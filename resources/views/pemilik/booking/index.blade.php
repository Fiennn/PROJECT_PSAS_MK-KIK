@extends('layouts.dashboard')

@section('title', 'Booking Masuk')
@section('page_title', 'Reservasi Tamu Masuk')
@section('page_description', 'Pantau dan kelola seluruh pesanan tamu, konfirmasi kedatangan wisatawan, dan tandai reservasi yang telah selesai.')

@section('dashboard')
    @php
        $statusTabs = [
            '' => 'Semua',
            'menunggu_verifikasi' => 'Perlu Konfirmasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'selesai' => 'Selesai',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'ditolak' => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
        ];
    @endphp

    <!-- KPI Counts Bar -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-6">
        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-stone-500">
                <span class="text-xs font-bold uppercase tracking-wider">Total Reservasi</span>
                <span class="material-symbols-outlined text-stone-400 text-lg">receipt_long</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-stone-900">{{ $counts['total'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Seluruh pesanan masuk</p>
        </div>

        <div class="rounded-2xl border {{ ($counts['perlu_konfirmasi'] ?? 0) > 0 ? 'border-amber-300 bg-amber-50/50' : 'border-stone-200 bg-white' }} p-4 shadow-xs">
            <div class="flex items-center justify-between text-amber-700">
                <span class="text-xs font-bold uppercase tracking-wider">Perlu Konfirmasi</span>
                <span class="material-symbols-outlined text-amber-600 text-lg">notification_important</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold {{ ($counts['perlu_konfirmasi'] ?? 0) > 0 ? 'text-[#ea580c]' : 'text-stone-900' }}">
                {{ $counts['perlu_konfirmasi'] ?? 0 }}
            </p>
            <p class="mt-0.5 text-[11px] text-stone-400">Pembayaran terverifikasi</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-sky-600">
                <span class="text-xs font-bold uppercase tracking-wider">Dikonfirmasi</span>
                <span class="material-symbols-outlined text-sky-500 text-lg">task_alt</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-sky-800">{{ $counts['dikonfirmasi'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Siap datang / menginap</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-emerald-600">
                <span class="text-xs font-bold uppercase tracking-wider">Selesai</span>
                <span class="material-symbols-outlined text-emerald-500 text-lg">verified</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-[#13391b]">{{ $counts['selesai'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Kunjungan sukses</p>
        </div>
    </div>

    <!-- Card Validasi E-Tiket Tamu Cepat -->
    <div class="mb-6 rounded-3xl border border-emerald-200 bg-gradient-to-r from-emerald-50 via-white to-stone-50 p-5 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#13391b] text-emerald-300 shadow-xs">
                    <span class="material-symbols-outlined text-2xl">qr_code_scanner</span>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-stone-900 flex items-center gap-2">
                        <span>Validasi Check-In E-Tiket Tamu</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-[#ea580c]">Instan</span>
                    </h3>
                    <p class="mt-0.5 text-xs text-stone-600">
                        Masukkan kode tiket (misal: <code class="font-mono font-bold text-emerald-800">HSG-00012</code> atau nomor booking) dari e-tiket tamu untuk verifikasi keabsahan reservasi.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('pemilik.booking.validate-ticket') }}" class="flex items-center gap-2 w-full lg:w-auto shrink-0">
                @csrf
                <div class="relative flex-1 sm:w-64">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-stone-400">
                        <span class="material-symbols-outlined text-sm">confirmation_number</span>
                    </span>
                    <input
                        type="text"
                        name="kode_tiket"
                        required
                        placeholder="Contoh: HSG-00012"
                        class="w-full rounded-xl border border-stone-300 bg-white py-2 pl-9 pr-3 text-xs font-mono font-bold text-stone-900 placeholder-stone-400 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                    />
                </div>
                <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all active:scale-95 shrink-0"
                >
                    <span class="material-symbols-outlined text-sm">verified_user</span>
                    <span>Cek Tiket</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="rounded-2xl border border-stone-200 bg-white p-4 sm:p-5 shadow-xs mb-6 space-y-4">
        <!-- Status Tabs -->
        <div class="flex flex-wrap gap-1.5 pb-2 border-b border-stone-100">
            @foreach ($statusTabs as $value => $label)
                <a
                    href="{{ route('pemilik.booking.index', array_merge(request()->query(), ['status' => $value, 'page' => 1])) }}"
                    class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-bold transition-all {{ (string) $status === (string) $value ? 'bg-[#13391b] text-white shadow-xs' : 'bg-stone-50 text-stone-600 hover:bg-stone-100 hover:text-stone-900 border border-stone-200/80' }}"
                >
                    <span>{{ $label }}</span>
                </a>
            @endforeach
        </div>

        <!-- Search Bar and Homestay Picker -->
        <form method="GET" action="{{ route('pemilik.booking.index') }}" class="grid gap-3 sm:grid-cols-[1.5fr_1fr_auto] sm:items-end">
            <input type="hidden" name="status" value="{{ $status ?? '' }}">

            <div>
                <label for="q" class="block text-xs font-bold text-stone-700 mb-1">Cari Tamu / Booking</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-stone-400 text-lg">search</span>
                    <input
                        id="q"
                        type="text"
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="Nama tamu, ID booking, atau nama kamar..."
                        class="w-full text-xs sm:text-sm pl-9 pr-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none"
                    >
                </div>
            </div>

            <div>
                <label for="homestay" class="block text-xs font-bold text-stone-700 mb-1">Pilih Unit Homestay</label>
                <select
                    id="homestay"
                    name="homestay"
                    class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none bg-white"
                >
                    <option value="">Semua Homestay Anda</option>
                    @foreach ($ownerHomestays as $h)
                        <option value="{{ $h->id_homestay }}" @selected(($homestayFilter ?? '') == $h->id_homestay)>
                            {{ $h->nama_homestay }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
                >
                    <span class="material-symbols-outlined text-sm">filter_alt</span>
                    <span>Filter</span>
                </button>
                @if ($q || $homestayFilter || $status)
                    <a
                        href="{{ route('pemilik.booking.index') }}"
                        class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 text-xs font-semibold transition-all"
                        title="Reset Filter"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Daftar Booking -->
    @if ($bookings->isEmpty())
        <div class="rounded-3xl border border-dashed border-stone-300 bg-white p-12 text-center shadow-xs">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-[#13391b] mb-4">
                <span class="material-symbols-outlined text-3xl">inbox</span>
            </div>
            <h3 class="text-lg font-bold text-stone-900">
                {{ $q || $status || $homestayFilter ? 'Tidak Ada Pesanan yang Sesuai Filter' : 'Belum Ada Pesanan Tamu' }}
            </h3>
            <p class="mt-1 text-xs sm:text-sm text-stone-500 max-w-md mx-auto leading-relaxed">
                {{ $q || $status || $homestayFilter ? 'Coba ubah kata kunci atau ganti status filter untuk melihat daftar pesanan lain.' : 'Pesanan dari wisatawan nusantara akan otomatis masuk ke halaman ini setelah mereka memesan homestay Anda.' }}
            </p>
            @if ($q || $status || $homestayFilter)
                <div class="mt-6">
                    <a
                        href="{{ route('pemilik.booking.index') }}"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full border border-stone-200 text-stone-700 hover:bg-stone-50 text-xs font-semibold"
                    >
                        <span>Reset Semua Filter</span>
                    </a>
                </div>
            @endif
        </div>
    @else
        <div class="space-y-4">
            @foreach ($bookings as $booking)
                <div class="rounded-3xl border border-stone-200 bg-white p-5 sm:p-6 shadow-xs hover:border-emerald-300 transition-all flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    <!-- Left: Guest & Homestay Details -->
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-[#13391b] font-extrabold text-base shadow-2xs">
                            {{ strtoupper(substr($booking->wisatawan->nama_wisatawan ?? 'W', 0, 2)) }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h3 class="text-base font-extrabold text-stone-900 truncate">
                                    {{ $booking->wisatawan->nama_wisatawan }}
                                </h3>
                                <x-badge :status="$booking->status->value" />
                                <span class="text-xs font-bold text-stone-400">#{{ $booking->id_booking }}</span>
                            </div>

                            <p class="text-xs text-stone-600 font-medium">
                                <span class="text-[#13391b] font-bold">{{ $booking->kamar->homestay->nama_homestay }}</span> &bull; {{ $booking->kamar->nama_kamar }}
                            </p>

                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-stone-500">
                                <span class="inline-flex items-center gap-1 font-semibold text-stone-700 bg-stone-100 px-2.5 py-1 rounded-lg">
                                    <span class="material-symbols-outlined text-xs text-stone-400">event</span>
                                    <span>{{ tanggal_id($booking->tgl_checkin) }} – {{ tanggal_id($booking->tgl_checkout) }}</span>
                                </span>
                                <span>&bull;</span>
                                <span>{{ $booking->jumlah_tamu }} Tamu ({{ $booking->jumlah_malam }} Malam)</span>
                                @if ($booking->wisatawan->no_hp)
                                    <span>&bull;</span>
                                    <a
                                        href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $booking->wisatawan->no_hp)) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1 text-emerald-700 hover:text-emerald-900 font-semibold"
                                        title="Chat WhatsApp Wisatawan"
                                    >
                                        <span class="material-symbols-outlined text-xs text-[#25D366]">chat</span>
                                        <span>{{ $booking->wisatawan->no_hp }}</span>
                                    </a>
                                @endif
                            </div>

                            <!-- Paket Wisata Pill if selected -->
                            @if ($booking->paket->isNotEmpty())
                                <div class="mt-2.5 flex flex-wrap gap-1.5">
                                    @foreach ($booking->paket as $pkg)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-[#ea580c] text-[10px] font-bold border border-amber-200">
                                            <span class="material-symbols-outlined text-xs">hiking</span>
                                            <span>{{ $pkg->nama_paket }} ({{ $pkg->pivot->jumlah_peserta }} org)</span>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Price & CTA Actions -->
                    <div class="flex flex-row lg:flex-col items-center lg:items-end justify-between shrink-0 gap-3 pt-3 lg:pt-0 border-t lg:border-0 border-stone-100">
                        <div class="text-left lg:text-right">
                            <p class="text-[11px] text-stone-400 font-bold uppercase tracking-wider">Total Pembayaran</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-[#13391b]">{{ rupiah($booking->total_harga) }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <a
                                href="{{ route('pemilik.booking.show', $booking) }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs hover:shadow-md transition-all"
                            >
                                <span>Tinjau Pesanan</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            <x-pagination :paginator="$bookings" />
        </div>
    @endif
@endsection
