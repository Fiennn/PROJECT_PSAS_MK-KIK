<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use Illuminate\Http\JsonResponse;

class HomestayController extends Controller
{
    public function index(): JsonResponse
    {
        $homestays = Homestay::query()
            ->where('status_verifikasi', 'disetujui')
            ->with('fasilitas')
            ->withCount('kamar')
            ->orderByDesc('id_homestay')
            ->get()
            ->map(fn (Homestay $homestay) => [
                'id' => $homestay->id_homestay,
                'nama' => $homestay->nama_homestay,
                'alamat' => $homestay->alamat,
                'deskripsi' => $homestay->deskripsi,
                'fasilitas' => $homestay->fasilitas->pluck('nama_fasilitas'),
                'jumlah_kamar' => $homestay->kamar_count,
            ]);

        return response()->json([
            'success' => true,
            'data' => $homestays,
        ]);
    }

    public function show(Homestay $homestay): JsonResponse
    {
        if ($homestay->status_verifikasi->value !== 'disetujui') {
            return response()->json([
                'success' => false,
                'message' => 'Homestay tidak ditemukan.',
            ], 404);
        }

        $homestay->load(['fasilitas', 'kamar', 'paket']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $homestay->id_homestay,
                'nama' => $homestay->nama_homestay,
                'alamat' => $homestay->alamat,
                'deskripsi' => $homestay->deskripsi,
                'fasilitas' => $homestay->fasilitas->pluck('nama_fasilitas'),
                'kamar' => $homestay->kamar->map(fn ($room) => [
                    'id' => $room->id_kamar,
                    'nama' => $room->nama_kamar,
                    'kapasitas' => $room->kapasitas,
                    'harga_per_malam' => (float) $room->harga_per_malam,
                    'status' => $room->status->value,
                ]),
                'paket' => $homestay->paket->map(fn ($package) => [
                    'id' => $package->id_paket,
                    'nama' => $package->nama_paket,
                    'deskripsi' => $package->deskripsi,
                    'harga' => (float) $package->harga,
                    'durasi' => $package->durasi,
                ]),
            ],
        ]);
    }
}
