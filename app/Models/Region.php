<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'url'];

    public function cities(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
