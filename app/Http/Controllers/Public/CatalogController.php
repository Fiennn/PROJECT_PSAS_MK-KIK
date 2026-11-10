<?php

namespace App\Http\Controllers\Public;

use App\Enums\StatusKamar;
use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kota' => ['nullable', 'string', 'max:100'],
            'harga_max' => ['nullable', 'numeric', 'min:0'],
            'kapasitas' => ['nullable', 'integer', 'min:1', 'max:20'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['integer', 'exists:fasilitas,id_fasilitas'],
        ]);

        $selectedFacilities = array_map('intval', $filters['fasilitas'] ?? []);

        $query = Homestay::query()
            ->disetujui()
            ->with([
                'kamar' => fn ($builder) => $builder->where('status', StatusKamar::Tersedia->value),
                'fasilitas',
            ])
            ->withMin(
                ['kamar as harga_min' => fn ($builder) => $builder->where('status', StatusKamar::Tersedia->value)],
                'harga_per_malam'
            )
            ->withCount([
                'kamar as kamar_tersedia_count' => fn ($builder) => $builder->where('status', StatusKamar::Tersedia->value),
            ])
            ->withRatingStats();

        if (! empty($filters['q'])) {
            $searchTerm = $filters['q'];
            $query->where(function ($sub) use ($searchTerm) {
                $sub->where('nama_homestay', 'like', '%'.$searchTerm.'%')
                    ->orWhere('alamat', 'like', '%'.$searchTerm.'%');
            });
        }

        if (! empty($filters['kota'])) {
            $query->where('alamat', 'like', '%'.$filters['kota'].'%');
        }

        if (isset($filters['harga_max'])) {
            $query->whereHas('kamar', function ($builder) use ($filters) {
                $builder->where('status', StatusKamar::Tersedia->value)
                    ->where('harga_per_malam', '<=', $filters['harga_max']);
            });
        }

        if (isset($filters['kapasitas'])) {
            $query->whereHas('kamar', function ($builder) use ($filters) {
                $builder->where('status', StatusKamar::Tersedia->value)
                    ->where('kapasitas', '>=', $filters['kapasitas']);
            });
        }

        foreach ($selectedFacilities as $facilityId) {
            $query->whereHas('fasilitas', fn ($builder) => $builder->where('fasilitas.id_fasilitas', $facilityId));
        }

        $homestays = $query
            ->orderByDesc('ulasan_avg_rating')
            ->orderBy('nama_homestay')
            ->paginate(9)
            ->withQueryString();

        $facilities = Fasilitas::query()->orderBy('nama_fasilitas')->get();

        return view('public.catalog', compact('homestays', 'facilities', 'filters', 'selectedFacilities'));
    }
}
