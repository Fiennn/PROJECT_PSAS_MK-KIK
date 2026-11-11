@extends('layouts.dashboard')

@section('title', 'Manajemen & Pemantauan Homestay')
@section('page_title', 'Manajemen & Pemantauan Homestay')
@section('page_description', 'Kelola, pantau ketersediaan, ubah data seluruh homestay desa wisata, atau daftarkan homestay baru binaan Pokdarwis.')

@section('page_actions')
    <x-button :href="route('admin.homestay.create')" variant="primary">
        <span class="material-symbols-outlined text-sm">add_home</span>
        <span>+ Daftarkan Homestay Baru</span>
    </x-button>
@endsection

@section('dashboard')
    <!-- Monitoring KPI Stats Cards -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-6">
        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-stone-500">
                <span class="text-xs font-bold uppercase tracking-wider">Total Homestay</span>
                <span class="material-symbols-outlined text-stone-400 text-lg">domain</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-stone-900">{{ $stats['total'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Seluruh desa wisata mitra</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-emerald-600">
                <span class="text-xs font-bold uppercase tracking-wider">Disetujui</span>
                <span class="material-symbols-outlined text-emerald-500 text-lg">verified</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-[#13391b]">{{ $stats['disetujui'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Aktif & tampil di katalog publik</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-amber-600">
                <span class="text-xs font-bold uppercase tracking-wider">Menunggu</span>
                <span class="material-symbols-outlined text-amber-500 text-lg">pending</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-amber-700">{{ $stats['menunggu'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Pengajuan butuh verifikasi</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-rose-600">
                <span class="text-xs font-bold uppercase tracking-wider">Ditolak / Nonaktif</span>
                <span class="material-symbols-outlined text-rose-500 text-lg">cancel</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-stone-700">{{ $stats['ditolak'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Draf / perlu perbaikan data</p>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <x-card>
        <form method="GET" action="{{ route('admin.homestay.index') }}" class="grid gap-4 sm:grid-cols-[1.4fr_0.7fr_auto] sm:items-end">
            <div>
                <label for="q" class="label">Cari Homestay</label>
                <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama homestay, alamat, atau nama pemilik..." class="field">
            </div>
            <x-form.select name="status" label="Status Publikasi">
                <option value="">Semua status</option>
                <option value="disetujui" @selected(($filters['status'] ?? '') === 'disetujui')>Disetujui (Katalog Aktif)</option>
                <option value="menunggu" @selected(($filters['status'] ?? '') === 'menunggu')>Menunggu Verifikasi</option>
                <option value="ditolak" @selected(($filters['status'] ?? '') === 'ditolak')>Ditolak / Nonaktif</option>
            </x-form.select>
            <div class="flex gap-2">
                <x-button type="submit">Terapkan Filter</x-button>
                <x-button :href="route('admin.homestay.index')" variant="secondary">Reset</x-button>
            </div>
        </form>
    </x-card>

    @if ($homestays->isEmpty())
        <div class="mt-6">
            <x-empty-state title="Tidak Ada Homestay" description="Belum ada data homestay yang sesuai dengan kriteria pencarian Anda.">
                <div class="mt-4">
                    <x-button :href="route('admin.homestay.create')" variant="primary">
                        <span class="material-symbols-outlined text-sm">add</span>
                        <span>Tambah Homestay Sekarang</span>
                    </x-button>
                </div>
            </x-empty-state>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($homestays as $homestay)
                <x-card padding="p-0">
                    <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="font-display text-lg font-bold text-stone-900">{{ $homestay->nama_homestay }}</h2>
                                <x-badge :status="$homestay->status_verifikasi->value" />
                            </div>

                            <p class="mt-1 text-sm text-stone-500 leading-snug">{{ $homestay->alamat }}</p>

                            <div class="mt-2.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-stone-600">
                                <span class="flex items-center gap-1 font-semibold text-stone-800">
                                    <span class="material-symbols-outlined text-sm text-[#1b4d24]">person</span>
                                    <span>{{ $homestay->pemilik->nama_pemilik }} ({{ $homestay->pemilik->no_hp }})</span>
                                </span>
                                <span class="text-stone-300">•</span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-stone-400">bed</span>
                                    <span>{{ $homestay->kamar_count }} Kamar</span>
                                </span>
                                <span class="text-stone-300">•</span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-stone-400">local_activity</span>
                                    <span>{{ $homestay->paket_count }} Paket Wisata</span>
                                </span>
                            </div>

                            @if ($homestay->fasilitas->isNotEmpty())
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @foreach ($homestay->fasilitas->take(5) as $facility)
                                        <span class="rounded-full bg-stone-100 border border-stone-200 px-2.5 py-0.5 text-[10px] font-semibold text-stone-700">
                                            {{ $facility->nama_fasilitas }}
                                        </span>
                                    @endforeach
                                    @if ($homestay->fasilitas->count() > 5)
                                        <span class="rounded-full bg-stone-100 px-2 py-0.5 text-[10px] text-stone-400">
                                            +{{ $homestay->fasilitas->count() - 5 }} lainnya
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons: Pantau & Ubah -->
                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                            <a
                                href="{{ route('admin.homestay.show', $homestay) }}"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-bold transition-all shadow-2xs hover:border-stone-300"
                            >
                                <span class="material-symbols-outlined text-sm text-[#13391b]">visibility</span>
                                <span>Pantau / Detail</span>
                            </a>

                            <a
                                href="{{ route('admin.homestay.edit', $homestay) }}"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold transition-all shadow-xs"
                            >
                                <span class="material-symbols-outlined text-sm">edit</span>
                                <span>Ubah Data</span>
                            </a>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <x-pagination :paginator="$homestays" />
    @endif
@endsection
