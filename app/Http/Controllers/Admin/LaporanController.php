<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusBooking;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function booking(Request $request): View
    {
        $filters = $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'status' => ['nullable', Rule::in(array_column(StatusBooking::cases(), 'value'))],
        ]);

        [$dari, $sampai] = $this->period($filters);

        $query = Booking::query()
            ->with(['wisatawan', 'kamar.homestay', 'pembayaran'])
            ->whereBetween('tgl_booking', [$dari->toDateString(), $sampai->toDateString()])
            ->when($filters['status'] ?? null, fn ($builder, $status) => $builder->where('status', $status))
            ->orderBy('tgl_booking')
            ->orderBy('id_booking');

        $bookings = $query->get();

        $statusCounts = $bookings->countBy(fn (Booking $booking) => $booking->status->value);

        return view('admin.laporan.booking', compact('bookings', 'statusCounts', 'dari', 'sampai', 'filters'));
    }

    public function pendapatan(Request $request): View
    {
        $filters = $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
        ]);

        [$dari, $sampai] = $this->period($filters);

        $bookings = Booking::query()
            ->with(['wisatawan', 'kamar.homestay'])
            ->whereIn('status', [StatusBooking::Dikonfirmasi->value, StatusBooking::Selesai->value])
            ->whereBetween('tgl_checkin', [$dari->toDateString(), $sampai->toDateString()])
            ->orderBy('tgl_checkin')
            ->get();

        $perHomestay = $bookings
            ->groupBy(fn (Booking $booking) => $booking->kamar->homestay->id_homestay)
            ->map(fn ($items) => [
                'homestay' => $items->first()->kamar->homestay->nama_homestay,
                'booking' => $items->count(),
                'pendapatan' => $items->sum('total_harga'),
            ])
            ->sortByDesc('pendapatan');

        $total = $bookings->sum('total_harga');

        return view('admin.laporan.pendapatan', compact('bookings', 'perHomestay', 'total', 'dari', 'sampai'));
    }

    /**
     * @param  array{dari?: string|null, sampai?: string|null}  $filters
     * @return array{0: Carbon, 1: Carbon}
     */
    private function period(array $filters): array
    {
        $dari = isset($filters['dari'])
            ? Carbon::parse($filters['dari'])->startOfDay()
            : now()->startOfMonth();

        $sampai = isset($filters['sampai'])
            ? Carbon::parse($filters['sampai'])->endOfDay()
            : now()->endOfMonth();

        return [$dari, $sampai];
    }
}
