<?php

namespace App\Http\Controllers\Wisatawan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wisatawan\StoreUlasanRequest;
use App\Models\Booking;
use App\Models\Ulasan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UlasanController extends Controller
{
    public function create(Booking $booking): View
    {
        $this->authorize('review', $booking);

        $booking->load('kamar.homestay');

        return view('wisatawan.ulasan.create', compact('booking'));
    }

    public function store(StoreUlasanRequest $request, Booking $booking): RedirectResponse
    {
        $this->authorize('review', $booking);

        DB::transaction(function () use ($request, $booking) {
            $lockedBooking = Booking::query()->whereKey($booking->id_booking)->lockForUpdate()->firstOrFail();

            if ($lockedBooking->ulasan()->exists()) {
                throw ValidationException::withMessages([
                    'rating' => 'Pemesanan ini sudah pernah diulas.',
                ]);
            }

            Ulasan::create([
                'id_booking' => $lockedBooking->id_booking,
                'rating' => $request->validated('rating'),
                'komentar' => $request->validated('komentar'),
                'tgl_ulasan' => today()->toDateString(),
            ]);
        });

        return redirect()
            ->route('wisatawan.booking.show', $booking)
            ->with('success', 'Terima kasih! Ulasan Anda sudah dipublikasikan.');
    }
}
