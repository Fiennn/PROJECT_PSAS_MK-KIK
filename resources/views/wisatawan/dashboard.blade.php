@extends('layouts.dashboard')

@section('title', 'Dashboard Wisatawan')
@section('page_title', 'Perjalanan Anda')
@section('page_description', 'Pantau pesanan, pembayaran, dan cerita perjalanan Anda di desa wisata.')

@section('page_actions')
    @if (Route::has('homestay.index'))
        <x-button :href="route('homestay.index')">Cari Homestay</x-button>
    @endif
@endsection

@section('dashboard')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Total Pemesanan" :value="$stats['total']" hint="Semua" />
        <x-stat-card label="Akan Menginap" :value="$stats['upcoming']" hint="Dikonfirmasi" tone="sky" />
        <x-stat-card label="Selesai" :value="$stats['selesai']" hint="Perjalanan" tone="clay" />
        <x-stat-card label="Total Pengeluaran" :value="rupiah($stats['pengeluaran'])" hint="Aktif & selesai" tone="sand" />
    </div>

    <div class="mt-6">
        <x-card padding="p-0">
            <div class="flex flex-col gap-2 border-b border-sand-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-display text-xl font-semibold text-forest-950">Pemesanan terbaru</h2>
                    <p class="mt-1 text-sm text-stone-500">Lima pemesanan terakhir yang Anda buat.</p>
                </div>
                @if (Route::has('wisatawan.booking.index'))
                    <a href="{{ route('wisatawan.booking.index') }}" class="text-sm font-bold text-forest-700 hover:underline">Lihat semua</a>
                @endif
            </div>

            @if ($bookings->isEmpty())
                <div class="p-5">
                    <x-empty-state
                        title="Belum ada pemesanan"
                        description="Mulai jelajahi homestay desa wisata dan pesan kamar pertama Anda."
                    >
                        @if (Route::has('homestay.index'))
                            <x-button :href="route('homestay.index')">Jelajahi Homestay</x-button>
                        @endif
                    </x-empty-state>
                </div>
            @else
                <ul class="divide-y divide-sand-100">
                    @foreach ($bookings as $booking)
                        <li class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-semibold text-forest-950">{{ $booking->kamar->homestay->nama_homestay }}</p>
                                    <x-badge :status="$booking->status->value" />
                                </div>
                                <p class="mt-1 text-sm text-stone-500">
                                    {{ $booking->kamar->nama_kamar }} · {{ tanggal_id($booking->tgl_checkin) }} – {{ tanggal_id($booking->tgl_checkout) }}
                                </p>
                                <p class="mt-1 text-xs text-stone-400">{{ $booking->jumlah_malam }} malam · {{ $booking->jumlah_tamu }} tamu</p>
                            </div>
                            <div class="flex items-center justify-between gap-4 sm:flex-col sm:items-end">
                                <p class="font-display text-lg font-semibold text-forest-900">{{ rupiah($booking->total_harga) }}</p>
                                @if (Route::has('wisatawan.booking.show'))
                                    <a href="{{ route('wisatawan.booking.show', $booking) }}" class="text-xs font-bold text-forest-700 hover:underline">Lihat detail</a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>
    </div>
@endsection
