<?php

namespace Database\Factories;

use App\Models\ArShelter;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArShelterFactory extends Factory
{
    protected $model = ArShelter::class;

    public function definition(): array
    {
        return [
            'nama' => 'Shelter '.fake()->unique()->company(),
            'kategori' => fake()->randomElement(['Homestay', 'Villa', 'Cottage', 'Guest House', 'Resort / Cabin']),
            'kecamatan' => 'BATURRADEN',
            'desa' => 'KETENGER',
            'alamat' => fake()->address(),
            'latitude' => -7.3300000 + fake()->randomFloat(6, -0.05, 0.05),
            'longitude' => 109.2250000 + fake()->randomFloat(6, -0.05, 0.05),
            'no_telepon' => null,
            'no_hp' => '0812'.fake()->numerify('########'),
            'instagram' => '@'.fake()->userName(),
            'is_mitra_pokdarwis' => true,
        ];
    }
}
