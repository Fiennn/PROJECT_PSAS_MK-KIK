@extends('layouts.app')

@section('title', 'Katalog Homestay')
@section('meta_description', 'Cari dan pesan homestay terverifikasi di desa wisata binaan Pokdarwis berdasarkan nama, kota destinasi, harga, kapasitas, dan fasilitas.')

@section('content')
    <!-- Top Header Banner -->
    <section class="border-b border-stone-200/80 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-100 text-[#13391b] text-xs font-semibold mb-3">
                    <span class="material-symbols-outlined text-sm text-[#ea580c]">explore</span>
                    <span>Jelajahi Desa Wisata Mitra Pokdarwis</span>
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight leading-tight">
                    Temukan Homestay Asri Pilihan Warga Lokal
                </h1>
                <p class="mt-3 text-sm sm:text-base text-stone-600 leading-relaxed">
                    Setiap homestay telah melewati kurasi dan verifikasi resmi pengelola desa wisata. Reservasi dengan kalender anti-double booking dan 100% mendukung kesejahteraan keluarga di desa.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content: Filter & Grid -->
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('homestay.index') }}" class="rounded-3xl border border-stone-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="grid gap-4 lg:grid-cols-[1.2fr_0.9fr_0.9fr_0.8fr_auto] lg:items-end">
                <!-- Kata Kunci -->
                <div>
                    <label for="q" class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1.5">Nama / Kata Kunci</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-stone-400 text-lg">search</span>
                        <input
                            id="q"
                            type="text"
                            name="q"
                            value="{{ $filters['q'] ?? '' }}"
                            placeholder="Cari nama homestay atau daerah..."
                            class="w-full rounded-2xl border border-stone-200 bg-[#faf9f5] pl-10 pr-4 py-2.5 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#13391b] focus:outline-none focus:ring-2 focus:ring-emerald-200"
                        />
                    </div>
                </div>

                <!-- Kota / Daerah -->
                <div>
                    <label for="kota" class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1.5">Destinasi / Daerah</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-[#1b4d24] text-lg">location_on</span>
                        <select
                            id="kota"
                            name="kota"
                            class="w-full rounded-2xl border border-stone-200 bg-[#faf9f5] pl-10 pr-4 py-2.5 text-sm font-semibold text-stone-900 focus:border-[#13391b] focus:outline-none focus:ring-2 focus:ring-emerald-200 cursor-pointer"
                        >
                            <option value="">Semua Daerah</option>
                            <option value="Banyumas" @selected(($filters['kota'] ?? '') === 'Banyumas')>Banyumas / Baturraden</option>
                            <option value="Bali" @selected(($filters['kota'] ?? '') === 'Bali')>Bali (Penglipuran &amp; Jatiluwih)</option>
                            <option value="Yogyakarta" @selected(($filters['kota'] ?? '') === 'Yogyakarta')>Yogyakarta (Menoreh)</option>
                            <option value="Bandung" @selected(($filters['kota'] ?? '') === 'Bandung')>Bandung (Pangalengan)</option>
                            <option value="Lombok" @selected(($filters['kota'] ?? '') === 'Lombok')>Lombok (Sade &amp; Sembalun)</option>
                        </select>
                    </div>
                </div>

                <!-- Harga Max -->
                <div>
                    <label for="harga_max" class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1.5">Harga Maksimal / Malam</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-stone-400 text-lg">payments</span>
                        <input
                            id="harga_max"
                            type="number"
                            min="0"
                            step="50000"
                            name="harga_max"
                            value="{{ $filters['harga_max'] ?? '' }}"
                            placeholder="Contoh: 300000"
                            class="w-full rounded-2xl border border-stone-200 bg-[#faf9f5] pl-10 pr-4 py-2.5 text-sm text-stone-900 placeholder:text-stone-400 focus:border-[#13391b] focus:outline-none focus:ring-2 focus:ring-emerald-200"
                        />
                    </div>
                </div>

                <!-- Kapasitas Tamu -->
                <div>
                    <label for="kapasitas" class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1.5">Kapasitas</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-stone-400 text-lg">group</span>
                        <select
                            id="kapasitas"
                            name="kapasitas"
                            class="w-full rounded-2xl border border-stone-200 bg-[#faf9f5] pl-10 pr-4 py-2.5 text-sm font-semibold text-stone-900 focus:border-[#13391b] focus:outline-none focus:ring-2 focus:ring-emerald-200 cursor-pointer"
                        >
                            <option value="">Semua Kapasitas</option>
                            @foreach ([1, 2, 3, 4] as $capacity)
                                <option value="{{ $capacity }}" @selected((int) ($filters['kapasitas'] ?? 0) === $capacity)>{{ $capacity }} Tamu+</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 lg:pb-0.5">
                    <button
                        type="submit"
                        class="flex-1 lg:flex-none inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-sm">tune</span>
                        <span>Terapkan</span>
                    </button>
                    <a
                        href="{{ route('homestay.index') }}"
                        class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-white border border-stone-200 hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-xs transition-colors cursor-pointer"
                    >
                        Reset
                    </a>
                </div>
            </div>

            <!-- Fasilitas Checkboxes -->
            <fieldset class="mt-5 border-t border-stone-100 pt-4">
                <legend class="text-xs font-bold text-stone-600 uppercase tracking-wider mb-2">Fasilitas Diinginkan</legend>
                <div class="flex flex-wrap gap-x-4 gap-y-2">
                    @foreach ($facilities as $facility)
                        <label class="inline-flex cursor-pointer items-center gap-1.5 text-xs text-stone-700 hover:text-stone-900">
                            <input
                                type="checkbox"
                                name="fasilitas[]"
                                value="{{ $facility->id_fasilitas }}"
                                @checked(in_array($facility->id_fasilitas, $selectedFacilities, true))
                                class="h-4 w-4 rounded border-stone-300 text-[#13391b] focus:ring-emerald-300 cursor-pointer"
                            />
                            <span>{{ $facility->nama_fasilitas }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>
        </form>

        <!-- Search Result Header -->
        <div class="mt-8 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-[#ea580c]">Hasil Pencarian</p>
                <h2 class="mt-0.5 text-2xl font-extrabold text-stone-900">
                    {{ $homestays->total() }} Homestay Ditemukan
                </h2>
            </div>
            @if ($homestays->total() > 0)
                <p class="text-xs text-stone-500 font-medium">Halaman {{ $homestays->currentPage() }} dari {{ $homestays->lastPage() }}</p>
            @endif
        </div>

        <!-- Cards Grid -->
        @if ($homestays->isEmpty())
            <div class="mt-8 rounded-3xl border border-stone-200 bg-white p-12 text-center shadow-xs">
                <div class="w-14 h-14 mx-auto rounded-full bg-stone-100 flex items-center justify-center text-stone-400 mb-3">
                    <span class="material-symbols-outlined text-3xl">home_work</span>
                </div>
                <h3 class="text-lg font-bold text-stone-900">Belum ada homestay yang cocok</h3>
                <p class="mt-1 text-sm text-stone-500 max-w-md mx-auto">
                    Coba longgarkan filter harga, kapasitas, atau fasilitas. Anda juga bisa mencari dengan kata kunci lain.
                </p>
                <div class="mt-5">
                    <a href="{{ route('homestay.index') }}" class="inline-flex items-center gap-1 px-5 py-2.5 rounded-full bg-[#13391b] text-white text-xs font-bold shadow-xs hover:bg-[#0e2c14]">
                        Hapus Semua Filter
                    </a>
                </div>
            </div>
        @else
            <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($homestays as $homestay)
                    <x-homestay-card :homestay="$homestay" />
                @endforeach
            </div>

            <div class="mt-8">
                <x-pagination :paginator="$homestays" />
            </div>
        @endif
    </section>
@endsection
