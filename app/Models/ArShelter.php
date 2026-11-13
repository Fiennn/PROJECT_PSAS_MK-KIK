<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArShelter extends Model
{
    use HasFactory;

    protected $table = 'ar_shelters';

    protected $primaryKey = 'id_shelter';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'kategori',
        'kecamatan',
        'desa',
        'alamat',
        'latitude',
        'longitude',
        'no_telepon',
        'no_hp',
        'instagram',
        'is_mitra_pokdarwis',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'is_mitra_pokdarwis' => 'boolean',
        ];
    }

    /**
     * Calculate Haversine distance in kilometers from given coordinate.
     */
    public function distanceTo(float $userLat, float $userLng): float
    {
        $earthRadiusKm = 6371;

        $dLat = deg2rad($this->latitude - $userLat);
        $dLng = deg2rad($this->longitude - $userLng);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($userLat)) * cos(deg2rad($this->latitude)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadiusKm * $c, 2);
    }

    /**
     * Calculate initial bearing (compass heading azimuth in degrees 0-360) from user to shelter.
     */
    public function bearingFrom(float $userLat, float $userLng): float
    {
        $lat1 = deg2rad($userLat);
        $lat2 = deg2rad($this->latitude);
        $dLng = deg2rad($this->longitude - $userLng);

        $y = sin($dLng) * cos($lat2);
        $x = cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($dLng);

        $bearingRad = atan2($y, $x);
        $bearingDeg = fmod(rad2deg($bearingRad) + 360, 360);

        return round($bearingDeg, 1);
    }
}
