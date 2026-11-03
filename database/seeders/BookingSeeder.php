<?php

namespace Database\Seeders;

use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use App\Models\Booking;
use App\Models\Homestay;
use App\Models\Kamar;
use App\Models\PaketWisata;
use App\Models\Pembayaran;
use App\Models\Ulasan;
use App\Models\Wisatawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $this->storeDemoProof();

        $bookings = [
            [
                'key' => 'pending',
                'username' => 'wisatawan1',
                'homestay' => 'Homestay Rumah Kayu',
                'kamar' => 'Kamar Melati',
                'checkin' => now()->addDays(9),
                'checkout' => now()->addDays(11),
                'tamu' => 2,
                'status' => StatusBooking::MenungguPembayaran,
                'paket' => null,
                'pembayaran' => null,
            ],
            [
                'key' => 'waiting',
                'username' => 'wisatawan2',
                'homestay' => 'Griya Sawah Hijau',
                'kamar' => 'Kamar Melati',
                'checkin' => now()->addDays(4),
                'checkout' => now()->addDays(6),
                'tamu' => 2,
                'status' => StatusBooking::MenungguVerifikasi,
                'paket' => null,
                'pembayaran' => StatusPembayaran::Menunggu,
            ],
            [
                'key' => 'confirmed',
                'username' => 'wisatawan3',
                'homestay' => 'Omah Jati',
                'kamar' => 'Kamar Melati',
                'checkin' => now()->addDays(20),
                'checkout' => now()->addDays(23),
                'tamu' => 2,
                'status' => StatusBooking::Dikonfirmasi,
                'paket' => ['Trekking Terasering', 2],
                'pembayaran' => StatusPembayaran::Terverifikasi,
            ],
            [
                'key' => 'completed',
                'username' => 'wisatawan1',
                'homestay' => 'Homestay Rumah Kayu',
                'kamar' => 'Kamar Melati',
                'checkin' => now()->subDays(12),
                'checkout' => now()->subDays(10),
                'tamu' => 2,
                'status' => StatusBooking::Selesai,
                'paket' => ['Keliling Desa Adat', 2],
                'pembayaran' => StatusPembayaran::Terverifikasi,
            ],
            [
                'key' => 'rejected',
                'username' => 'wisatawan1',
                'homestay' => 'Griya Sawah Hijau',
                'kamar' => 'Kamar Anggrek',
                'checkin' => now()->addDays(15),
                'checkout' => now()->addDays(17),
                'tamu' => 2,
                'status' => StatusBooking::Ditolak,
                'paket' => null,
                'pembayaran' => StatusPembayaran::Terverifikasi,
            ],
            [
                'key' => 'cancelled',
                'username' => 'wisatawan2',
                'homestay' => 'Rumah Bambu Lestari',
                'kamar' => 'Kamar Melati',
                'checkin' => now()->addDays(30),
                'checkout' => now()->addDays(32),
                'tamu' => 2,
                'status' => StatusBooking::Dibatalkan,
                'paket' => null,
                'pembayaran' => null,
            ],
        ];

        foreach ($bookings as $data) {
            $wisatawan = Wisatawan::whereHas('user', fn ($query) => $query->where('username', $data['username']))->firstOrFail();
            $homestay = Homestay::where('nama_homestay', $data['homestay'])->firstOrFail();
            $kamar = Kamar::where('id_homestay', $homestay->id_homestay)->where('nama_kamar', $data['kamar'])->firstOrFail();

            $package = null;
            $subtotal = 0;

            if ($data['paket']) {
                $package = PaketWisata::where('id_homestay', $homestay->id_homestay)
                    ->where('nama_paket', $data['paket'][0])
                    ->firstOrFail();
                $subtotal = (float) $package->harga * $data['paket'][1];
            }

            $nights = $data['checkin']->diffInDays($data['checkout']);
            $total = ((float) $kamar->harga_per_malam * $nights) + $subtotal;

            $booking = Booking::updateOrCreate(
                [
                    'id_wisatawan' => $wisatawan->id_wisatawan,
                    'id_kamar' => $kamar->id_kamar,
                    'tgl_checkin' => $data['checkin']->toDateString(),
                ],
                [
                    'tgl_booking' => now()->subDays(2)->toDateString(),
                    'tgl_checkout' => $data['checkout']->toDateString(),
                    'jumlah_tamu' => $data['tamu'],
                    'total_harga' => $total,
                    'status' => $data['status']->value,
                ]
            );

            if ($package) {
                $booking->paket()->sync([
                    $package->id_paket => [
                        'jumlah_peserta' => $data['paket'][1],
                        'subtotal' => $subtotal,
                    ],
                ]);
            } else {
                $booking->paket()->sync([]);
            }

            if ($data['pembayaran']) {
                Pembayaran::updateOrCreate(
                    ['id_booking' => $booking->id_booking],
                    [
                        'metode' => 'Transfer Bank',
                        'jumlah' => $total,
                        'bukti_transfer' => 'pembayaran/demo-bukti-transfer.pdf',
                        'tgl_bayar' => now()->subDay(),
                        'status_verifikasi' => $data['pembayaran']->value,
                    ]
                );
            }

            if ($data['key'] === 'completed') {
                Ulasan::updateOrCreate(
                    ['id_booking' => $booking->id_booking],
                    [
                        'rating' => 5,
                        'komentar' => 'Suasananya sangat tenang, tuan rumah ramah, dan sarapannya lezat. Kami akan kembali lagi.',
                        'tgl_ulasan' => now()->subDays(9)->toDateString(),
                    ]
                );
            }
        }
    }

    private function storeDemoProof(): void
    {
        $path = 'pembayaran/demo-bukti-transfer.pdf';

        if (Storage::disk('local')->exists($path)) {
            return;
        }

        $pdf = "%PDF-1.4\n"
            ."1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n"
            ."2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n"
            ."3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 300 200]/Contents 4 0 R/Resources<</Font<</F1 5 0 R>>>>>>endobj\n"
            ."4 0 obj<</Length 60>>stream\n"
            ."BT /F1 12 Tf 40 100 Td (Bukti Transfer Demo HomeStay Go) Tj ET\n"
            ."endstream endobj\n"
            ."5 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj\n"
            ."trailer<</Size 6/Root 1 0 R>>\n%%EOF";

        Storage::disk('local')->put($path, $pdf);
    }
}
