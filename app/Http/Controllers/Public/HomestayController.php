<?php

namespace App\Http\Controllers\Public;

use App\Enums\StatusBooking;
use App\Enums\StatusKamar;
use App\Enums\StatusVerifikasi;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Homestay;
use App\Models\Ulasan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class HomestayController extends Controller
{
    public function show(Homestay $homestay): View
    {
        abort_unless($homestay->status_verifikasi === StatusVerifikasi::Disetujui, 404);

        $homestay->load([
            'pemilik',
            'fasilitas',
            'kamar' => fn ($query) => $query->where('status', StatusKamar::Tersedia->value),
            'paket',
        ]);

        $reviewQuery = Ulasan::query()
            ->whereHas('booking.kamar', fn ($query) => $query->where('id_homestay', $homestay->id_homestay));

        $averageRating = round((float) (clone $reviewQuery)->avg('rating'), 1);
        $reviewCount = (clone $reviewQuery)->count();

        $reviews = (clone $reviewQuery)
            ->with('booking.wisatawan')
            ->orderByDesc('tgl_ulasan')
            ->paginate(5)
            ->withQueryString();

        return view('public.homestay-show', compact('homestay', 'averageRating', 'reviewCount', 'reviews'));
    }

    public function availability(Request $request, Homestay $homestay): JsonResponse
    {
        abort_unless($homestay->status_verifikasi === StatusVerifikasi::Disetujui, 404);

        try {
            $validated = $request->validate([
                'checkin' => ['required', 'date', 'after_or_equal:today'],
                'checkout' => ['required', 'date', 'after:checkin'],
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Tanggal yang dipilih belum valid.',
                'errors' => $exception->errors(),
            ], 422);
        }

        $checkin = $validated['checkin'];
        $checkout = $validated['checkout'];
        $nights = \Illuminate\Support\Carbon::parse($checkin)->diffInDays(\Illuminate\Support\Carbon::parse($checkout));

        $rooms = $homestay->kamar()
            ->where('status', StatusKamar::Tersedia->value)
            ->orderBy('harga_per_malam')
            ->get();

        $bookedRoomIds = Booking::query()
            ->whereIn('id_kamar', $rooms->pluck('id_kamar'))
            ->whereIn('status', StatusBooking::activeValues())
            ->overlapping($checkin, $checkout)
            ->pluck('id_kamar')
            ->unique();

        return response()->json([
            'checkin' => $checkin,
            'checkout' => $checkout,
            'nights' => $nights,
            'rooms' => $rooms->map(fn ($room) => [
                'id_kamar' => $room->id_kamar,
                'nama_kamar' => $room->nama_kamar,
                'kapasitas' => $room->kapasitas,
                'harga_per_malam' => (float) $room->harga_per_malam,
                'tersedia' => ! $bookedRoomIds->contains($room->id_kamar),
                'total' => (float) $room->harga_per_malam * $nights,
            ])->values(),
        ]);
    }
}
