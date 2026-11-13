@extends('layouts.app')

@section('title', 'E-Tiket Resmi — ' . $ticketCode)

@push('head')
<style>
    @media print {
        header, footer, nav, .no-print, [role="navigation"] {
            display: none !important;
        }
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        main {
            padding: 0 !important;
            margin: 0 !important;
        }
        .ticket-wrapper {
            box-shadow: none !important;
            border: 1px solid #94a3b8 !important;
            page-break-inside: avoid;
            margin: 0 auto !important;
            max-width: 100% !important;
        }
        @page {
            margin: 10mm;
            size: auto;
        }
    }
</style>
@endpush

@section('content')
<div class="py-8 bg-stone-100 min-h-screen print:bg-white print:py-0">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Navigation Action Bar (Hidden in Print) --}}
        <div class="no-print mb-6 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('wisatawan.booking.show', $booking) }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-stone-700 hover:text-emerald-900 transition">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali ke Detail Pesanan
            </a>
            <div class="flex items-center gap-3">
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($booking->kamar->homestay->nama_homestay . ' ' . $booking->kamar->homestay->alamat) }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-300 bg-white text-stone-800 text-sm font-semibold hover:bg-stone-50 transition shadow-sm">
                    <span class="material-symbols-outlined text-orange-600 text-lg">directions</span>
                    Buka Rute Maps
                </a>
                <button type="button" onclick="window.print()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-800 text-white text-sm font-bold hover:bg-emerald-900 transition shadow-md shadow-emerald-900/20 active:scale-95">
                    <span class="material-symbols-outlined text-lg">print</span>
                    Cetak / Simpan PDF
                </button>
            </div>
        </div>

        {{-- Offline Warning Banner (Hidden in Print) --}}
        <div class="no-print mb-6 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/80 rounded-2xl p-4 flex items-start gap-3 text-stone-800">
            <span class="material-symbols-outlined text-orange-600 text-2xl flex-shrink-0">wifi_off</span>
            <div class="text-xs sm:text-sm">
                <p class="font-bold text-stone-900">Tips Perjalanan Desa Wisata:</p>
                <p class="text-stone-600 mt-0.5">Sinyal internet di kawasan perbukitan atau dusun terkadang terbatas. Silakan <strong>Cetak</strong> atau <strong>Tangkapan Layar (Screenshot)</strong> halaman E-Tiket ini sebelum berangkat ke lokasi!</p>
            </div>
        </div>

        {{-- Official Boarding Pass Ticket Card --}}
        <div class="ticket-wrapper bg-white rounded-3xl shadow-xl overflow-hidden border border-stone-200 relative print:shadow-none print:border-stone-400 print:rounded-2xl">
            {{-- Header Band --}}
            <div class="bg-gradient-to-r from-[#13391b] via-[#1b4b24] to-[#0d2813] text-white px-6 sm:px-8 py-6 relative">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center text-emerald-300 flex-shrink-0">
                            <span class="material-symbols-outlined text-3xl">cottage</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-black tracking-tight text-white font-serif">HomeStay Go</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-orange-500 text-white rounded-md">Resmi</span>
                            </div>
                            <p class="text-xs text-emerald-200/90 font-medium">E-Tiket Masuk & Surat Reservasi Resmi Pokdarwis</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:items-end">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 text-xs font-bold tracking-wide">
                            <span class="material-symbols-outlined text-sm text-emerald-300">verified</span>
                            TERVERIFIKASI & LUNAS
                        </div>
                        <div class="mt-2 text-right">
                            <p class="text-[10px] uppercase tracking-widest text-emerald-200/70 font-semibold">Kode Tiket</p>
                            <p class="text-xl font-mono font-extrabold tracking-wider text-amber-300">{{ $ticketCode }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ticket Body Content --}}
            <div class="p-6 sm:p-8 space-y-6">
                {{-- Destination & Dates Strip --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-5 rounded-2xl bg-stone-50 border border-stone-200/80">
                    <div class="md:col-span-1 border-b md:border-b-0 md:border-r border-stone-200 pb-4 md:pb-0 md:pr-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Homestay Tujuan</p>
                        <h2 class="text-base font-bold text-stone-900 mt-1">{{ $booking->kamar->homestay->nama_homestay }}</h2>
                        <p class="text-xs text-stone-600 mt-1 flex items-start gap-1">
                            <span class="material-symbols-outlined text-sm text-orange-600 flex-shrink-0 mt-0.5">location_on</span>
                            <span>{{ $booking->kamar->homestay->alamat }}</span>
                        </p>
                    </div>
                    <div class="flex items-center justify-between md:justify-around md:col-span-2 pt-2 md:pt-0">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Check-In</p>
                            <p class="text-base sm:text-lg font-extrabold text-stone-900 mt-0.5">{{ tanggal_id($booking->tgl_checkin) }}</p>
                            <p class="text-xs font-semibold text-emerald-800">Mulai 14.00 WIB</p>
                        </div>
                        <div class="flex flex-col items-center px-3">
                            <span class="text-xs font-bold text-stone-500">{{ $booking->hitungJumlahMalam() }} Malam</span>
                            <div class="w-16 sm:w-24 h-0.5 bg-stone-300 relative my-1">
                                <span class="absolute -top-1.5 left-1/2 -translate-x-1/2 material-symbols-outlined text-sm text-stone-400">arrow_forward</span>
                            </div>
                            <span class="text-[10px] text-stone-500">{{ $booking->jumlah_tamu }} Tamu</span>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Check-Out</p>
                            <p class="text-base sm:text-lg font-extrabold text-stone-900 mt-0.5">{{ tanggal_id($booking->tgl_checkout) }}</p>
                            <p class="text-xs font-semibold text-stone-600">Sebelum 12.00 WIB</p>
                        </div>
                    </div>
                </div>

                {{-- Main Information Grid: Folio vs QR Code --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                    {{-- Folio Details --}}
                    <div class="lg:col-span-8 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl border border-stone-200 bg-white">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Kamar Dipilih</p>
                                <p class="text-sm font-bold text-stone-900 mt-1">{{ $booking->kamar->nama_kamar }}</p>
                                <p class="text-xs text-stone-600">Kapasitas Maksimal {{ $booking->kamar->kapasitas }} Orang</p>
                            </div>
                            <div class="p-4 rounded-xl border border-stone-200 bg-white">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Nama Pemesan (Tamu)</p>
                                <p class="text-sm font-bold text-stone-900 mt-1">{{ $booking->wisatawan->nama_wisatawan ?? $booking->wisatawan->user->nama ?? 'Tamu Desa' }}</p>
                                <p class="text-xs text-stone-600 font-mono">{{ $booking->wisatawan->no_hp ?? '-' }}</p>
                            </div>
                            <div class="p-4 rounded-xl border border-stone-200 bg-white">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Tuan Rumah (Warga Desa)</p>
                                <p class="text-sm font-bold text-stone-900 mt-1">{{ $booking->kamar->homestay->pemilik->nama_pemilik ?? $booking->kamar->homestay->pemilik->user->nama ?? 'Warga Pengelola' }}</p>
                                <p class="text-xs text-stone-600 font-mono">{{ $booking->kamar->homestay->pemilik->no_hp ?? '-' }}</p>
                            </div>
                            <div class="p-4 rounded-xl border border-stone-200 bg-white">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Total Biaya Reservasi</p>
                                <p class="text-base font-extrabold text-emerald-800 mt-1">{{ rupiah($booking->total_harga) }}</p>
                                <p class="text-[11px] font-semibold text-emerald-700">Lunas via Transfer / QRIS</p>
                            </div>
                        </div>

                        @if($booking->paket->isNotEmpty())
                            <div class="p-4 rounded-xl border border-stone-200 bg-emerald-50/40">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-900">Paket Wisata & Budaya Terpilih:</p>
                                <ul class="mt-2 space-y-1.5 text-xs text-stone-700">
                                    @foreach($booking->paket as $pkt)
                                        <li class="flex items-center justify-between">
                                            <span class="font-medium">• {{ $pkt->nama_paket }} ({{ $pkt->pivot->jumlah_peserta }} Peserta)</span>
                                            <span class="font-mono text-stone-600">{{ rupiah($pkt->pivot->subtotal) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    {{-- QR Code Box --}}
                    <div class="lg:col-span-4 flex flex-col items-center justify-center p-6 rounded-2xl bg-stone-50 border border-stone-200 text-center">
                        <div class="bg-white p-3 rounded-2xl shadow-sm border border-stone-200">
                            {!! $qrSvg !!}
                        </div>
                        <p class="mt-3 text-xs font-mono font-bold text-stone-800 tracking-wider">{{ $qrPayload }}</p>
                        <p class="mt-1 text-[11px] text-stone-500 leading-snug">
                            Pindai atau tunjukkan kode QR ini kepada tuan rumah saat tiba di desa untuk check-in instan.
                        </p>
                    </div>
                </div>

                {{-- Perforated Line Decoration --}}
                <div class="relative py-2">
                    <div class="border-t-2 border-dashed border-stone-300"></div>
                    <div class="absolute -left-10 -top-3 w-6 h-6 rounded-full bg-stone-100 print:bg-white border border-stone-300"></div>
                    <div class="absolute -right-10 -top-3 w-6 h-6 rounded-full bg-stone-100 print:bg-white border border-stone-300"></div>
                </div>

                {{-- Village Etiquette & Pokdarwis Terms --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-stone-600">
                    <div class="space-y-1.5">
                        <h4 class="font-bold text-stone-900 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-orange-600 text-base">info</span>
                            Petunjuk Kedatangan Tamu
                        </h4>
                        <p>1. Tunjukkan E-Tiket ini beserta Kartu Identitas (KTP/SIM) saat tiba di homestay.</p>
                        <p>2. Konfirmasi perkiraan waktu tiba ke Tuan Rumah melalui kontak yang tertera.</p>
                        <p>3. Patuhi jam istirahat malam warga dusun (mulai pukul 22.00 WIB).</p>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-bold text-stone-900 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-emerald-700 text-base">eco</span>
                            Tata Krama Desa Wisata Nusantara
                        </h4>
                        <p>1. Berpakaian sopan dan bertegur sapa dengan warga saat berkeliling dusun.</p>
                        <p>2. Menjaga kebersihan dan membuang sampah pada tempat yang disediakan.</p>
                        <p>3. Turut menjaga kelestarian adat istiadat, lingkungan alam, dan keharmonisan desa.</p>
                    </div>
                </div>
            </div>

            {{-- Footer Bar --}}
            <div class="bg-stone-100 border-t border-stone-200 px-6 sm:px-8 py-3 flex flex-wrap items-center justify-between text-[11px] text-stone-500">
                <p>© {{ date('Y') }} HomeStay Go • Badan Kerjasama Pokdarwis Desa Wisata Indonesia</p>
                <p class="font-mono">ID Booking: #{{ $booking->id_booking }} • Terverifikasi Sistem</p>
            </div>
        </div>
    </div>
</div>
@endsection
