<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use App\Enums\StatusVerifikasi;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Homestay;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'pengguna' => User::count(),
            'total_wisatawan' => User::where('role', 'wisatawan')->count(),
            'total_pemilik' => User::where('role', 'pemilik')->count(),
            'total_homestay' => Homestay::count(),
            'homestay_disetujui' => Homestay::where('status_verifikasi', StatusVerifikasi::Disetujui->value)->count(),
            'homestay_menunggu' => Homestay::where('status_verifikasi', StatusVerifikasi::Menunggu->value)->count(),
            'pembayaran_menunggu' => Pembayaran::where('status_verifikasi', StatusPembayaran::Menunggu->value)->count(),
            'booking_bulan_ini' => Booking::whereBetween('tgl_booking', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'pendapatan_terverifikasi' => Pembayaran::where('status_verifikasi', StatusPembayaran::Terverifikasi->value)->sum('jumlah'),
        ];

        $pendingHomestays = Homestay::with('pemilik')
            ->where('status_verifikasi', StatusVerifikasi::Menunggu->value)
            ->orderByDesc('id_homestay')
            ->limit(5)
            ->get();

        $pendingPayments = Pembayaran::with(['booking.wisatawan', 'booking.kamar.homestay'])
            ->where('status_verifikasi', StatusPembayaran::Menunggu->value)
            ->orderByDesc('id_pembayaran')
            ->limit(5)
            ->get();

        $recentBookings = Booking::with(['wisatawan', 'kamar.homestay', 'pembayaran'])
            ->orderByDesc('id_booking')
            ->limit(5)
            ->get();

        $activeBookings = Booking::whereIn('status', StatusBooking::activeValues())->count();

        return view('admin.dashboard', compact(
            'stats',
            'pendingHomestays',
            'pendingPayments',
            'recentBookings',
            'activeBookings'
        ));
    }
}
