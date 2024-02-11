<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\District;
use App\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Support\Facades\File;

class DataImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [];
        $imgs = [];

        $client = new HttpBrowser(HttpClient::create());
        $crawler = $client->request('GET', 'https://www.e-obce.sk/kraj/NR.html');
        $districts = $crawler->filter('div#okres td a.okreslink');
        $districts->each(function (Crawler $district) use ($client, &$data, &$imgs) {
            $href = $district->attr('href');
            $text = $district->text();

            $newCrawler = $client->request('GET', $href);
            $headingCrawler = $newCrawler->filter('b:contains("Vyberte si obec alebo mesto z")');
            $tableCrawler = $headingCrawler->nextAll('table')->first();
            $cities = $tableCrawler->filter('td a');

            $towns = [];

            $cities->each(function (Crawler $city) use ($client, &$towns, &$imgs) {
                if (str_contains($city->attr('href'), 'www')) {

                    $newCrawler = $client->request('GET', $city->attr('href'));

                    // erb
                    $image = $newCrawler->filter('img[src*="/erb/"]');
                    $image = $image->extract(['src'])[0];
                    // erb

                    // mayor
                    $mayor = $newCrawler->filterXPath('//td[contains(text(), "Starosta:") or contains(text(), "Primátor:")]');
                    $mayor = $mayor->nextAll('td')->eq(0)->text();
                    // mayor

                    // email
                    $email = $newCrawler->filterXPath('//a[contains(@href, "mailto:")]')->first();
                    $email = $email->text();
                    // email

                    //zip
                    $tdElements = $newCrawler->filter('td[valign="top"]');
                    $zip = '';
                    $tdElements->each(function (Crawler $td) use (&$zip) {
                        if (preg_match('/\d+\s\d+|\b94615\b/', $td->text())) {
                            $zip = $td->text();
                            return false;
                        }
                        return true;
                    });
                    // zip

                    // address
                    $trElements = $newCrawler->filter('tr');
                    $address = '';
                    foreach ($trElements as $trElement) {
                        $tdElements = $trElement->getElementsByTagName('td');
                        if ($tdElements->length >= 2 && trim($tdElements[1]->nodeValue) === 'Email:') {
                            $address = $tdElements[0]->textContent;
                            break;
                        }
                    }
                    // address

                    // phone
                    $trElements = $newCrawler->filter('tr');
                    $phone = '';
                    foreach ($trElements as $trElement) {
                        $tdElements = $trElement->getElementsByTagName('td');
                        if ($tdElements->length >= 3 && trim($tdElements[2]->nodeValue) === 'Tel:') {
                            $nestedTable = $tdElements[3]->getElementsByTagName('table')->item(0);
                            if ($nestedTable) {
                                $nestedTd = $nestedTable->getElementsByTagName('td')->item(0);
                                if ($nestedTd) {
                                    $phone = trim($nestedTd->nodeValue);
                                }
                            }
                            break;
                        }
                    }
                    // phone

                    // fax
                    $fax = 'n/a';
                    foreach ($trElements as $trElement) {
                        $tdElements = $trElement->getElementsByTagName('td');
                        if ($tdElements->length >= 2 && trim($tdElements[1]->nodeValue) === 'Fax:') {
                            $fax = trim($tdElements[2]->nodeValue);
                            break;
                        }
                    }
                    // fax

                    // web
                    $trElements = $newCrawler->filter('tr');
                    $web = '';
                    foreach ($trElements as $trElement) {
                        $tdElements = $trElement->getElementsByTagName('td');
                        if ($tdElements->length >= 2 && trim($tdElements[1]->nodeValue) === 'Web:') {
                            $websiteNode = $tdElements[2]->getElementsByTagName('a')->item(0);
                            if ($websiteNode) {
                                $web = trim($websiteNode->nodeValue);
                            }
                            break;
                        }
                    }
                    // web

                    $imgs[] = $image;

                    $towns[] = [
                        'name' => $city->text(),
                        'url' => $city->attr('href'),
                        'image' => str_replace('https://www.e-obce.sk/erb/', '', $image),
                        'mayor_name' => $mayor,
                        'city_hall_address' => $address . ', ' . $zip,
                        'phone' => $phone,
                        'fax' => $fax,
                        'email' => $email,
                        'web_address' => $web
                    ];
                }
            });

            $data[] = [
                'name' => 'Okres ' . $text,
                'url' => $href,
                'towns' => $towns
            ];
        });

        $imgsPath = public_path('imgs');
        if (!File::isDirectory($imgsPath)) {
            File::makeDirectory($imgsPath, 0777, true);
        }
        File::cleanDirectory($imgsPath);

        $imgsPath = $imgsPath . '/';

        foreach ($imgs as $imageUrl) {
            $imageContent = file_get_contents($imageUrl);

            if ($imageContent !== false) {
                $imageName = basename($imageUrl);
                $path = $imgsPath . $imageName;
                file_put_contents($path, $imageContent);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        City::truncate();
        District::truncate();
        Region::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        DB::transaction(function () use ($data) {
            $region = Region::create([
                'name' => 'Nitriansky kraj',
                'url' => 'https://www.e-obce.sk/kraj/NR.html',
            ]);

            foreach ($data as $district) {

                $d = District::create([
                    'name' => 'Okres ' . $district['name'],
                    'url' => $district['url'],
                    'region_id' => $region->id
                ]);

                foreach ($district['towns'] as $city) {
                    City::create([
                        'district_id' => $d->id,
                        'name' => $city['name'],
                        'url' => $city['url'],
                        'image' => $city['image'],
                        'mayor_name' => $city['mayor_name'],
                        'city_hall_address' => $city['city_hall_address'],
                        'phone' => $city['phone'],
                        'fax' => $city['fax'],
                        'email' => $city['email'],
                        'web_address' => $city['web_address'],
                    ]);
                }
            }
        });

        $this->info('Data import completed successfully.');

    }
}
