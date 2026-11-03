<?php

namespace Database\Factories;

use App\Enums\StatusVerifikasi;
use App\Models\Homestay;
use App\Models\PemilikHomestay;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomestayFactory extends Factory
{
    protected $model = Homestay::class;

    public function definition(): array
    {
        return [
            'id_pemilik' => PemilikHomestay::factory(),
            'nama_homestay' => 'Homestay '.fake()->unique()->city(),
            'deskripsi' => fake()->paragraph(),
            'alamat' => fake()->address(),
            'status_verifikasi' => StatusVerifikasi::Disetujui->value,
        ];
    }

    public function menunggu(): static
    {
        return $this->state(fn () => ['status_verifikasi' => StatusVerifikasi::Menunggu->value]);
    }

    public function ditolak(): static
    {
        return $this->state(fn () => ['status_verifikasi' => StatusVerifikasi::Ditolak->value]);
    }
}
