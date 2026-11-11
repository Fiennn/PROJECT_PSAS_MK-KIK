@extends('layouts.dashboard')

@section('title', 'Paket Wisata: '.$homestay->nama_homestay)
@section('page_title', 'Kelola Paket Wisata')
@section('page_description', 'Tawarkan pengalaman budaya, kuliner, dan tur alam khas desa untuk melengkapi liburan tamu di '.$homestay->nama_homestay.'.')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a
            href="{{ route('pemilik.homestay.kamar.index', $homestay) }}"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
        >
            <span class="material-symbols-outlined text-sm text-[#13391b]">bed</span>
            <span>Kamar & Tarif ({{ $homestay->kamar()->count() }})</span>
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
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-[#ea580c]">
                <span class="material-symbols-outlined text-2xl">hiking</span>
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
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-amber-50 text-[#ea580c] text-xs font-bold border border-amber-100">
                <span class="material-symbols-outlined text-sm">local_activity</span>
                <span>{{ $paket->count() }} Paket Wisata Aktif</span>
            </span>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <!-- Daftar Paket Wisata -->
        <div class="space-y-4">
            @if ($paket->isEmpty())
                <div class="rounded-3xl border border-dashed border-stone-300 bg-white p-10 text-center shadow-xs">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-[#ea580c] mb-3">
                        <span class="material-symbols-outlined text-3xl">hiking</span>
                    </div>
                    <h3 class="text-base font-bold text-stone-900">Belum Ada Paket Wisata</h3>
                    <p class="mt-1 text-xs text-stone-500 max-w-sm mx-auto leading-relaxed">
                        Paket wisata bersifat opsional, namun sangat disukai wisatawan untuk merasakan kegiatan lokal khas desa seperti bertani, bersepeda sawah, atau kelas kuliner.
                    </p>
                </div>
            @else
                @foreach ($paket as $item)
                    <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-xs hover:border-amber-300 transition-all flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-extrabold text-stone-900 truncate">{{ $item->nama_paket }}</h3>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-[#ea580c] text-[11px] font-bold border border-amber-200/60">
                                    <span class="material-symbols-outlined text-xs">schedule</span>
                                    <span>{{ $item->durasi }}</span>
                                </span>
                            </div>

                            <p class="mt-2 text-xs text-stone-600 leading-relaxed">
                                {{ $item->deskripsi }}
                            </p>

                            <p class="mt-3 text-lg font-extrabold text-[#13391b]">
                                {{ rupiah($item->harga) }}
                                <span class="text-xs font-normal text-stone-500">/ orang peserta</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 pt-3 sm:pt-0 border-t sm:border-0 border-stone-100">
                            <a
                                href="{{ route('pemilik.paket.edit', $item) }}"
                                class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
                            >
                                <span class="material-symbols-outlined text-xs">edit</span>
                                <span>Ubah</span>
                            </a>

                            @can('delete', $item)
                                <form
                                    method="POST"
                                    action="{{ route('pemilik.paket.destroy', $item) }}"
                                    onsubmit="return confirm('Hapus paket wisata {{ $item->nama_paket }}? Paket yang pernah dipesan tidak dapat dihapus demi keutuhan riwayat transaksi.');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-2 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 transition-all cursor-pointer"
                                        title="Hapus Paket Wisata"
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

        <!-- Formulir Tambah Paket Baru (Sticky) -->
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs lg:sticky lg:top-28">
            <div class="flex items-center gap-2 pb-3 border-b border-stone-100 mb-4">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-[#ea580c]">
                    <span class="material-symbols-outlined text-base">add</span>
                </span>
                <div>
                    <h3 class="text-sm font-extrabold text-stone-900">Tambah Paket Wisata</h3>
                    <p class="text-[11px] text-stone-500">Wisatawan dapat memilih paket ini saat reservasi kamar.</p>
                </div>
            </div>

            @include('pemilik.paket._form', ['paket' => null, 'homestay' => $homestay])
        </div>
    </div>
@endsection
