<?php

namespace Database\Factories;

use App\Models\Homestay;
use App\Models\PaketWisata;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaketWisataFactory extends Factory
{
    protected $model = PaketWisata::class;

    public function definition(): array
    {
        return [
            'id_homestay' => Homestay::factory(),
            'nama_paket' => 'Paket '.fake()->unique()->words(2, true),
            'deskripsi' => fake()->paragraph(),
            'harga' => fake()->numberBetween(50, 300) * 1000,
            'durasi' => fake()->randomElement(['2 jam', '4 jam', '1 hari']),
        ];
    }
}
