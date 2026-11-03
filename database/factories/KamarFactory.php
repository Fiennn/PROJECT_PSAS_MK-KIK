<?php

namespace Database\Factories;

use App\Enums\StatusKamar;
use App\Models\Homestay;
use App\Models\Kamar;
use Illuminate\Database\Eloquent\Factories\Factory;

class KamarFactory extends Factory
{
    protected $model = Kamar::class;

    public function definition(): array
    {
        return [
            'id_homestay' => Homestay::factory(),
            'nama_kamar' => 'Kamar '.fake()->unique()->firstName(),
            'kapasitas' => fake()->numberBetween(2, 4),
            'harga_per_malam' => fake()->numberBetween(150, 600) * 1000,
            'status' => StatusKamar::Tersedia->value,
        ];
    }

    public function nonaktif(): static
    {
        return $this->state(fn () => ['status' => StatusKamar::Nonaktif->value]);
    }
}
