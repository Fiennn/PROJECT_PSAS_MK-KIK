# HomeStay Go Android (Native)

Project Android native sederhana untuk menampilkan daftar homestay dari API Laravel HomeStay Go.

## Cara membuka

1. Buka **Android Studio**.
2. Pilih **Open**.
3. Arahkan ke folder:

```text
android/HomeStayGo
```

4. Tunggu Gradle sync selesai.
5. Jalankan emulator Android.
6. Pastikan backend Laravel berjalan:

```powershell
cd "D:\PROJECT PSAS KIK & MK"
php artisan serve
```

## Base URL API

Untuk emulator Android, base URL default adalah:

```text
http://10.0.2.2:8000/
```

`10.0.2.2` adalah alias emulator untuk `localhost` komputer.

Jika memakai HP fisik, ubah `BASE_URL` di:

```text
app/src/main/java/com/homestaygo/app/ApiClient.kt
```

menjadi IP komputer, contoh:

```kotlin
private const val BASE_URL = "http://192.168.1.10:8000/"
```

## Fitur saat ini

- Menampilkan daftar homestay dari endpoint `GET /api/homestay`.
- Membuka halaman detail sederhana saat item diklik.

## Pengembangan berikutnya

- Login wisatawan/pemilik.
- Detail homestay lengkap (kamar, paket, fasilitas).
- Form pemesanan.
- Upload bukti pembayaran.
- Status pesanan.
- Integrasi token auth (Sanctum) di Laravel.
