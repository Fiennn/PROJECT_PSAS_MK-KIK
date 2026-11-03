<?php

namespace Database\Factories;

use App\Models\Fasilitas;
use Illuminate\Database\Eloquent\Factories\Factory;

class FasilitasFactory extends Factory
{
    protected $model = Fasilitas::class;

    public function definition(): array
    {
        return [
            'nama_fasilitas' => fake()->unique()->words(2, true),
        ];
    }
}
