<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use Illuminate\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            'WiFi Gratis',
            'Parkir Kendaraan',
            'Sarapan Pagi',
            'Kamar Mandi Dalam',
            'Air Panas',
            'AC',
            'Dapur Bersama',
            'Ruang Santai',
            'Pemandangan Sawah',
            'Pemandu Wisata Lokal',
        ];

        foreach ($facilities as $facility) {
            Fasilitas::updateOrCreate(['nama_fasilitas' => $facility]);
        }
    }
}
