<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';
    protected $primaryKey = 'id';

    protected $fillable = ['district_id',
        'name',
        'url',
        'image',
        'mayor_name',
        'city_hall_address',
        'phone',
        'fax',
        'email',
        'web_address',
        'lat',
        'lng'
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
