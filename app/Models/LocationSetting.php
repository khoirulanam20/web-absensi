<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationSetting extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'double',
            'longitude' => 'double',
            'radius' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    function getLatLngAttribute(): array|null
    {
        if (is_null($this->latitude) || is_null($this->longitude)) {
            return null;
        }
        return [
            'lat' => $this->latitude,
            'lng' => $this->longitude
        ];
    }
}
