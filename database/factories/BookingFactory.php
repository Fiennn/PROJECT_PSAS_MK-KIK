<?php

namespace Database\Factories;

use App\Enums\StatusBooking;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Wisatawan;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $checkin = now()->addDays(10);

        return [
            'id_wisatawan' => Wisatawan::factory(),
            'id_kamar' => Kamar::factory(),
            'tgl_booking' => now()->toDateString(),
            'tgl_checkin' => $checkin->toDateString(),
            'tgl_checkout' => $checkin->copy()->addDays(2)->toDateString(),
            'jumlah_tamu' => 2,
            'total_harga' => 500000,
            'status' => StatusBooking::MenungguPembayaran->value,
        ];
    }

    public function status(StatusBooking $status): static
    {
        return $this->state(fn () => ['status' => $status->value]);
    }
}
