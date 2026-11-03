<?php

namespace App\Http\Controllers\Pemilik;

use App\Enums\StatusVerifikasi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pemilik\StoreHomestayRequest;
use App\Models\Fasilitas;
use App\Models\Homestay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomestayController extends Controller
{
    public function index(Request $request): View
    {
        $pemilik = $request->user()->pemilikHomestay()->firstOrFail();

        $homestays = $pemilik->homestay()
            ->with('fasilitas')
            ->withCount(['kamar', 'paket'])
            ->orderByDesc('id_homestay')
            ->paginate(9);

        return view('pemilik.homestay.index', compact('homestays'));
    }

    public function create(): View
    {
        $fasilitas = Fasilitas::orderBy('nama_fasilitas')->get();

        return view('pemilik.homestay.create', compact('fasilitas'));
    }

    public function store(StoreHomestayRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $pemilik = $request->user()->pemilikHomestay()->firstOrFail();

        $homestay = DB::transaction(function () use ($validated, $pemilik) {
            $homestay = Homestay::create([
                'id_pemilik' => $pemilik->id_pemilik,
                'nama_homestay' => $validated['nama_homestay'],
                'deskripsi' => $validated['deskripsi'],
                'alamat' => $validated['alamat'],
                'status_verifikasi' => StatusVerifikasi::Menunggu->value,
            ]);

            $homestay->fasilitas()->sync($validated['fasilitas'] ?? []);

            return $homestay;
        });

        return redirect()
            ->route('pemilik.homestay.index')
            ->with('success', 'Homestay "'.$homestay->nama_homestay.'" berhasil diajukan. Admin desa akan memverifikasinya.');
    }

    public function edit(Homestay $homestay): View
    {
        $this->authorize('update', $homestay);

        $homestay->load('fasilitas');
        $fasilitas = Fasilitas::orderBy('nama_fasilitas')->get();
        $selectedFacilities = $homestay->fasilitas->pluck('id_fasilitas')->all();

        return view('pemilik.homestay.edit', compact('homestay', 'fasilitas', 'selectedFacilities'));
    }

    public function update(StoreHomestayRequest $request, Homestay $homestay): RedirectResponse
    {
        $this->authorize('update', $homestay);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $homestay) {
            $data = [
                'nama_homestay' => $validated['nama_homestay'],
                'deskripsi' => $validated['deskripsi'],
                'alamat' => $validated['alamat'],
            ];

            if ($homestay->status_verifikasi === StatusVerifikasi::Ditolak) {
                $data['status_verifikasi'] = StatusVerifikasi::Menunggu->value;
            }

            $homestay->update($data);
            $homestay->fasilitas()->sync($validated['fasilitas'] ?? []);
        });

        return redirect()
            ->route('pemilik.homestay.index')
            ->with('success', 'Data homestay berhasil diperbarui.');
    }
}
