@extends('layouts.dashboard')

@section('title', 'Homestay Saya')
@section('page_title', 'Homestay Saya')
@section('page_description', 'Kelola informasi hunian desa Anda, pantau status verifikasi Pokdarwis, serta atur kamar dan paket wisata.')

@section('page_actions')
    <a
        href="{{ route('pemilik.homestay.create') }}"
        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs hover:shadow-md transition-all cursor-pointer"
    >
        <span class="material-symbols-outlined text-sm">add_home</span>
        <span>+ Daftarkan Homestay Baru</span>
    </a>
@endsection

@section('dashboard')
    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-6">
        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-stone-500">
                <span class="text-xs font-bold uppercase tracking-wider">Total Homestay</span>
                <span class="material-symbols-outlined text-stone-400 text-lg">domain</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-stone-900">{{ $stats['total'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Unit hunian Anda</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-emerald-600">
                <span class="text-xs font-bold uppercase tracking-wider">Katalog Aktif</span>
                <span class="material-symbols-outlined text-emerald-500 text-lg">verified</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-[#13391b]">{{ $stats['disetujui'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Disetujui & siap dipesan</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-amber-600">
                <span class="text-xs font-bold uppercase tracking-wider">Menunggu</span>
                <span class="material-symbols-outlined text-amber-500 text-lg">pending</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-amber-700">{{ $stats['menunggu'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Proses verifikasi Pokdarwis</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-sky-600">
                <span class="text-xs font-bold uppercase tracking-wider">Total Kamar</span>
                <span class="material-symbols-outlined text-sky-500 text-lg">bed</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-stone-900">{{ $stats['total_kamar'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Kamar siap dihuni tamu</p>
        </div>
    </div>

    <!-- Filter & Pencarian Homestay -->
    <div class="rounded-2xl border border-stone-200 bg-white p-4 sm:p-5 shadow-xs mb-6">
        <form method="GET" action="{{ route('pemilik.homestay.index') }}" class="grid gap-3 sm:grid-cols-[1.5fr_1fr_auto] sm:items-end">
            <div>
                <label for="q" class="block text-xs font-bold text-stone-700 mb-1">Cari Homestay</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-stone-400 text-lg">search</span>
                    <input
                        id="q"
                        type="text"
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="Nama homestay atau alamat..."
                        class="w-full text-xs sm:text-sm pl-9 pr-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none"
                    >
                </div>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-stone-700 mb-1">Status Publikasi</label>
                <select
                    id="status"
                    name="status"
                    class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none bg-white"
                >
                    <option value="">Semua Status Verifikasi</option>
                    <option value="disetujui" @selected(($status ?? '') === 'disetujui')>Disetujui (Katalog Aktif)</option>
                    <option value="menunggu" @selected(($status ?? '') === 'menunggu')>Menunggu Verifikasi</option>
                    <option value="ditolak" @selected(($status ?? '') === 'ditolak')>Ditolak / Perlu Revisi</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold transition-all cursor-pointer shadow-xs"
                >
                    <span class="material-symbols-outlined text-sm">filter_alt</span>
                    <span>Terapkan</span>
                </button>
                @if ($q || $status)
                    <a
                        href="{{ route('pemilik.homestay.index') }}"
                        class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 text-xs font-semibold transition-all"
                        title="Reset Filter"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Daftar Kartu Homestay -->
    @if ($homestays->isEmpty())
        <div class="rounded-3xl border border-dashed border-stone-300 bg-white p-12 text-center shadow-xs">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-[#13391b] mb-4">
                <span class="material-symbols-outlined text-3xl">cottage</span>
            </div>
            <h3 class="text-lg font-bold text-stone-900">
                {{ $q || $status ? 'Tidak Ada Homestay yang Sesuai Filter' : 'Belum Ada Homestay yang Terdaftar' }}
            </h3>
            <p class="mt-1 text-xs sm:text-sm text-stone-500 max-w-md mx-auto leading-relaxed">
                {{ $q || $status ? 'Coba ubah kata kunci pencarian atau reset filter untuk melihat seluruh unit homestay Anda.' : 'Daftarkan rumah atau kamar warga Anda sekarang untuk diverifikasi oleh Pokdarwis desa wisata.' }}
            </p>
            <div class="mt-6 flex justify-center gap-3">
                @if ($q || $status)
                    <a
                        href="{{ route('pemilik.homestay.index') }}"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full border border-stone-200 text-stone-700 hover:bg-stone-50 text-xs font-semibold"
                    >
                        <span>Reset Filter</span>
                    </a>
                @else
                    <a
                        href="{{ route('pemilik.homestay.create') }}"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs hover:shadow-md transition-all"
                    >
                        <span class="material-symbols-outlined text-sm">add_home</span>
                        <span>Daftarkan Homestay Baru</span>
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($homestays as $homestay)
                <div class="flex flex-col overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-xs hover:shadow-md hover:border-emerald-300 transition-all">
                    <!-- Top Ribbon Header -->
                    <div class="relative bg-gradient-to-r from-[#0d2813] to-[#1a4a22] p-5 text-white">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-emerald-300">
                                <span class="material-symbols-outlined text-xs">location_on</span>
                                <span>Desa Wisata Pokdarwis</span>
                            </span>
                            <x-badge :status="$homestay->status_verifikasi->value" />
                        </div>
                        <h3 class="mt-2 text-xl font-extrabold tracking-tight text-white line-clamp-1">
                            {{ $homestay->nama_homestay }}
                        </h3>
                    </div>

                    <!-- Body Content -->
                    <div class="flex flex-1 flex-col p-5">
                        <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                            {{ $homestay->alamat }}
                        </p>

                        <!-- Room & Package Metrics -->
                        <div class="mt-4 grid grid-cols-2 gap-2 text-center">
                            <div class="rounded-2xl border border-stone-100 bg-stone-50/80 p-3">
                                <p class="text-2xl font-extrabold text-[#13391b]">{{ $homestay->kamar_count }}</p>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mt-0.5">Kamar Tersedia</p>
                            </div>
                            <div class="rounded-2xl border border-stone-100 bg-stone-50/80 p-3">
                                <p class="text-2xl font-extrabold text-[#ea580c]">{{ $homestay->paket_count }}</p>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mt-0.5">Paket Wisata</p>
                            </div>
                        </div>

                        <!-- Facilities Chips -->
                        @if ($homestay->fasilitas->isNotEmpty())
                            <div class="mt-4 flex flex-wrap gap-1.5">
                                @foreach ($homestay->fasilitas->take(4) as $facility)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-900 border border-emerald-100">
                                        <span class="material-symbols-outlined text-[11px] text-emerald-600">check</span>
                                        <span>{{ $facility->nama_fasilitas }}</span>
                                    </span>
                                @endforeach
                                @if ($homestay->fasilitas->count() > 4)
                                    <span class="rounded-full bg-stone-100 px-2 py-1 text-[10px] font-semibold text-stone-500">
                                        +{{ $homestay->fasilitas->count() - 4 }} lainnya
                                    </span>
                                @endif
                            </div>
                        @endif

                        <!-- Action Buttons Row -->
                        <div class="mt-auto pt-5 border-t border-stone-100 flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ route('pemilik.homestay.kamar.index', $homestay) }}"
                                    class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-2xs transition-all"
                                >
                                    <span class="material-symbols-outlined text-xs">bed</span>
                                    <span>Kamar</span>
                                </a>
                                <a
                                    href="{{ route('pemilik.homestay.paket.index', $homestay) }}"
                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
                                >
                                    <span class="material-symbols-outlined text-xs text-[#ea580c]">hiking</span>
                                    <span>Paket</span>
                                </a>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <a
                                    href="{{ route('pemilik.homestay.edit', $homestay) }}"
                                    class="p-2 rounded-xl border border-stone-200 text-stone-600 hover:text-stone-900 hover:bg-stone-50 transition-all"
                                    title="Ubah Data Homestay"
                                >
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>

                                @can('delete', $homestay)
                                    <form
                                        method="POST"
                                        action="{{ route('pemilik.homestay.destroy', $homestay) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus homestay ini? Seluruh data kamar dan paket terkait akan ikut terhapus. Tindakan ini tidak dapat dibatalkan.');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="p-2 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 transition-all cursor-pointer"
                                            title="Hapus Homestay"
                                        >
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            <x-pagination :paginator="$homestays" />
        </div>
    @endif
@endsection
