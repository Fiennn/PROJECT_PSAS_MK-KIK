<?php

namespace App\Http\Controllers\Pemilik;

use App\Enums\StatusBooking;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PendapatanController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
        ]);

        $pemilik = $request->user()->pemilikHomestay()->firstOrFail();
        $homestays = Homestay::where('id_pemilik', $pemilik->id_pemilik)
            ->orderBy('nama_homestay')
            ->get();

        $dari = isset($filters['dari'])
            ? Carbon::parse($filters['dari'])->startOfDay()
            : now()->startOfMonth();

        $sampai = isset($filters['sampai'])
            ? Carbon::parse($filters['sampai'])->endOfDay()
            : now()->endOfMonth();

        $baseQuery = Booking::query()
            ->whereHas('kamar', fn ($query) => $query->whereIn('id_homestay', $homestays->pluck('id_homestay')))
            ->whereIn('status', [StatusBooking::Dikonfirmasi->value, StatusBooking::Selesai->value])
            ->whereBetween('tgl_checkin', [$dari->toDateString(), $sampai->toDateString()]);

        $totals = [
            'pendapatan' => (clone $baseQuery)->sum('total_harga'),
            'booking' => (clone $baseQuery)->count(),
            'malam' => (clone $baseQuery)->get()->sum(fn (Booking $booking) => $booking->jumlah_malam),
        ];

        $perHomestay = (clone $baseQuery)
            ->with('kamar.homestay')
            ->get()
            ->groupBy(fn (Booking $booking) => $booking->kamar->homestay->id_homestay)
            ->map(function ($bookings) {
                return [
                    'homestay' => $bookings->first()->kamar->homestay->nama_homestay,
                    'booking' => $bookings->count(),
                    'pendapatan' => $bookings->sum('total_harga'),
                ];
            })
            ->sortByDesc('pendapatan');

        $bookings = (clone $baseQuery)
            ->with(['kamar.homestay', 'wisatawan'])
            ->orderByDesc('tgl_checkin')
            ->paginate(12)
            ->withQueryString();

        return view('pemilik.pendapatan', compact(
            'homestays',
            'filters',
            'dari',
            'sampai',
            'totals',
            'perHomestay',
            'bookings'
        ));
    }
}
