<?php

namespace Database\Factories;

use App\Enums\StatusPembayaran;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class PembayaranFactory extends Factory
{
    protected $model = Pembayaran::class;

    public function definition(): array
    {
        return [
            'id_booking' => Booking::factory(),
            'metode' => 'Transfer Bank',
            'jumlah' => 500000,
            'bukti_transfer' => 'pembayaran/dummy.pdf',
            'tgl_bayar' => now(),
            'status_verifikasi' => StatusPembayaran::Menunggu->value,
        ];
    }

    public function terverifikasi(): static
    {
        return $this->state(fn () => ['status_verifikasi' => StatusPembayaran::Terverifikasi->value]);
    }

    public function ditolak(): static
    {
        return $this->state(fn () => ['status_verifikasi' => StatusPembayaran::Ditolak->value]);
    }
}
