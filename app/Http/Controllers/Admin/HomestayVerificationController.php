<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusVerifikasi;
use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\Homestay;
use App\Models\PemilikHomestay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $stats = [
            'total' => Homestay::count(),
            'disetujui' => Homestay::where('status_verifikasi', StatusVerifikasi::Disetujui->value)->count(),
            'menunggu' => Homestay::where('status_verifikasi', StatusVerifikasi::Menunggu->value)->count(),
            'ditolak' => Homestay::where('status_verifikasi', StatusVerifikasi::Ditolak->value)->count(),
        ];

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

        return view('admin.homestay.index', compact('homestays', 'filters', 'stats'));
    }

    public function create(): View
    {
        $pemilikList = PemilikHomestay::with('user')->orderBy('nama_pemilik')->get();
        $fasilitas = Fasilitas::orderBy('nama_fasilitas')->get();

        return view('admin.homestay.create', compact('pemilikList', 'fasilitas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_pemilik' => ['required', 'exists:pemilik_homestay,id_pemilik'],
            'nama_homestay' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'status_verifikasi' => ['required', Rule::in(array_column(StatusVerifikasi::cases(), 'value'))],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['exists:fasilitas,id_fasilitas'],
        ], [
            'id_pemilik.required' => 'Pilih pemilik homestay warga.',
            'id_pemilik.exists' => 'Pemilik homestay yang dipilih tidak valid.',
            'nama_homestay.required' => 'Nama homestay wajib diisi.',
            'deskripsi.required' => 'Deskripsi homestay wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'status_verifikasi.required' => 'Status verifikasi wajib ditentukan.',
        ]);

        $homestay = DB::transaction(function () use ($validated) {
            $homestay = Homestay::create([
                'id_pemilik' => $validated['id_pemilik'],
                'nama_homestay' => $validated['nama_homestay'],
                'deskripsi' => $validated['deskripsi'],
                'alamat' => $validated['alamat'],
                'status_verifikasi' => $validated['status_verifikasi'],
            ]);

            if (! empty($validated['fasilitas'])) {
                $homestay->fasilitas()->sync($validated['fasilitas']);
            }

            return $homestay;
        });

        return redirect()
            ->route('admin.homestay.show', $homestay)
            ->with('success', 'Homestay "'.$homestay->nama_homestay.'" berhasil ditambahkan oleh Admin Pokdarwis.');
    }

    public function show(Homestay $homestay): View
    {
        $homestay->load(['pemilik.user', 'fasilitas', 'kamar', 'paket']);
        $bookingCount = $homestay->booking()->count();
        $totalPendapatan = $homestay->booking()->whereIn('booking.status', ['dikonfirmasi', 'selesai'])->sum('booking.total_harga');

        return view('admin.homestay.show', compact('homestay', 'bookingCount', 'totalPendapatan'));
    }

    public function edit(Homestay $homestay): View
    {
        $homestay->load(['pemilik', 'fasilitas']);
        $pemilikList = PemilikHomestay::with('user')->orderBy('nama_pemilik')->get();
        $fasilitas = Fasilitas::orderBy('nama_fasilitas')->get();
        $selectedFacilities = $homestay->fasilitas->pluck('id_fasilitas')->all();

        return view('admin.homestay.edit', compact('homestay', 'pemilikList', 'fasilitas', 'selectedFacilities'));
    }

    public function update(Request $request, Homestay $homestay): RedirectResponse
    {
        $validated = $request->validate([
            'id_pemilik' => ['required', 'exists:pemilik_homestay,id_pemilik'],
            'nama_homestay' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'status_verifikasi' => ['required', Rule::in(array_column(StatusVerifikasi::cases(), 'value'))],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['exists:fasilitas,id_fasilitas'],
        ], [
            'id_pemilik.required' => 'Pilih pemilik homestay warga.',
            'id_pemilik.exists' => 'Pemilik homestay yang dipilih tidak valid.',
            'nama_homestay.required' => 'Nama homestay wajib diisi.',
            'deskripsi.required' => 'Deskripsi homestay wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'status_verifikasi.required' => 'Status verifikasi wajib ditentukan.',
        ]);

        DB::transaction(function () use ($validated, $homestay) {
            $homestay->update([
                'id_pemilik' => $validated['id_pemilik'],
                'nama_homestay' => $validated['nama_homestay'],
                'deskripsi' => $validated['deskripsi'],
                'alamat' => $validated['alamat'],
                'status_verifikasi' => $validated['status_verifikasi'],
            ]);

            $homestay->fasilitas()->sync($validated['fasilitas'] ?? []);
        });

        return redirect()
            ->route('admin.homestay.show', $homestay)
            ->with('success', 'Perubahan data homestay "'.$homestay->nama_homestay.'" berhasil disimpan.');
    }

    public function destroy(Homestay $homestay): RedirectResponse
    {
        if ($homestay->booking()->exists()) {
            return redirect()
                ->route('admin.homestay.show', $homestay)
                ->with('error', 'Homestay "'.$homestay->nama_homestay.'" memiliki riwayat transaksi/booking dan tidak dapat dihapus. Anda dapat mengubah statusnya menjadi "ditolak" untuk menonaktifkannya dari katalog.');
        }

        $nama = $homestay->nama_homestay;

        DB::transaction(function () use ($homestay) {
            $homestay->fasilitas()->detach();
            $homestay->kamar()->delete();
            $homestay->paket()->delete();
            $homestay->delete();
        });

        return redirect()
            ->route('admin.homestay.index')
            ->with('success', 'Homestay "'.$nama.'" berhasil dihapus dari sistem.');
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
