<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wisatawan;
use Illuminate\Database\Eloquent\Factories\Factory;

class WisatawanFactory extends Factory
{
    protected $model = Wisatawan::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory()->wisatawan(),
            'nama_wisatawan' => fake()->name(),
            'no_hp' => fake()->numerify('08##########'),
            'asal_kota' => fake()->city(),
        ];
    }
}
