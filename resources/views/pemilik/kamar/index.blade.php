@extends('layouts.dashboard')

@section('title', 'Kamar: '.$homestay->nama_homestay)
@section('page_title', 'Kelola Kamar & Tarif')
@section('page_description', 'Atur daftar kamar, kapasitas tamu, dan harga per malam untuk '.$homestay->nama_homestay.'.')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a
            href="{{ route('pemilik.homestay.paket.index', $homestay) }}"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
        >
            <span class="material-symbols-outlined text-sm text-[#ea580c]">hiking</span>
            <span>Paket Wisata ({{ $homestay->paket()->count() }})</span>
        </a>
        <a
            href="{{ route('pemilik.homestay.index') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
        >
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali ke Homestay</span>
        </a>
    </div>
@endsection

@section('dashboard')
    <!-- Context Banner Homestay -->
    <div class="mb-6 rounded-2xl border border-stone-200 bg-white p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-[#13391b]">
                <span class="material-symbols-outlined text-2xl">cottage</span>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-base font-extrabold text-stone-900">{{ $homestay->nama_homestay }}</h2>
                    <x-badge :status="$homestay->status_verifikasi->value" />
                </div>
                <p class="mt-0.5 text-xs text-stone-500">{{ $homestay->alamat }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-stone-100 text-stone-700 text-xs font-bold">
                <span class="material-symbols-outlined text-sm text-stone-500">bed</span>
                <span>{{ $kamar->count() }} Kamar Didaftarkan</span>
            </span>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <!-- Daftar Kamar -->
        <div class="space-y-4">
            @if ($kamar->isEmpty())
                <div class="rounded-3xl border border-dashed border-stone-300 bg-white p-10 text-center shadow-xs">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-[#13391b] mb-3">
                        <span class="material-symbols-outlined text-3xl">bed</span>
                    </div>
                    <h3 class="text-base font-bold text-stone-900">Belum Ada Kamar yang Ditambahkan</h3>
                    <p class="mt-1 text-xs text-stone-500 max-w-sm mx-auto leading-relaxed">
                        Tambahkan kamar pertama menggunakan formulir di samping agar homestay Anda dapat dipesan oleh wisatawan desa.
                    </p>
                </div>
            @else
                @foreach ($kamar as $room)
                    <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-xs hover:border-emerald-300 transition-all flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-extrabold text-stone-900 truncate">{{ $room->nama_kamar }}</h3>
                                <x-badge :status="$room->status->value" />
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-stone-500">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-stone-100 text-stone-700 font-semibold">
                                    <span class="material-symbols-outlined text-xs">group</span>
                                    <span>Kapasitas: {{ $room->kapasitas }} Orang</span>
                                </span>
                                <span>&bull;</span>
                                <span class="text-xs text-stone-400">ID #{{ $room->id_kamar }}</span>
                            </div>

                            <p class="mt-3 text-lg sm:text-xl font-extrabold text-[#13391b]">
                                {{ rupiah($room->harga_per_malam) }}
                                <span class="text-xs font-normal text-stone-500">/ malam</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 pt-3 sm:pt-0 border-t sm:border-0 border-stone-100">
                            <a
                                href="{{ route('pemilik.kamar.edit', $room) }}"
                                class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
                            >
                                <span class="material-symbols-outlined text-xs">edit</span>
                                <span>Ubah</span>
                            </a>

                            @can('delete', $room)
                                <form
                                    method="POST"
                                    action="{{ route('pemilik.kamar.destroy', $room) }}"
                                    onsubmit="return confirm('Hapus kamar {{ $room->nama_kamar }}? Kamar hanya dapat dihapus jika belum memiliki riwayat reservasi.');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-2 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 transition-all cursor-pointer"
                                        title="Hapus Kamar"
                                    >
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Formulir Tambah Kamar Baru (Sticky) -->
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs lg:sticky lg:top-28">
            <div class="flex items-center gap-2 pb-3 border-b border-stone-100 mb-4">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-[#13391b]">
                    <span class="material-symbols-outlined text-base">add</span>
                </span>
                <div>
                    <h3 class="text-sm font-extrabold text-stone-900">Tambah Kamar Baru</h3>
                    <p class="text-[11px] text-stone-500">Kamar langsung aktif jika statusnya Tersedia.</p>
                </div>
            </div>

            @include('pemilik.kamar._form', ['kamar' => null, 'homestay' => $homestay])
        </div>
    </div>
@endsection
