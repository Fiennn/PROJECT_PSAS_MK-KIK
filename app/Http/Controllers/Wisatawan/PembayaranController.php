<?php

namespace App\Http\Controllers\Wisatawan;

use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wisatawan\StorePembayaranRequest;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PembayaranController extends Controller
{
    public function store(StorePembayaranRequest $request, Booking $booking): RedirectResponse
    {
        $this->authorize('uploadPayment', $booking);

        $pendingPaymentExists = $booking->pembayaran()
            ->where('status_verifikasi', StatusPembayaran::Menunggu->value)
            ->exists();

        if ($pendingPaymentExists) {
            return back()->with('error', 'Masih ada bukti pembayaran yang menunggu verifikasi.');
        }

        $path = $request->file('bukti_transfer')->store('pembayaran', 'local');

        try {
            DB::transaction(function () use ($request, $booking, $path) {
                Pembayaran::create([
                    'id_booking' => $booking->id_booking,
                    'metode' => $request->validated('metode'),
                    'jumlah' => $booking->total_harga,
                    'bukti_transfer' => $path,
                    'tgl_bayar' => now(),
                    'status_verifikasi' => StatusPembayaran::Menunggu->value,
                ]);

                $booking->update(['status' => StatusBooking::MenungguVerifikasi->value]);
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);

            throw $exception;
        }

        return redirect()
            ->route('wisatawan.booking.show', $booking)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Admin desa akan memverifikasinya.');
    }
}
