<?php

namespace Database\Factories;

use App\Models\Fasilitas;
use App\Models\Homestay;
use App\Models\HomestayFasilitas;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomestayFasilitasFactory extends Factory
{
    protected $model = HomestayFasilitas::class;

    public function definition(): array
    {
        return [
            'id_homestay' => Homestay::factory(),
            'id_fasilitas' => Fasilitas::factory(),
        ];
    }
}
