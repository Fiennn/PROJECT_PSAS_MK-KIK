<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ArShelter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RadarController extends Controller
{
    /**
     * Presets for GPS simulation, ideal for classroom testing / desktop testing.
     */
    public const PRESETS = [
        'baturraden' => [
            'key' => 'baturraden',
            'label' => 'Kawasan Wisata Baturraden',
            'lat' => -7.3150000,
            'lng' => 109.2280000,
            'desc' => 'Kawasan Lereng Gunung Slamet & Desa Ketenger',
        ],
        'purwokerto' => [
            'key' => 'purwokerto',
            'label' => 'Alun-Alun Purwokerto (Pusat Kota)',
            'lat' => -7.4243000,
            'lng' => 109.2302000,
            'desc' => 'Area Perkotaan & Stasiun Purwokerto',
        ],
        'limpakuwus' => [
            'key' => 'limpakuwus',
            'label' => 'Hutan Pinus Limpakuwus',
            'lat' => -7.3070000,
            'lng' => 109.2508000,
            'desc' => 'Wisata Alam Pinus Kec. Sumbang',
        ],
        'cipendok' => [
            'key' => 'cipendok',
            'label' => 'Curug Cipendok & Menggala Ranch',
            'lat' => -7.3459000,
            'lng' => 109.1285000,
            'desc' => 'Perbukitan Karangtengah Kec. Cilongok',
        ],
        'banyumas' => [
            'key' => 'banyumas',
            'label' => 'Kota Lama Banyumas (Sudagaran)',
            'lat' => -7.5125000,
            'lng' => 109.2937000,
            'desc' => 'Kawasan Bersejarah Sudagaran Banyumas',
        ],
    ];

    public function index(): View
    {
        $shelters = ArShelter::query()
            ->orderBy('nama')
            ->get()
            ->map(function (ArShelter $s) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $s->no_hp ?: $s->no_telepon ?: '');
                if (str_starts_with($cleanPhone, '08')) {
                    $cleanPhone = '628'.substr($cleanPhone, 2);
                }

                return [
                    'id' => $s->id_shelter,
                    'nama' => $s->nama,
                    'kategori' => $s->kategori,
                    'kecamatan' => $s->kecamatan,
                    'desa' => $s->desa,
                    'alamat' => $s->alamat,
                    'lat' => (float) $s->latitude,
                    'lng' => (float) $s->longitude,
                    'no_hp' => $s->no_hp ?: $s->no_telepon,
                    'instagram' => $s->instagram,
                    'is_mitra' => $s->is_mitra_pokdarwis,
                    'maps_url' => 'https://www.google.com/maps/search/?api=1&query='.urlencode($s->nama.' '.$s->alamat),
                    'wa_url' => $cleanPhone ? 'https://wa.me/'.$cleanPhone : null,
                ];
            });

        $categories = $shelters->pluck('kategori')->unique()->values();
        $presets = array_values(self::PRESETS);

        return view('public.radar-ar', compact('shelters', 'categories', 'presets'));
    }

    public function api(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'kategori' => ['nullable', 'string'],
            'radius' => ['nullable', 'numeric', 'min:0.5', 'max:50'],
        ]);

        $userLat = (float) $validated['lat'];
        $userLng = (float) $validated['lng'];
        $radius = isset($validated['radius']) ? (float) $validated['radius'] : null;
        $category = $validated['kategori'] ?? null;

        $shelters = ArShelter::query()
            ->when($category && $category !== 'all', fn ($q) => $q->where('kategori', $category))
            ->get()
            ->map(function (ArShelter $s) use ($userLat, $userLng) {
                $distKm = $s->distanceTo($userLat, $userLng);
                $bearing = $s->bearingFrom($userLat, $userLng);

                return [
                    'id' => $s->id_shelter,
                    'nama' => $s->nama,
                    'kategori' => $s->kategori,
                    'kecamatan' => $s->kecamatan,
                    'desa' => $s->desa,
                    'alamat' => $s->alamat,
                    'lat' => (float) $s->latitude,
                    'lng' => (float) $s->longitude,
                    'no_hp' => $s->no_hp ?: $s->no_telepon,
                    'instagram' => $s->instagram,
                    'jarak_km' => $distKm,
                    'jarak_teks' => $distKm < 1 ? round($distKm * 1000).' m' : $distKm.' km',
                    'bearing' => $bearing,
                    'maps_url' => 'https://www.google.com/maps/search/?api=1&query='.urlencode($s->nama.' '.$s->alamat),
                ];
            })
            ->when($radius, fn ($collection) => $collection->filter(fn ($item) => $item['jarak_km'] <= $radius))
            ->sortBy('jarak_km')
            ->values();

        return response()->json([
            'status' => 'success',
            'user_location' => [
                'lat' => $userLat,
                'lng' => $userLng,
            ],
            'total' => $shelters->count(),
            'data' => $shelters,
        ]);
    }
}
