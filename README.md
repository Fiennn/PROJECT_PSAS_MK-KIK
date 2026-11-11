# HomeStay Go

HomeStay Go adalah aplikasi pemesanan homestay untuk desa wisata Indonesia. Aplikasi ini menghubungkan wisatawan dengan pemilik homestay lokal, dengan admin desa wisata sebagai verifikator homestay dan bukti pembayaran.

Seluruh antarmuka menggunakan Bahasa Indonesia. Kode, komentar, dan penamaan teknis menggunakan Bahasa Inggris.

## Fitur Utama

### Publik
- Halaman beranda dengan homestay unggulan dan paket wisata desa.
- Katalog homestay dengan pencarian, filter harga, kapasitas, dan fasilitas.
- Detail homestay: kamar, fasilitas, paket wisata, dan ulasan wisatawan.
- Pengecekan ketersediaan kamar berdasarkan tanggal check-in/check-out.
- Halaman masuk dan pendaftaran wisatawan/pemilik homestay.

### Wisatawan
- Membuat pemesanan kamar dengan perhitungan harga otomatis.
- Memilih paket wisata tambahan saat memesan.
- Mengunggah bukti pembayaran privat (JPG, PNG, atau PDF maksimal 2 MB).
- Melihat status pesanan, riwayat pembayaran, dan membatalkan pesanan yang masih menunggu pembayaran.
- Menulis ulasan setelah menginap selesai.

### Pemilik Homestay
- Mengajukan homestay baru untuk diverifikasi admin.
- Mengelola data homestay, kamar, harga, status kamar, dan paket wisata.
- Melihat pesanan masuk dan detail tamu.
- Mengonfirmasi pesanan setelah pembayaran terverifikasi.
- Menolak pesanan atau menandai pesanan selesai setelah tanggal check-out.
- Melihat laporan pendapatan berdasarkan rentang tanggal check-in.

### Admin Desa Wisata
- Melihat ringkasan operasional desa wisata.
- Mencari dan memfilter pengguna, serta mengaktifkan/menonaktifkan akun.
- Menyetujui atau menolak pengajuan homestay.
- Memverifikasi atau menolak bukti pembayaran.
- Mengelola master data fasilitas.
- Mencetak laporan booking dan laporan pendapatan.

## Teknologi

- **Laravel 11** (PHP 8.2+)
- **MySQL / MariaDB**
- **Blade** + **Tailwind CSS** + **Alpine.js**
- **Vite** untuk aset frontend
- **PHPUnit** untuk pengujian

## Alur Bisnis Singkat

1. Pemilik mendaftar dan mengajukan homestay. Status awal: `menunggu`.
2. Admin menyetujui homestay sehingga tampil di katalog publik.
3. Wisatawan memilih kamar, tanggal, dan paket wisata lalu membuat pesanan.
4. Sistem memeriksa bentrok jadwal kamar dan menyimpan snapshot harga.
5. Wisatawan mentransfer dan mengunggah bukti pembayaran. Status pesanan menjadi `menunggu_verifikasi`.
6. Admin memverifikasi pembayaran. Jika ditolak, pesanan kembali ke `menunggu_pembayaran`.
7. Pemilik mengonfirmasi pesanan setelah pembayaran terverifikasi.
8. Setelah tanggal check-out, pemilik menandai pesanan `selesai`.
9. Wisatawan dapat menulis satu ulasan untuk pesanan yang selesai.

## Persyaratan

- PHP 8.2 atau lebih baru dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, dan `zip`.
- Composer 2.
- Node.js 18+ dan npm.
- MySQL 8 / MariaDB 10.4+ (contoh: XAMPP).

## Instalasi

```bash
git clone <url-repositori> homestay-go
cd homestay-go

composer install
npm install

copy .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database di `.env`:

```dotenv
APP_NAME="HomeStay Go"
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestay_go
DB_USERNAME=root
DB_PASSWORD=
```

Buat database, lalu jalankan migrasi dan seeder:

```bash
mysql -u root -e "CREATE DATABASE homestay_go CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate:fresh --seed
```

Buat symbolic link storage (diperlukan untuk aset publik) dan jalankan aplikasi:

```bash
php artisan storage:link
npm run build
php artisan serve
```

Buka `http://localhost:8000`.

