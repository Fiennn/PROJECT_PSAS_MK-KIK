<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PembayaranProofController extends Controller
{
    public function show(Pembayaran $pembayaran): StreamedResponse
    {
        $this->authorize('viewProof', $pembayaran);

        abort_unless(Storage::disk('local')->exists($pembayaran->bukti_transfer), 404);

        $extension = pathinfo($pembayaran->bukti_transfer, PATHINFO_EXTENSION);
        $filename = 'bukti-transfer-'.$pembayaran->id_pembayaran.'.'.$extension;

        return Storage::disk('local')->response(
            $pembayaran->bukti_transfer,
            $filename,
            ['Content-Disposition' => 'inline; filename="'.$filename.'"']
        );
    }
}
