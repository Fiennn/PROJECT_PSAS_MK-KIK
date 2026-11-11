<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FasilitasController as AdminFasilitasController;
use App\Http\Controllers\Admin\HomestayVerificationController as AdminHomestayVerificationController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Pemilik\BookingController as PemilikBookingController;
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboardController;
use App\Http\Controllers\Pemilik\HomestayController as PemilikHomestayController;
use App\Http\Controllers\Pemilik\KamarController as PemilikKamarController;
use App\Http\Controllers\Pemilik\PaketWisataController as PemilikPaketWisataController;
use App\Http\Controllers\Pemilik\PendapatanController as PemilikPendapatanController;
use App\Http\Controllers\Public\CatalogController;
use App\Http\Controllers\Public\HomestayController;
use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Shared\PembayaranProofController;
use App\Http\Controllers\Wisatawan\BookingController;
use App\Http\Controllers\Wisatawan\DashboardController as WisatawanDashboardController;
use App\Http\Controllers\Wisatawan\PembayaranController;
use App\Http\Controllers\Wisatawan\UlasanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::get('/homestay', [CatalogController::class, 'index'])->name('homestay.index');
Route::get('/homestay/{homestay}/ketersediaan', [HomestayController::class, 'availability'])
    ->middleware('throttle:60,1')
    ->name('homestay.availability');
