@extends('layouts.dashboard')

@section('title', 'Booking Masuk')
@section('page_title', 'Booking Masuk')
@section('page_description', 'Tinjau pesanan yang masuk, konfirmasi tamu, dan tandai pesanan yang sudah selesai.')

@section('dashboard')
    @php
        $statusTabs = [
            '' => 'Semua',
            'menunggu_verifikasi' => 'Perlu Konfirmasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
        ];
    @endphp

    <div class="flex flex-wrap gap-2">
        @foreach ($statusTabs as $value => $label)
            <a
                href="{{ $value === '' ? route('pemilik.booking.index') : route('pemilik.booking.index', ['status' => $value]) }}"
                class="focus-ring rounded-full border px-4 py-2 text-xs font-semibold transition {{ (string) $status === (string) $value ? 'border-forest-700 bg-forest-700 text-white' : 'border-sand-200 bg-white text-forest-900 hover:bg-sand-100' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if ($bookings->isEmpty())
        <div class="mt-6">
            <x-empty-state
                title="Belum ada pesanan"
                description="Pesanan tamu akan muncul di sini setelah homestay Anda disetujui dan dipesan wisatawan."
            />
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($bookings as $booking)
                <x-card padding="p-0">
                    <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="font-display text-lg font-semibold text-forest-950">{{ $booking->wisatawan->nama_wisatawan }}</h2>
                                <x-badge :status="$booking->status->value" />
                            </div>
                            <p class="mt-1 text-sm text-stone-500">
                                {{ $booking->kamar->homestay->nama_homestay }} · {{ $booking->kamar->nama_kamar }}
                            </p>
                            <p class="mt-1 text-xs text-stone-400">
                                {{ tanggal_id($booking->tgl_checkin) }} – {{ tanggal_id($booking->tgl_checkout) }}
                                · {{ $booking->jumlah_tamu }} tamu · {{ $booking->jumlah_malam }} malam
                            </p>
                            <p class="mt-1 text-xs text-stone-400">Dipesan {{ tanggal_id($booking->tgl_booking) }}</p>
                        </div>

                        <div class="flex shrink-0 flex-col items-start gap-3 sm:items-end">
                            <p class="font-display text-xl font-semibold text-forest-900">{{ rupiah($booking->total_harga) }}</p>
                            <a href="{{ route('pemilik.booking.show', $booking) }}" class="focus-ring rounded-full bg-forest-50 px-4 py-2 text-xs font-bold text-forest-800 transition hover:bg-forest-700 hover:text-white">
                                Tinjau pesanan
                            </a>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <x-pagination :paginator="$bookings" />
    @endif
@endsection
