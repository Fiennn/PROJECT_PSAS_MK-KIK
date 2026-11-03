<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemilik\StorePaketWisataRequest;
use App\Models\Homestay;
use App\Models\PaketWisata;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaketWisataController extends Controller
{
    public function index(Homestay $homestay): View
    {
        $this->authorize('managePackages', $homestay);

        $paket = $homestay->paket()->orderBy('nama_paket')->get();

        return view('pemilik.paket.index', compact('homestay', 'paket'));
    }

    public function store(StorePaketWisataRequest $request, Homestay $homestay): RedirectResponse
    {
        $this->authorize('managePackages', $homestay);

        $homestay->paket()->create($request->validated());

        return redirect()
            ->route('pemilik.homestay.paket.index', $homestay)
            ->with('success', 'Paket wisata baru berhasil ditambahkan.');
    }

    public function edit(PaketWisata $paket): View
    {
        $this->authorize('update', $paket);

        $paket->load('homestay');

        return view('pemilik.paket.edit', compact('paket'));
    }

    public function update(StorePaketWisataRequest $request, PaketWisata $paket): RedirectResponse
    {
        $this->authorize('update', $paket);

        $paket->update($request->validated());

        return redirect()
            ->route('pemilik.homestay.paket.index', $paket->homestay)
            ->with('success', 'Paket wisata berhasil diperbarui.');
    }

    public function destroy(PaketWisata $paket): RedirectResponse
    {
        $this->authorize('delete', $paket);

        $homestay = $paket->homestay;
        $paket->delete();

        return redirect()
            ->route('pemilik.homestay.paket.index', $homestay)
            ->with('success', 'Paket wisata berhasil dihapus.');
    }
}
