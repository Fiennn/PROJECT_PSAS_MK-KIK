<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(array_column(StatusPembayaran::cases(), 'value'))],
        ]);

        $payments = Pembayaran::query()
            ->with(['booking.wisatawan', 'booking.kamar.homestay'])
            ->when($filters['q'] ?? null, function ($query, $keyword) {
                $query->where(function ($builder) use ($keyword) {
                    $builder->whereHas('booking.wisatawan', fn ($relation) => $relation->where('nama_wisatawan', 'like', '%'.$keyword.'%'))
                        ->orWhereHas('booking.kamar.homestay', fn ($relation) => $relation->where('nama_homestay', 'like', '%'.$keyword.'%'));
                });
            })
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status_verifikasi', $status))
            ->orderByRaw("FIELD(status_verifikasi, 'menunggu', 'ditolak', 'terverifikasi')")
            ->orderByDesc('id_pembayaran')
            ->paginate(12)
            ->withQueryString();

        return view('admin.pembayaran.index', compact('payments', 'filters'));
    }

    public function show(Pembayaran $pembayaran): View
    {
        $pembayaran->load(['booking.wisatawan', 'booking.kamar.homestay.pemilik', 'booking.paket']);

        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function verify(Pembayaran $pembayaran): RedirectResponse
    {
        $this->authorize('verify', $pembayaran);

        $pembayaran->update(['status_verifikasi' => StatusPembayaran::Terverifikasi->value]);

        return redirect()
            ->route('admin.pembayaran.show', $pembayaran)
            ->with('success', 'Pembayaran terverifikasi. Pemilik homestay sekarang dapat mengonfirmasi pesanan.');
    }

    public function reject(Pembayaran $pembayaran): RedirectResponse
    {
        $this->authorize('reject', $pembayaran);

        DB::transaction(function () use ($pembayaran) {
            $pembayaran->update(['status_verifikasi' => StatusPembayaran::Ditolak->value]);

            $booking = $pembayaran->booking()->lockForUpdate()->firstOrFail();

            if ($booking->status === StatusBooking::MenungguVerifikasi) {
                $booking->update(['status' => StatusBooking::MenungguPembayaran->value]);
            }
        });

        return redirect()
            ->route('admin.pembayaran.show', $pembayaran)
            ->with('success', 'Pembayaran ditolak. Pesanan dikembalikan ke status menunggu pembayaran agar tamu dapat mengunggah ulang bukti.');
    }
}
