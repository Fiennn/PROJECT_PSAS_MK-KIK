<?php

namespace App\Http\Controllers\Pemilik;

use App\Enums\StatusBooking;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Homestay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $pemilik = $request->user()->pemilikHomestay()->firstOrFail();
        $homestayIds = Homestay::where('id_pemilik', $pemilik->id_pemilik)->pluck('id_homestay');

        $status = $request->query('status');
        $allowedStatuses = array_column(StatusBooking::cases(), 'value');

        $bookings = Booking::query()
            ->whereHas('kamar', fn ($query) => $query->whereIn('id_homestay', $homestayIds))
            ->when(
                $status && in_array($status, $allowedStatuses, true),
                fn ($query) => $query->where('status', $status)
            )
            ->with(['kamar.homestay', 'wisatawan', 'pembayaran', 'paket'])
            ->orderByDesc('id_booking')
            ->paginate(10)
            ->withQueryString();

        return view('pemilik.booking.index', compact('bookings', 'status'));
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load([
            'kamar.homestay.pemilik',
            'wisatawan',
            'paket',
            'pembayaran' => fn ($query) => $query->orderByDesc('id_pembayaran'),
            'ulasan',
        ]);

        return view('pemilik.booking.show', compact('booking'));
    }

    public function confirm(Booking $booking): RedirectResponse
    {
        $this->authorize('confirm', $booking);

        $booking->update(['status' => StatusBooking::Dikonfirmasi->value]);

        return redirect()
            ->route('pemilik.booking.show', $booking)
            ->with('success', 'Pesanan dikonfirmasi. Tamu akan datang sesuai tanggal check-in.');
    }

    public function reject(Booking $booking): RedirectResponse
    {
        $this->authorize('reject', $booking);

        $booking->update(['status' => StatusBooking::Ditolak->value]);

        return redirect()
            ->route('pemilik.booking.show', $booking)
            ->with('success', 'Pesanan ditolak. Kamar kembali tersedia untuk tamu lain.');
    }

    public function complete(Booking $booking): RedirectResponse
    {
        $this->authorize('complete', $booking);

        $booking->update(['status' => StatusBooking::Selesai->value]);

        return redirect()
            ->route('pemilik.booking.show', $booking)
            ->with('success', 'Pesanan ditandai selesai. Tamu sekarang dapat menulis ulasan.');
    }
}
