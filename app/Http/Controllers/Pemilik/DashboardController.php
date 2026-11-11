<?php

namespace App\Http\Controllers\Pemilik;

use App\Enums\StatusBooking;
use App\Enums\StatusVerifikasi;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Homestay;
use App\Models\Kamar;
use App\Models\Ulasan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $pemilik = $request->user()->pemilikHomestay()->firstOrFail();

        // Ambil homestay milik pemilik ini lengkap dengan relasi dan jumlah
        $homestays = Homestay::where('id_pemilik', $pemilik->id_pemilik)
            ->withCount(['kamar', 'paket'])
            ->with(['fasilitas'])
            ->get();

        $homestayIds = $homestays->pluck('id_homestay');

        // Query dasar booking yang masuk ke homestay milik pemilik
        $bookingQuery = Booking::whereHas('kamar', fn ($query) => $query->whereIn('id_homestay', $homestayIds));

        $totalKamar = Kamar::whereIn('id_homestay', $homestayIds)->count();

        $today = Carbon::today()->toDateString();

        // Statistik lengkap untuk dashboard pemilik
        $stats = [
            'homestay' => $homestayIds->count(),
            'homestay_disetujui' => $homestays->where('status_verifikasi', StatusVerifikasi::Disetujui)->count(),
            'homestay_menunggu' => $homestays->where('status_verifikasi', StatusVerifikasi::Menunggu)->count(),
            'total_kamar' => $totalKamar,
            'perlu_konfirmasi' => (clone $bookingQuery)
                ->where('booking.status', StatusBooking::MenungguVerifikasi->value)
                ->count(),
            'dikonfirmasi' => (clone $bookingQuery)
                ->where('booking.status', StatusBooking::Dikonfirmasi->value)
                ->count(),
            'selesai' => (clone $bookingQuery)
                ->where('booking.status', StatusBooking::Selesai->value)
                ->count(),
            'pendapatan' => (clone $bookingQuery)
                ->whereIn('booking.status', [StatusBooking::Dikonfirmasi->value, StatusBooking::Selesai->value])
                ->sum('booking.total_harga'),
            'tamu_aktif' => (clone $bookingQuery)
                ->where('booking.status', StatusBooking::Dikonfirmasi->value)
                ->whereDate('tgl_checkin', '<=', $today)
                ->whereDate('tgl_checkout', '>=', $today)
                ->count(),
        ];

        // 5 Pesanan terbaru
        $bookings = (clone $bookingQuery)
            ->with(['kamar.homestay', 'wisatawan.user', 'pembayaran'])
            ->orderByDesc('id_booking')
            ->limit(5)
            ->get();

        // Jadwal kedatangan tamu terdekat (checkin >= hari ini, status dikonfirmasi)
        $upcomingGuests = (clone $bookingQuery)
            ->with(['kamar.homestay', 'wisatawan'])
            ->where('booking.status', StatusBooking::Dikonfirmasi->value)
            ->whereDate('tgl_checkin', '>=', $today)
            ->orderBy('tgl_checkin', 'asc')
            ->limit(4)
            ->get();

        // Ulasan dan kepuasan tamu terbaru
        $recentReviews = Ulasan::whereHas('booking.kamar', fn ($q) => $q->whereIn('id_homestay', $homestayIds))
            ->with(['booking.wisatawan', 'booking.kamar.homestay'])
            ->orderByDesc('id_ulasan')
            ->limit(3)
            ->get();

        return view('pemilik.dashboard', compact(
            'pemilik',
            'homestays',
            'stats',
            'bookings',
            'upcomingGuests',
            'recentReviews'
        ));
    }
}
