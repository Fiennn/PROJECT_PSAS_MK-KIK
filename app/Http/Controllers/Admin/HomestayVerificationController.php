<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusVerifikasi;
use App\Http\Controllers\Controller;
use App\Models\Homestay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HomestayVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(array_column(StatusVerifikasi::cases(), 'value'))],
        ]);

        $homestays = Homestay::query()
            ->with(['pemilik', 'fasilitas'])
            ->withCount(['kamar', 'paket'])
            ->when($filters['q'] ?? null, function ($query, $keyword) {
                $query->where(function ($builder) use ($keyword) {
                    $builder->where('nama_homestay', 'like', '%'.$keyword.'%')
                        ->orWhere('alamat', 'like', '%'.$keyword.'%')
                        ->orWhereHas('pemilik', fn ($relation) => $relation->where('nama_pemilik', 'like', '%'.$keyword.'%'));
                });
            })
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status_verifikasi', $status))
            ->orderByRaw("FIELD(status_verifikasi, 'menunggu', 'ditolak', 'disetujui')")
            ->orderByDesc('id_homestay')
            ->paginate(10)
            ->withQueryString();

        return view('admin.homestay.index', compact('homestays', 'filters'));
    }

    public function show(Homestay $homestay): View
    {
        $homestay->load(['pemilik.user', 'fasilitas', 'kamar', 'paket']);

        return view('admin.homestay.show', compact('homestay'));
    }

    public function approve(Homestay $homestay): RedirectResponse
    {
        $homestay->update(['status_verifikasi' => StatusVerifikasi::Disetujui->value]);

        return redirect()
            ->route('admin.homestay.show', $homestay)
            ->with('success', 'Homestay '.$homestay->nama_homestay.' berhasil disetujui dan tampil di katalog.');
    }

    public function reject(Homestay $homestay): RedirectResponse
    {
        $homestay->update(['status_verifikasi' => StatusVerifikasi::Ditolak->value]);

        return redirect()
            ->route('admin.homestay.show', $homestay)
            ->with('success', 'Homestay '.$homestay->nama_homestay.' ditolak. Pemilik dapat memperbaiki data dan mengajukan ulang.');
    }
}