## Aplikasi Android native

Project Android native sederhana tersedia di folder `android/HomeStayGo`.

1. Buka folder tersebut dengan **Android Studio**.
2. Jalankan backend Laravel:

```bash
php artisan serve
```

3. Untuk emulator Android, base URL API sudah diatur ke `http://10.0.2.2:8000/`.
4. Untuk HP fisik, ubah `BASE_URL` di `ApiClient.kt` menjadi IP komputer.

Untuk mode pengembangan frontend:

```bash
npm run dev
```

## Akun Demo

Seeder menyediakan akun berikut:

| Peran | Username | Password |
| --- | --- | --- |
| Admin | `admin` | `admin123` |
| Pemilik Homestay | `pemilik1` | `pemilik123` |
| Pemilik Homestay | `pemilik2` | `pemilik123` |
| Wisatawan | `wisatawan1` | `wisatawan123` |
| Wisatawan | `wisatawan2` | `wisatawan123` |
| Wisatawan | `wisatawan3` | `wisatawan123` |

Seeder juga membuat contoh homestay, kamar, paket wisata, pesanan dengan berbagai status, pembayaran, dan ulasan agar setiap dashboard dapat langsung dijelajahi.

## Pengujian

Pengujian menggunakan database MySQL terpisah `homestay_go_test` sesuai konfigurasi `phpunit.xml`.

```bash
mysql -u root -e "CREATE DATABASE homestay_go_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan test
```

Cakupan pengujian utama:

- Pencegahan bentrok jadwal kamar.
- Perhitungan dan snapshot harga pesanan.
- Pembatasan akses berdasarkan peran.
- Kelayakan dan validasi ulasan.
- Alur verifikasi pembayaran dan konfirmasi pemilik.
- Pengelolaan homestay, kamar, paket, dan fasilitas.
- Laporan booking dan pendapatan.

## Struktur Direktori Penting

```text
app/
  Enums/                     Status dan peran dalam bentuk enum
  Http/Controllers/
    Admin/                   Fitur admin desa wisata
    Auth/                    Login dan registrasi
    Pemilik/                 Fitur pemilik homestay
    Public/                  Beranda, katalog, detail, ketersediaan
    Shared/                  Streaming bukti pembayaran privat
    Wisatawan/               Pemesanan, pembayaran, ulasan
  Http/Middleware/           Middleware active dan role
  Http/Requests/             Validasi formulir
  Models/                    Model domain
  Policies/                  Otorisasi per model
  Services/BookingService.php Logika bentrok jadwal dan harga
database/
  migrations/                Skema database
  seeders/                   Data demo
resources/
  css/app.css                Tailwind dan design token
  js/app.js                  Alpine.js dan helper harga
  views/                     Blade per peran
routes/web.php               Seluruh rute aplikasi
tests/Feature/               Pengujian fitur
```

## Aturan Penting

- Pemesanan yang berstatus `menunggu_pembayaran`, `menunggu_verifikasi`, dan `dikonfirmasi` memblokir kamar pada tanggal yang sama.
- Tanggal check-out dianggap sebagai hari kamar tersedia kembali (check-in baru boleh di tanggal check-out pesanan sebelumnya).
- Harga kamar dan paket disimpan sebagai snapshot saat pesanan dibuat, sehingga perubahan harga tidak mengubah pesanan lama.
- Bukti pembayaran disimpan di disk privat dengan nama acak dan hanya dapat diakses admin serta wisatawan pemilik pesanan.
- Pemilik hanya dapat mengonfirmasi pesanan jika sudah ada pembayaran terverifikasi.
- Pemilik hanya dapat menandai selesai setelah tanggal check-out.
- Kamar dan paket wisata yang sudah memiliki riwayat pesanan tidak dapat dihapus agar data transaksi tetap utuh.
- Pendapatan dihitung dari pesanan berstatus `dikonfirmasi` dan `selesai`.

## Lisensi

Proyek ini dibuat untuk kebutuhan MVP dan pembelajaran. Silakan sesuaikan lisensi sesuai kebutuhan institusi atau desa wisata Anda.
