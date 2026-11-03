<?php

namespace App\Services;

use App\Enums\StatusBooking;
use App\Enums\StatusKamar;
use App\Enums\StatusVerifikasi;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\PaketWisata;
use App\Models\Wisatawan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Create a booking inside a transaction while locking the room row.
     * This serialises concurrent requests for the same room.
     *
     * @param  array{tgl_checkin: string, tgl_checkout: string, jumlah_tamu: int, paket?: array<int|string, int>}  $data
     */
    public function create(Wisatawan $wisatawan, Kamar $kamar, array $data): Booking
    {
        return DB::transaction(function () use ($wisatawan, $kamar, $data) {
            $room = Kamar::query()
                ->with('homestay')
                ->whereKey($kamar->id_kamar)
                ->lockForUpdate()
                ->firstOrFail();

            if ($room->status !== StatusKamar::Tersedia) {
                throw ValidationException::withMessages([
                    'id_kamar' => 'Kamar ini sedang tidak tersedia untuk dipesan.',
                ]);
            }

            if ($room->homestay->status_verifikasi !== StatusVerifikasi::Disetujui) {
                throw ValidationException::withMessages([
                    'id_kamar' => 'Homestay ini belum disetujui oleh pengelola desa wisata.',
                ]);
            }

            $checkin = Carbon::parse($data['tgl_checkin'])->startOfDay();
            $checkout = Carbon::parse($data['tgl_checkout'])->startOfDay();

            if ($checkout->lessThanOrEqualTo($checkin)) {
                throw ValidationException::withMessages([
                    'tgl_checkout' => 'Tanggal check-out harus setelah tanggal check-in.',
                ]);
            }

            if ($checkin->lessThan(today())) {
                throw ValidationException::withMessages([
                    'tgl_checkin' => 'Tanggal check-in tidak boleh di masa lalu.',
                ]);
            }

            if ((int) $data['jumlah_tamu'] > $room->kapasitas) {
                throw ValidationException::withMessages([
                    'jumlah_tamu' => 'Jumlah tamu melebihi kapasitas kamar (maksimal '.$room->kapasitas.' orang).',
                ]);
            }

            $overlap = Booking::query()
                ->where('id_kamar', $room->id_kamar)
                ->whereIn('status', StatusBooking::activeValues())
                ->overlapping($checkin->toDateString(), $checkout->toDateString())
                ->lockForUpdate()
                ->exists();

            if ($overlap) {
                throw ValidationException::withMessages([
                    'tgl_checkin' => 'Kamar sudah dipesan pada rentang tanggal tersebut. Silakan pilih tanggal atau kamar lain.',
                ]);
            }

            $nights = $checkin->diffInDays($checkout);
            $roomTotal = (float) $room->harga_per_malam * $nights;

            $packageQuantities = collect($data['paket'] ?? [])
                ->filter(fn ($quantity, $packageId) => is_numeric($packageId) && (int) $quantity > 0)
                ->mapWithKeys(fn ($quantity, $packageId) => [(int) $packageId => (int) $quantity]);

            $packages = PaketWisata::query()
                ->whereIn('id_paket', $packageQuantities->keys())
                ->get()
                ->keyBy('id_paket');

            if ($packages->count() !== $packageQuantities->count()) {
                throw ValidationException::withMessages([
                    'paket' => 'Salah satu paket wisata yang dipilih tidak valid.',
                ]);
            }

            $packageTotal = 0;

            foreach ($packages as $packageId => $package) {
                if ($package->id_homestay !== $room->id_homestay) {
                    throw ValidationException::withMessages([
                        'paket' => 'Paket wisata yang dipilih bukan milik homestay ini.',
                    ]);
                }

                $packageTotal += (float) $package->harga * $packageQuantities[$packageId];
            }

            $total = $roomTotal + $packageTotal;

            $booking = Booking::create([
                'id_wisatawan' => $wisatawan->id_wisatawan,
                'id_kamar' => $room->id_kamar,
                'tgl_booking' => today()->toDateString(),
                'tgl_checkin' => $checkin->toDateString(),
                'tgl_checkout' => $checkout->toDateString(),
                'jumlah_tamu' => (int) $data['jumlah_tamu'],
                'total_harga' => $total,
                'status' => StatusBooking::MenungguPembayaran->value,
            ]);

            foreach ($packages as $packageId => $package) {
                $quantity = $packageQuantities[$packageId];

                $booking->bookingPaket()->create([
                    'id_paket' => $package->id_paket,
                    'jumlah_peserta' => $quantity,
                    'subtotal' => (float) $package->harga * $quantity,
                ]);
            }

            return $booking->load(['kamar.homestay', 'paket', 'pembayaran']);
        }, 3);
    }
}
