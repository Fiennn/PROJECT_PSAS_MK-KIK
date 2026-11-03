@extends('layouts.dashboard')

@section('title', 'Pemesanan Saya')
@section('page_title', 'Pemesanan Saya')
@section('page_description', 'Pantau status pembayaran, konfirmasi pemilik, dan riwayat menginap Anda.')

@section('page_actions')
    @if (Route::has('homestay.index'))
        <x-button :href="route('homestay.index')" variant="secondary">Cari Homestay</x-button>
    @endif
@endsection

@section('dashboard')
    @php
        $statusTabs = [
            '' => 'Semua',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
        ];
    @endphp

    <div class="flex flex-wrap gap-2">
        @foreach ($statusTabs as $value => $label)
            <a
                href="{{ $value === '' ? route('wisatawan.booking.index') : route('wisatawan.booking.index', ['status' => $value]) }}"
                class="focus-ring rounded-full border px-4 py-2 text-xs font-semibold transition {{ (string) $status === (string) $value ? 'border-forest-700 bg-forest-700 text-white' : 'border-sand-200 bg-white text-forest-900 hover:bg-sand-100' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if ($bookings->isEmpty())
        <div class="mt-6">
            <x-empty-state
                title="Belum ada pemesanan"
                description="Pemesanan yang Anda buat akan muncul di sini beserta status dan detail pembayarannya."
            >
                @if (Route::has('homestay.index'))
                    <x-button :href="route('homestay.index')">Jelajahi Homestay</x-button>
                @endif
            </x-empty-state>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($bookings as $booking)
                <x-card padding="p-0">
                    <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="font-display text-lg font-semibold text-forest-950">{{ $booking->kamar->homestay->nama_homestay }}</h2>
                                <x-badge :status="$booking->status->value" />
                            </div>
                            <p class="mt-1 text-sm text-stone-500">
                                {{ $booking->kamar->nama_kamar }} · {{ $booking->jumlah_tamu }} tamu · {{ $booking->jumlah_malam }} malam
                            </p>
                            <p class="mt-1 text-xs text-stone-400">
                                {{ tanggal_id($booking->tgl_checkin) }} – {{ tanggal_id($booking->tgl_checkout) }}
                                · Dipesan {{ tanggal_id($booking->tgl_booking) }}
                            </p>
                            @if ($booking->paket->isNotEmpty())
                                <p class="mt-2 text-xs text-clay-700">
                                    Paket: {{ $booking->paket->map(fn ($package) => $package->nama_paket.' ('.$package->pivot->jumlah_peserta.' peserta)')->join(', ') }}
                                </p>
                            @endif
                        </div>

                        <div class="flex shrink-0 flex-col items-start gap-3 sm:items-end">
                            <p class="font-display text-xl font-semibold text-forest-900">{{ rupiah($booking->total_harga) }}</p>
                            <a href="{{ route('wisatawan.booking.show', $booking) }}" class="focus-ring rounded-full bg-forest-50 px-4 py-2 text-xs font-bold text-forest-800 transition hover:bg-forest-700 hover:text-white">
                                Lihat detail
                            </a>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <x-pagination :paginator="$bookings" />
    @endif
@endsection
