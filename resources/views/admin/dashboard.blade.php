@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page_title', 'Ringkasan Desa Wisata')
@section('page_description', 'Pantau pengguna, verifikasi homestay, pembayaran, dan aktivitas pemesanan.')

@section('dashboard')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Pengguna" :value="$stats['pengguna']" hint="Semua" />
        <x-stat-card label="Homestay Menunggu" :value="$stats['homestay_menunggu']" hint="Verifikasi" tone="clay" />
        <x-stat-card label="Pembayaran Menunggu" :value="$stats['pembayaran_menunggu']" hint="Verifikasi" tone="sand" />
        <x-stat-card label="Booking Bulan Ini" :value="$stats['booking_bulan_ini']" hint="Dibuat" tone="sky" />
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2">
        <x-stat-card label="Pendapatan Terverifikasi" :value="rupiah($stats['pendapatan_terverifikasi'])" hint="Pembayaran" tone="forest" />
        <x-stat-card label="Booking Aktif" :value="$activeBookings" hint="Berjalan" tone="sky" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <x-card padding="p-0">
            <div class="flex items-center justify-between gap-3 border-b border-sand-200 p-5">
                <h2 class="font-display text-xl font-semibold text-forest-950">Homestay menunggu verifikasi</h2>
                @if (Route::has('admin.homestay.index'))
                    <a href="{{ route('admin.homestay.index') }}" class="text-sm font-bold text-forest-700 hover:underline">Lihat semua</a>
                @endif
            </div>

            @if ($pendingHomestays->isEmpty())
                <div class="p-5">
                    <x-empty-state title="Semua sudah ditinjau" description="Tidak ada pengajuan homestay baru saat ini." />
                </div>
            @else
                <ul class="divide-y divide-sand-100">
                    @foreach ($pendingHomestays as $homestay)
                        <li class="p-5">
                            <p class="font-semibold text-forest-950">{{ $homestay->nama_homestay }}</p>
                            <p class="mt-1 text-sm text-stone-500">{{ $homestay->pemilik->nama_pemilik }} · {{ $homestay->alamat }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>

        <x-card padding="p-0">
            <div class="flex items-center justify-between gap-3 border-b border-sand-200 p-5">
                <h2 class="font-display text-xl font-semibold text-forest-950">Pembayaran menunggu</h2>
                @if (Route::has('admin.pembayaran.index'))
                    <a href="{{ route('admin.pembayaran.index') }}" class="text-sm font-bold text-forest-700 hover:underline">Lihat semua</a>
                @endif
            </div>

            @if ($pendingPayments->isEmpty())
                <div class="p-5">
                    <x-empty-state title="Tidak ada pembayaran baru" description="Semua bukti pembayaran sudah diverifikasi." />
                </div>
            @else
                <ul class="divide-y divide-sand-100">
                    @foreach ($pendingPayments as $payment)
                        <li class="flex items-center justify-between gap-3 p-5">
                            <div class="min-w-0">
                                <p class="font-semibold text-forest-950">{{ $payment->booking->wisatawan->nama_wisatawan }}</p>
                                <p class="mt-1 text-sm text-stone-500">{{ $payment->booking->kamar->homestay->nama_homestay }}</p>
                                <p class="mt-1 text-xs text-stone-400">{{ tanggal_id($payment->tgl_bayar) }} · {{ $payment->metode }}</p>
                            </div>
                            <p class="font-display text-lg font-semibold text-forest-900">{{ rupiah($payment->jumlah) }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>
    </div>
@endsection
