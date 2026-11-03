<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingPaket;
use App\Models\PaketWisata;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingPaketFactory extends Factory
{
    protected $model = BookingPaket::class;

    public function definition(): array
    {
        return [
            'id_booking' => Booking::factory(),
            'id_paket' => PaketWisata::factory(),
            'jumlah_peserta' => 2,
            'subtotal' => 200000,
        ];
    }
}
