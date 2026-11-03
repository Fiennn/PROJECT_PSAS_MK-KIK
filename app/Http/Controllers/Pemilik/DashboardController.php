<?php

namespace App\Http\Controllers\Pemilik;

use App\Enums\StatusBooking;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $pemilik = $request->user()->pemilikHomestay()->firstOrFail();
        $homestayIds = Homestay::where('id_pemilik', $pemilik->id_pemilik)->pluck('id_homestay');

        $bookingQuery = Booking::whereHas('kamar', fn ($query) => $query->whereIn('id_homestay', $homestayIds));

        $stats = [
            'homestay' => $homestayIds->count(),
            'perlu_konfirmasi' => (clone $bookingQuery)
                ->where('status', StatusBooking::MenungguVerifikasi->value)
                ->count(),
            'dikonfirmasi' => (clone $bookingQuery)
                ->where('status', StatusBooking::Dikonfirmasi->value)
                ->count(),
            'pendapatan' => (clone $bookingQuery)
                ->whereIn('status', [StatusBooking::Dikonfirmasi->value, StatusBooking::Selesai->value])
                ->sum('total_harga'),
        ];

        $bookings = (clone $bookingQuery)
            ->with(['kamar.homestay', 'wisatawan', 'pembayaran'])
            ->orderByDesc('id_booking')
            ->limit(5)
            ->get();

        return view('pemilik.dashboard', compact('stats', 'bookings'));
    }
}
