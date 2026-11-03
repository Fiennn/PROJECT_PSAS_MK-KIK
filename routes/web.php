<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboardController;
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
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
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
    });

Route::middleware(['auth', 'active', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });
