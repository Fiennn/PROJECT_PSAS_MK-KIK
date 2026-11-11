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
        $q = $request->query('q');
        $status = $request->query('status');

        $query = $pemilik->homestay()
            ->with('fasilitas')
            ->withCount(['kamar', 'paket'])
            ->when($q, function ($builder, $q) {
                $builder->where(function ($sub) use ($q) {
                    $sub->where('nama_homestay', 'like', "%{$q}%")
                        ->orWhere('alamat', 'like', "%{$q}%");
                });
            })
            ->when($status, function ($builder, $status) {
                $builder->where('status_verifikasi', $status);
            })
            ->orderByDesc('id_homestay');

        $homestays = $query->paginate(9)->withQueryString();

        $allHomestays = $pemilik->homestay()->withCount('kamar')->get();
        $stats = [
            'total' => $allHomestays->count(),
            'disetujui' => $allHomestays->where('status_verifikasi', StatusVerifikasi::Disetujui)->count(),
            'menunggu' => $allHomestays->where('status_verifikasi', StatusVerifikasi::Menunggu)->count(),
            'ditolak' => $allHomestays->where('status_verifikasi', StatusVerifikasi::Ditolak)->count(),
            'total_kamar' => $allHomestays->sum('kamar_count'),
        ];

        return view('pemilik.homestay.index', compact('homestays', 'stats', 'q', 'status'));
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
            ->with('success', 'Homestay "'.$homestay->nama_homestay.'" berhasil diajukan. Admin Pokdarwis akan segera memverifikasinya.');
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
            ->with('success', 'Data homestay "'.$homestay->nama_homestay.'" berhasil diperbarui.');
    }

    public function destroy(Homestay $homestay): RedirectResponse
    {
        $this->authorize('delete', $homestay);

        if ($homestay->booking()->exists()) {
            return back()->with('error', 'Homestay tidak dapat dihapus karena memiliki riwayat pemesanan atau keuangan.');
        }

        DB::transaction(function () use ($homestay) {
            $homestay->fasilitas()->detach();
            $homestay->paket()->delete();
            $homestay->kamar()->delete();
            $homestay->delete();
        });

        return redirect()
            ->route('pemilik.homestay.index')
            ->with('success', 'Homestay "'.$homestay->nama_homestay.'" berhasil dihapus.');
    }
}
