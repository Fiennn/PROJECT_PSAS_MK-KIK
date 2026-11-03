@extends('layouts.dashboard')

@section('title', 'Dashboard Pemilik')
@section('page_title', 'Rumah Anda')
@section('page_description', 'Ringkasan homestay, pesanan yang perlu ditindaklanjuti, dan pendapatan Anda.')

@section('page_actions')
    @if (Route::has('pemilik.homestay.create'))
        <x-button :href="route('pemilik.homestay.create')">Tambah Homestay</x-button>
    @endif
@endsection

@section('dashboard')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Homestay" :value="$stats['homestay']" hint="Dikelola" />
        <x-stat-card label="Perlu Konfirmasi" :value="$stats['perlu_konfirmasi']" hint="Pesanan" tone="clay" />
        <x-stat-card label="Dikonfirmasi" :value="$stats['dikonfirmasi']" hint="Aktif" tone="sky" />
        <x-stat-card label="Pendapatan" :value="rupiah($stats['pendapatan'])" hint="Dikonfirmasi" tone="sand" />
    </div>

    @if ($stats['perlu_konfirmasi'] > 0 && Route::has('pemilik.booking.index'))
        <div class="mt-6">
            <x-alert type="warning">
                Ada {{ $stats['perlu_konfirmasi'] }} pesanan dengan pembayaran terverifikasi yang menunggu konfirmasi Anda.
                <a href="{{ route('pemilik.booking.index') }}" class="font-bold underline">Tinjau pesanan</a>.
            </x-alert>
        </div>
    @endif

    <div class="mt-6">
        <x-card padding="p-0">
            <div class="flex flex-col gap-2 border-b border-sand-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-display text-xl font-semibold text-forest-950">Pesanan terbaru</h2>
                    <p class="mt-1 text-sm text-stone-500">Pantau tamu yang akan datang ke homestay Anda.</p>
                </div>
                @if (Route::has('pemilik.booking.index'))
                    <a href="{{ route('pemilik.booking.index') }}" class="text-sm font-bold text-forest-700 hover:underline">Lihat semua</a>
                @endif
            </div>

            @if ($bookings->isEmpty())
                <div class="p-5">
                    <x-empty-state title="Belum ada pesanan" description="Pesanan tamu akan muncul di sini setelah homestay Anda disetujui dan dipesan." />
                </div>
            @else
                <ul class="divide-y divide-sand-100">
                    @foreach ($bookings as $booking)
                        <li class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-semibold text-forest-950">{{ $booking->wisatawan->nama_wisatawan }}</p>
                                    <x-badge :status="$booking->status->value" />
                                </div>
                                <p class="mt-1 text-sm text-stone-500">
                                    {{ $booking->kamar->homestay->nama_homestay }} · {{ $booking->kamar->nama_kamar }}
                                </p>
                                <p class="mt-1 text-xs text-stone-400">{{ tanggal_id($booking->tgl_checkin) }} – {{ tanggal_id($booking->tgl_checkout) }}</p>
                            </div>
                            <p class="font-display text-lg font-semibold text-forest-900">{{ rupiah($booking->total_harga) }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>
    </div>
@endsection
