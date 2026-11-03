<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Ulasan;
use Illuminate\Database\Eloquent\Factories\Factory;

class UlasanFactory extends Factory
{
    protected $model = Ulasan::class;

    public function definition(): array
    {
        return [
            'id_booking' => Booking::factory(),
            'rating' => fake()->numberBetween(3, 5),
            'komentar' => fake()->sentence(12),
            'tgl_ulasan' => now()->toDateString(),
        ];
    }
}
