<?php

namespace App\Http\Controllers\Public;

use App\Enums\StatusKamar;
use App\Http\Controllers\Controller;
use App\Models\Homestay;
use App\Models\Kamar;
use App\Models\Ulasan;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $featured = Homestay::query()
            ->disetujui()
            ->with([
                'kamar' => fn ($query) => $query->where('status', StatusKamar::Tersedia->value),
                'fasilitas',
            ])
            ->withMin(
                ['kamar as harga_min' => fn ($query) => $query->where('status', StatusKamar::Tersedia->value)],
                'harga_per_malam'
            )
            ->withRatingStats()
            ->orderByDesc('ulasan_avg_rating')
            ->orderBy('nama_homestay')
            ->limit(3)
            ->get();

        $stats = [
            'homestay' => Homestay::query()->disetujui()->count(),
            'kamar' => Kamar::query()
                ->where('status', StatusKamar::Tersedia->value)
                ->whereHas('homestay', fn ($query) => $query->where('status_verifikasi', 'disetujui'))
                ->count(),
            'ulasan' => Ulasan::query()->count(),
            'rating' => round((float) Ulasan::query()->avg('rating'), 1),
        ];

        $testimonials = Ulasan::query()
            ->with(['booking.wisatawan', 'booking.kamar.homestay'])
            ->where('rating', '>=', 4)
            ->orderByDesc('tgl_ulasan')
            ->limit(3)
            ->get();

        return view('public.landing', compact('featured', 'stats', 'testimonials'));
    }
}
