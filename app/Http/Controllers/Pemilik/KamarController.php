<?php

namespace App\Http\Controllers\Pemilik;

use App\Enums\StatusKamar;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pemilik\StoreKamarRequest;
use App\Models\Homestay;
use App\Models\Kamar;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KamarController extends Controller
{
    public function index(Homestay $homestay): View
    {
        $this->authorize('manageRooms', $homestay);

        $homestay->load('fasilitas');
        $kamar = $homestay->kamar()->orderBy('harga_per_malam')->get();

        return view('pemilik.kamar.index', compact('homestay', 'kamar'));
    }

    public function store(StoreKamarRequest $request, Homestay $homestay): RedirectResponse
    {
        $this->authorize('manageRooms', $homestay);

        $homestay->kamar()->create($request->validated());

        return redirect()
            ->route('pemilik.homestay.kamar.index', $homestay)
            ->with('success', 'Kamar baru berhasil ditambahkan.');
    }

    public function edit(Kamar $kamar): View
    {
        $this->authorize('update', $kamar);

        $kamar->load('homestay');

        return view('pemilik.kamar.edit', compact('kamar'));
    }

    public function update(StoreKamarRequest $request, Kamar $kamar): RedirectResponse
    {
        $this->authorize('update', $kamar);

        $kamar->update($request->validated());

        return redirect()
            ->route('pemilik.homestay.kamar.index', $kamar->homestay)
            ->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar): RedirectResponse
    {
        $this->authorize('delete', $kamar);

        $homestay = $kamar->homestay;
        $kamar->delete();

        return redirect()
            ->route('pemilik.homestay.kamar.index', $homestay)
            ->with('success', 'Kamar berhasil dihapus.');
    }
}
