<?php

namespace App\Http\Controllers\Wisatawan;

use App\Enums\StatusBooking;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $wisatawan = $request->user()->wisatawan()->firstOrFail();

        $baseQuery = Booking::where('id_wisatawan', $wisatawan->id_wisatawan);

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'upcoming' => (clone $baseQuery)
                ->where('status', StatusBooking::Dikonfirmasi->value)
                ->whereDate('tgl_checkin', '>=', today())
                ->count(),
            'selesai' => (clone $baseQuery)->where('status', StatusBooking::Selesai->value)->count(),
            'pengeluaran' => (clone $baseQuery)
                ->whereIn('status', [StatusBooking::Dikonfirmasi->value, StatusBooking::Selesai->value])
                ->sum('total_harga'),
        ];

        $bookings = (clone $baseQuery)
            ->with(['kamar.homestay', 'pembayaran'])
            ->orderByDesc('id_booking')
            ->limit(5)
            ->get();

        return view('wisatawan.dashboard', compact('stats', 'bookings'));
    }
}