Route::get('/homestay/{homestay}', [HomestayController::class, 'show'])->name('homestay.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::post('/login/whatsapp', [LoginController::class, 'loginViaWhatsApp'])->name('login.whatsapp');
    Route::get('/login/social/{provider}', [LoginController::class, 'loginViaSocial'])->name('login.social');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/register/verify-otp', [RegisterController::class, 'showOtpForm'])->name('register.otp');
    Route::post('/register/verify-otp', [RegisterController::class, 'verifyOtp'])->name('register.verify-otp');
    Route::post('/register/resend-otp', [RegisterController::class, 'resendOtp'])->name('register.resend-otp');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/pembayaran/{pembayaran}/bukti', [PembayaranProofController::class, 'show'])
    ->middleware(['auth', 'active'])
    ->name('pembayaran.bukti');

Route::middleware(['auth', 'active', 'role:wisatawan'])
    ->prefix('wisatawan')
    ->name('wisatawan.')
    ->group(function () {
        Route::get('/dashboard', [WisatawanDashboardController::class, 'index'])->name('dashboard');

        Route::get('/kamar/{kamar}/booking', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
        Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
        Route::patch('/booking/{booking}/batal', [BookingController::class, 'cancel'])->name('booking.cancel');
        Route::post('/booking/{booking}/pembayaran', [PembayaranController::class, 'store'])->name('booking.pembayaran');

        Route::get('/booking/{booking}/ulasan', [UlasanController::class, 'create'])->name('ulasan.create');
        Route::post('/booking/{booking}/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    });

Route::middleware(['auth', 'active', 'role:pemilik'])
    ->prefix('pemilik')
    ->name('pemilik.')
    ->group(function () {
        Route::get('/dashboard', [PemilikDashboardController::class, 'index'])->name('dashboard');

        Route::get('/homestay', [PemilikHomestayController::class, 'index'])->name('homestay.index');
        Route::get('/homestay/create', [PemilikHomestayController::class, 'create'])->name('homestay.create');
        Route::post('/homestay', [PemilikHomestayController::class, 'store'])->name('homestay.store');
        Route::get('/homestay/{homestay}/edit', [PemilikHomestayController::class, 'edit'])->name('homestay.edit');
        Route::patch('/homestay/{homestay}', [PemilikHomestayController::class, 'update'])->name('homestay.update');

        Route::get('/homestay/{homestay}/kamar', [PemilikKamarController::class, 'index'])->name('homestay.kamar.index');
        Route::post('/homestay/{homestay}/kamar', [PemilikKamarController::class, 'store'])->name('homestay.kamar.store');
        Route::get('/kamar/{kamar}/edit', [PemilikKamarController::class, 'edit'])->name('kamar.edit');
        Route::patch('/kamar/{kamar}', [PemilikKamarController::class, 'update'])->name('kamar.update');
        Route::delete('/kamar/{kamar}', [PemilikKamarController::class, 'destroy'])->name('kamar.destroy');

        Route::get('/homestay/{homestay}/paket', [PemilikPaketWisataController::class, 'index'])->name('homestay.paket.index');
        Route::post('/homestay/{homestay}/paket', [PemilikPaketWisataController::class, 'store'])->name('homestay.paket.store');
        Route::get('/paket/{paket}/edit', [PemilikPaketWisataController::class, 'edit'])->name('paket.edit');
        Route::patch('/paket/{paket}', [PemilikPaketWisataController::class, 'update'])->name('paket.update');
        Route::delete('/paket/{paket}', [PemilikPaketWisataController::class, 'destroy'])->name('paket.destroy');

        Route::get('/booking', [PemilikBookingController::class, 'index'])->name('booking.index');
        Route::get('/booking/{booking}', [PemilikBookingController::class, 'show'])->name('booking.show');
        Route::patch('/booking/{booking}/konfirmasi', [PemilikBookingController::class, 'confirm'])->name('booking.confirm');
        Route::patch('/booking/{booking}/tolak', [PemilikBookingController::class, 'reject'])->name('booking.reject');
        Route::patch('/booking/{booking}/selesai', [PemilikBookingController::class, 'complete'])->name('booking.complete');

        Route::get('/pendapatan', [PemilikPendapatanController::class, 'index'])->name('pendapatan');
    });

Route::middleware(['auth', 'active', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/status', [AdminUserController::class, 'toggleStatus'])->name('users.status');

        Route::get('/homestay', [AdminHomestayVerificationController::class, 'index'])->name('homestay.index');
        Route::get('/homestay/create', [AdminHomestayVerificationController::class, 'create'])->name('homestay.create');
        Route::post('/homestay', [AdminHomestayVerificationController::class, 'store'])->name('homestay.store');
        Route::get('/homestay/{homestay}', [AdminHomestayVerificationController::class, 'show'])->name('homestay.show');
        Route::get('/homestay/{homestay}/edit', [AdminHomestayVerificationController::class, 'edit'])->name('homestay.edit');
        Route::put('/homestay/{homestay}', [AdminHomestayVerificationController::class, 'update'])->name('homestay.update');
        Route::delete('/homestay/{homestay}', [AdminHomestayVerificationController::class, 'destroy'])->name('homestay.destroy');
        Route::patch('/homestay/{homestay}/setujui', [AdminHomestayVerificationController::class, 'approve'])->name('homestay.approve');
        Route::patch('/homestay/{homestay}/tolak', [AdminHomestayVerificationController::class, 'reject'])->name('homestay.reject');

        Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/pembayaran/{pembayaran}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');
        Route::patch('/pembayaran/{pembayaran}/verifikasi', [AdminPembayaranController::class, 'verify'])->name('pembayaran.verify');
        Route::patch('/pembayaran/{pembayaran}/tolak', [AdminPembayaranController::class, 'reject'])->name('pembayaran.reject');

        Route::get('/fasilitas', [AdminFasilitasController::class, 'index'])->name('fasilitas.index');
        Route::post('/fasilitas', [AdminFasilitasController::class, 'store'])->name('fasilitas.store');
        Route::patch('/fasilitas/{fasilitas}', [AdminFasilitasController::class, 'update'])->name('fasilitas.update');
        Route::delete('/fasilitas/{fasilitas}', [AdminFasilitasController::class, 'destroy'])->name('fasilitas.destroy');

        Route::get('/laporan/booking', [AdminLaporanController::class, 'booking'])->name('laporan.booking');
        Route::get('/laporan/pendapatan', [AdminLaporanController::class, 'pendapatan'])->name('laporan.pendapatan');
    });
