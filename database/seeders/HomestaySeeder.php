<?php

namespace Database\Seeders;

use App\Enums\StatusVerifikasi;
use App\Models\Fasilitas;
use App\Models\Homestay;
use App\Models\Kamar;
use App\Models\PemilikHomestay;
use App\Models\PaketWisata;
use Illuminate\Database\Seeder;

class HomestaySeeder extends Seeder
{
    public function run(): void
    {
        $pemilik1 = PemilikHomestay::whereHas('user', fn ($query) => $query->where('username', 'pemilik1'))->firstOrFail();
        $pemilik2 = PemilikHomestay::whereHas('user', fn ($query) => $query->where('username', 'pemilik2'))->firstOrFail();

        $allFacilities = Fasilitas::pluck('id_fasilitas', 'nama_fasilitas');

        $homestays = [
            [
                'pemilik' => $pemilik1,
                'nama' => 'Homestay Rumah Kayu',
                'deskripsi' => 'Rumah kayu tradisional Bali dengan halaman hijau dan suasana tenang. Cocok untuk keluarga yang ingin merasakan kehidupan desa Penglipuran.',
                'alamat' => 'Banjar Penglipuran, Bangli, Bali',
                'status' => StatusVerifikasi::Disetujui,
                'fasilitas' => ['WiFi Gratis', 'Parkir Kendaraan', 'Sarapan Pagi', 'Kamar Mandi Dalam', 'Pemandangan Sawah'],
                'kamar' => [
                    ['nama' => 'Kamar Melati', 'kapasitas' => 2, 'harga' => 250000, 'status' => 'tersedia'],
                    ['nama' => 'Kamar Anggrek', 'kapasitas' => 4, 'harga' => 400000, 'status' => 'tersedia'],
                ],
                'paket' => [
                    ['nama' => 'Keliling Desa Adat', 'deskripsi' => 'Tur berpemandu menyusuri rumah adat dan kebun bambu bersama warga lokal.', 'harga' => 75000, 'durasi' => '2 jam'],
                    ['nama' => 'Kelas Memasak Tradisional', 'deskripsi' => 'Belajar memasak lawar dan sate plecing bersama ibu-ibu desa.', 'harga' => 120000, 'durasi' => '3 jam'],
                ],
            ],
            [
                'pemilik' => $pemilik1,
                'nama' => 'Griya Sawah Hijau',
                'deskripsi' => 'Homestay sederhana dengan teras menghadap sawah bertingkat. Bangun pagi dengan suara burung dan udara sejuk Tegalalang.',
                'alamat' => 'Tegalalang, Gianyar, Bali',
                'status' => StatusVerifikasi::Disetujui,
                'fasilitas' => ['WiFi Gratis', 'Sarapan Pagi', 'Ruang Santai', 'Pemandangan Sawah', 'Pemandu Wisata Lokal'],
                'kamar' => [
                    ['nama' => 'Kamar Melati', 'kapasitas' => 2, 'harga' => 200000, 'status' => 'tersedia'],
                    ['nama' => 'Kamar Anggrek', 'kapasitas' => 3, 'harga' => 300000, 'status' => 'tersedia'],
                ],
                'paket' => [
                    ['nama' => 'Jelajah Sawah Subak', 'deskripsi' => 'Berjalan menyusuri pematang sawah dan belajar sistem irigasi subak.', 'harga' => 60000, 'durasi' => '90 menit'],
                ],
            ],
            [
                'pemilik' => $pemilik2,
                'nama' => 'Omah Jati',
                'deskripsi' => 'Rumah joglo dengan perabot kayu jati asli di tengah Desa Jatiluwih. Pemandangan sawah terasering UNESCO hanya beberapa langkah dari kamar.',
                'alamat' => 'Desa Jatiluwih, Tabanan, Bali',
                'status' => StatusVerifikasi::Disetujui,
                'fasilitas' => ['WiFi Gratis', 'Parkir Kendaraan', 'Sarapan Pagi', 'Air Panas', 'Pemandangan Sawah', 'Dapur Bersama'],
                'kamar' => [
                    ['nama' => 'Kamar Melati', 'kapasitas' => 2, 'harga' => 325000, 'status' => 'tersedia'],
                    ['nama' => 'Kamar Kenanga', 'kapasitas' => 4, 'harga' => 475000, 'status' => 'tersedia'],
                ],
                'paket' => [
                    ['nama' => 'Trekking Terasering', 'deskripsi' => 'Trekking pagi melewati terasering Jatiluwih bersama pemandu desa.', 'harga' => 90000, 'durasi' => '3 jam'],
                    ['nama' => 'Makan Malam Warga', 'deskripsi' => 'Makan malam hidangan rumahan bersama keluarga tuan rumah.', 'harga' => 65000, 'durasi' => '2 jam'],
                ],
            ],
            [
                'pemilik' => $pemilik2,
                'nama' => 'Rumah Bambu Lestari',
                'deskripsi' => 'Bangunan bambu ramah lingkungan dengan kolam kecil dan kebun sayur. Rasakan hidup lambat khas Desa Sade, Lombok.',
                'alamat' => 'Desa Sade, Lombok Tengah, NTB',
                'status' => StatusVerifikasi::Disetujui,
                'fasilitas' => ['WiFi Gratis', 'Sarapan Pagi', 'Kamar Mandi Dalam', 'Ruang Santai', 'Pemandu Wisata Lokal'],
                'kamar' => [
                    ['nama' => 'Kamar Melati', 'kapasitas' => 2, 'harga' => 275000, 'status' => 'tersedia'],
                    ['nama' => 'Kamar Anggrek', 'kapasitas' => 2, 'harga' => 225000, 'status' => 'nonaktif'],
                ],
                'paket' => [
                    ['nama' => 'Tenun Kain Sade', 'deskripsi' => 'Belajar menenun kain tradisional bersama perajin perempuan Desa Sade.', 'harga' => 85000, 'durasi' => '2 jam'],
                ],
            ],
            [
                'pemilik' => $pemilik1,
                'nama' => 'Homestay Bukit Menoreh',
                'deskripsi' => 'Homestay baru di lereng Bukit Menoreh dengan pemandangan kota Yogyakarta dari kejauhan. Sedang menunggu verifikasi pengelola desa wisata.',
                'alamat' => 'Samigaluh, Kulon Progo, DIY',
                'status' => StatusVerifikasi::Menunggu,
                'fasilitas' => ['WiFi Gratis', 'Parkir Kendaraan', 'Sarapan Pagi'],
                'kamar' => [
                    ['nama' => 'Kamar Melati', 'kapasitas' => 2, 'harga' => 180000, 'status' => 'tersedia'],
                ],
                'paket' => [],
            ],
            [
                'pemilik' => $pemilik1,
                'nama' => 'Homestay Baturraden Asri',
                'deskripsi' => 'Homestay kayu alami di lereng Gunung Slamet dengan pemandangan sawah terasering dan udara pegunungan yang sejuk. Sangat dekat dengan Lokawisata Baturraden.',
                'alamat' => 'Desa Karangsalam, Baturraden, Banyumas, Jawa Tengah',
                'status' => StatusVerifikasi::Disetujui,
                'fasilitas' => ['WiFi Gratis', 'Parkir Kendaraan', 'Sarapan Pagi', 'Air Panas', 'Pemandangan Sawah'],
                'kamar' => [
                    ['nama' => 'Kamar Slamet', 'kapasitas' => 2, 'harga' => 250000, 'status' => 'tersedia'],
                    ['nama' => 'Kamar Curug', 'kapasitas' => 4, 'harga' => 380000, 'status' => 'tersedia'],
                ],
                'paket' => [
                    ['nama' => 'Tur Curug & Hutan Pinus', 'deskripsi' => 'Jelajah curug alami dan tracking hutan pinus dipandu pemuda karang taruna.', 'harga' => 60000, 'durasi' => '2 jam'],
                ],
            ],
            [
                'pemilik' => $pemilik2,
                'nama' => 'Pondok Kopi Priangan',
                'deskripsi' => 'Pondok di tengah kebun kopi Pangalengan. Pengajuan verifikasi ditolak karena data alamat belum lengkap.',
                'alamat' => 'Pangalengan, Bandung, Jawa Barat',
                'status' => StatusVerifikasi::Ditolak,
                'fasilitas' => ['WiFi Gratis', 'Parkir Kendaraan', 'Dapur Bersama'],
                'kamar' => [
                    ['nama' => 'Kamar Melati', 'kapasitas' => 3, 'harga' => 200000, 'status' => 'tersedia'],
                ],
                'paket' => [],
            ],
        ];

        foreach ($homestays as $data) {
            $homestay = Homestay::updateOrCreate(
                ['nama_homestay' => $data['nama']],
                [
                    'id_pemilik' => $data['pemilik']->id_pemilik,
                    'deskripsi' => $data['deskripsi'],
                    'alamat' => $data['alamat'],
                    'status_verifikasi' => $data['status']->value,
                ]
            );

            $homestay->fasilitas()->sync(
                collect($data['fasilitas'])->map(fn ($name) => $allFacilities[$name])->all()
            );

            foreach ($data['kamar'] as $room) {
                Kamar::updateOrCreate(
                    ['id_homestay' => $homestay->id_homestay, 'nama_kamar' => $room['nama']],
                    [
                        'kapasitas' => $room['kapasitas'],
                        'harga_per_malam' => $room['harga'],
                        'status' => $room['status'],
                    ]
                );
            }

            foreach ($data['paket'] as $package) {
                PaketWisata::updateOrCreate(
                    ['id_homestay' => $homestay->id_homestay, 'nama_paket' => $package['nama']],
                    [
                        'deskripsi' => $package['deskripsi'],
                        'harga' => $package['harga'],
                        'durasi' => $package['durasi'],
                    ]
                );
            }
        }
    }
}
