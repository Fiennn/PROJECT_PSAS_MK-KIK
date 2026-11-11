@extends('layouts.dashboard')

@section('title', 'Kelola Fasilitas')
@section('page_title', 'Master Fasilitas Homestay')
@section('page_description', 'Kelola daftar fasilitas resmi desa wisata yang dapat dipilih pemilik saat mendaftarkan homestay.')

@section('dashboard')
    <div class="grid gap-6 lg:grid-cols-[1.25fr_0.75fr] lg:items-start">
        <!-- Kolom Kiri: Daftar Fasilitas -->
        <div class="space-y-4">
            @if ($fasilitas->isEmpty())
                <div class="rounded-3xl border border-dashed border-stone-300 bg-white p-10 text-center shadow-xs">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-[#13391b] mb-3">
                        <span class="material-symbols-outlined text-3xl">list_alt</span>
                    </div>
                    <h3 class="text-base font-bold text-stone-900">Belum Ada Fasilitas Terdaftar</h3>
                    <p class="mt-1 text-xs text-stone-500 max-w-sm mx-auto">
                        Gunakan formulir di samping untuk menambahkan fasilitas homestay pertama (contoh: WiFi, Air Panas, Dapur Bersama).
                    </p>
                </div>
            @else
                <div class="rounded-3xl border border-stone-200 bg-white shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-stone-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#13391b]">checklist</span>
                            <h3 class="text-sm font-extrabold text-stone-900">Daftar Fasilitas Resmi</h3>
                        </div>
                        <span class="text-xs font-bold text-stone-400">{{ $fasilitas->total() }} Jenis Terdaftar</span>
                    </div>

                    <ul class="divide-y divide-stone-100">
                        @foreach ($fasilitas as $item)
                            <li class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between hover:bg-stone-50/50 transition-colors">
                                <div class="min-w-0 flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-[#13391b]">
                                        <span class="material-symbols-outlined text-base">check</span>
                                    </span>
                                    <div>
                                        <p class="font-bold text-stone-900 text-xs sm:text-sm">{{ $item->nama_fasilitas }}</p>
                                        <p class="text-[11px] text-stone-400">
                                            Digunakan oleh <strong class="text-emerald-800">{{ $item->homestay_count }}</strong> unit homestay
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <form method="POST" action="{{ route('admin.fasilitas.update', $item) }}" class="flex items-center gap-1.5">
                                        @csrf
                                        @method('PATCH')
                                        <input
                                            type="text"
                                            name="nama_fasilitas"
                                            value="{{ old('nama_fasilitas', $item->nama_fasilitas) }}"
                                            class="w-36 sm:w-44 px-3 py-1.5 text-xs rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-1 focus:ring-[#13391b] outline-none"
                                            aria-label="Nama fasilitas {{ $item->nama_fasilitas }}"
                                            required
                                        >
                                        <button
                                            type="submit"
                                            class="px-3 py-1.5 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-bold shadow-2xs transition-all cursor-pointer"
                                        >
                                            Simpan
                                        </button>
                                    </form>

                                    @if ($item->homestay_count === 0)
                                        <x-confirm-button
                                            :action="route('admin.fasilitas.destroy', $item)"
                                            method="DELETE"
                                            label="Hapus"
                                            title="Hapus fasilitas {{ $item->nama_fasilitas }}?"
                                            message="Fasilitas yang belum digunakan homestay dapat dihapus secara aman."
                                            confirm-label="Ya, hapus"
                                        />
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-4">
                    <x-pagination :paginator="$fasilitas" />
                </div>
            @endif
        </div>

        <!-- Kolom Kanan: Tambah Fasilitas (Sticky) -->
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs lg:sticky lg:top-28">
            <div class="flex items-center gap-2 pb-3 border-b border-stone-100 mb-4">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-[#13391b]">
                    <span class="material-symbols-outlined text-base">add</span>
                </span>
                <div>
                    <h3 class="text-sm font-extrabold text-stone-900">Tambah Fasilitas Baru</h3>
                    <p class="text-[11px] text-stone-500">Nama fasilitas akan langsung muncul di form pengajuan homestay.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.fasilitas.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="nama_fasilitas" class="block text-xs font-bold text-stone-700 mb-1">Nama Fasilitas</label>
                    <input
                        id="nama_fasilitas"
                        type="text"
                        name="nama_fasilitas"
                        value="{{ old('nama_fasilitas') }}"
                        placeholder="Contoh: Sarapan Pagi Tradisional"
                        required
                        class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none"
                    >
                    @error('nama_fasilitas')
                        <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-4 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs hover:shadow-md transition-all cursor-pointer"
                >
                    <span class="material-symbols-outlined text-sm">add_circle</span>
                    <span>Tambah ke Master Data</span>
                </button>
            </form>

            <div class="mt-6 rounded-2xl bg-stone-50 border border-stone-200/80 p-3.5 text-[11px] text-stone-500 leading-relaxed">
                <p class="font-bold text-stone-700 mb-1">Catatan Penting:</p>
                Fasilitas yang sudah dipakai oleh satu atau lebih homestay tidak dapat dihapus untuk menjaga keutuhan katalog profil desa wisata.
            </div>
        </div>
    </div>
@endsection
