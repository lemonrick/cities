<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GeocodeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:geocode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Geocode city data using Nominatim';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cities = City::all();

        foreach ($cities as $city) {
            $address = $city->city_hall_address . ', Slovakia';
            $coordinates = $this->geocodeAddress($address);

            if ($coordinates) {
                $city->update([
                    'lat' => $coordinates['lat'],
                    'lng' => $coordinates['lon'],
                ]);

                $this->info("Geocoded {$city->name}: lat={$coordinates['lat']}, lng={$coordinates['lon']}");
            } else {
                $this->error("Failed to geocode {$city->name}");
            }
        }

        $this->info('Geocoding completed.');
    }

    private function geocodeAddress($address)
    {
        $response = Http::get('https://nominatim.openstreetmap.org/search', [
            'q' => $address,
            'format' => 'json',
            'polygon' => 1,
            'addressdetails' => 1,
        ]);

        $data = $response->json();

        if (!empty($data) && isset($data[0]['lat'], $data[0]['lon'])) {
            return [
                'lat' => $data[0]['lat'],
                'lon' => $data[0]['lon'],
            ];
        }

        return null;
    }


}
