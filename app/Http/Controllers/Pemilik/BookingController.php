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
        $ownerHomestays = Homestay::where('id_pemilik', $pemilik->id_pemilik)->orderBy('nama_homestay')->get();
        $homestayIds = $ownerHomestays->pluck('id_homestay');

        $status = $request->query('status');
        $q = $request->query('q');
        $homestayFilter = $request->query('homestay');
        $allowedStatuses = array_column(StatusBooking::cases(), 'value');

        $bookings = Booking::query()
            ->whereHas('kamar', fn ($query) => $query->whereIn('id_homestay', $homestayIds))
            ->when(
                $status && in_array($status, $allowedStatuses, true),
                fn ($query) => $query->where('booking.status', $status)
            )
            ->when($homestayFilter, function ($query, $hId) {
                $query->whereHas('kamar', fn ($k) => $k->where('id_homestay', $hId));
            })
            ->when($q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('booking.id_booking', 'like', "%{$q}%")
                        ->orWhereHas('wisatawan', fn ($w) => $w->where('nama_wisatawan', 'like', "%{$q}%")->orWhere('no_hp', 'like', "%{$q}%"))
                        ->orWhereHas('kamar', fn ($k) => $k->where('nama_kamar', 'like', "%{$q}%"));
                });
            })
            ->with(['kamar.homestay', 'wisatawan.user', 'pembayaran', 'paket'])
            ->orderByDesc('id_booking')
            ->paginate(10)
            ->withQueryString();

        $allBookings = Booking::whereHas('kamar', fn ($query) => $query->whereIn('id_homestay', $homestayIds))->get();
        $counts = [
            'total' => $allBookings->count(),
            'perlu_konfirmasi' => $allBookings->where('status', StatusBooking::MenungguVerifikasi)->count(),
            'dikonfirmasi' => $allBookings->where('status', StatusBooking::Dikonfirmasi)->count(),
            'selesai' => $allBookings->where('status', StatusBooking::Selesai)->count(),
        ];

        return view('pemilik.booking.index', compact(
            'bookings',
            'status',
            'q',
            'homestayFilter',
            'counts',
            'ownerHomestays'
        ));
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load([
            'kamar.homestay.pemilik',
            'wisatawan.user',
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
            ->with('success', 'Pesanan #'.$booking->id_booking.' berhasil dikonfirmasi. Kamar siap menyambut tamu pada tanggal check-in.');
    }

    public function reject(Booking $booking): RedirectResponse
    {
        $this->authorize('reject', $booking);

        $booking->update(['status' => StatusBooking::Ditolak->value]);

        return redirect()
            ->route('pemilik.booking.show', $booking)
            ->with('success', 'Pesanan #'.$booking->id_booking.' ditolak. Kamar kembali tersedia untuk wisatawan lain.');
    }

    public function complete(Booking $booking): RedirectResponse
    {
        $this->authorize('complete', $booking);

        $booking->update(['status' => StatusBooking::Selesai->value]);

        return redirect()
            ->route('pemilik.booking.show', $booking)
            ->with('success', 'Pesanan #'.$booking->id_booking.' ditandai selesai. Terima kasih telah menyambut tamu dengan keramahan desa.');
    }
}
