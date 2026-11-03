<?php

namespace Database\Factories;

use App\Models\PemilikHomestay;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PemilikHomestayFactory extends Factory
{
    protected $model = PemilikHomestay::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory()->pemilik(),
            'nama_pemilik' => fake()->name(),
            'no_hp' => fake()->numerify('08##########'),
            'alamat' => fake()->address(),
        ];
    }
}
