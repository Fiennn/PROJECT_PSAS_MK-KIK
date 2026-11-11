@extends('layouts.dashboard')

@section('title', 'Ubah Data Kamar')
@section('page_title', 'Ubah Data: '.$kamar->nama_kamar)
@section('page_description', 'Perbarui kapasitas tamu, tarif per malam, atau status ketersediaan kamar.')

@section('page_actions')
    <a
        href="{{ route('pemilik.homestay.kamar.index', $kamar->homestay) }}"
        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
    >
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Kembali ke Daftar Kamar</span>
    </a>
@endsection

@section('dashboard')
    <div class="max-w-2xl">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-2 pb-4 border-b border-stone-100 mb-6">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-[#13391b]">
                    <span class="material-symbols-outlined text-lg">edit</span>
                </span>
                <div>
                    <h2 class="text-base font-extrabold text-stone-900">Formulir Perubahan Kamar</h2>
                    <p class="text-xs text-stone-500">Perubahan tarif tidak mempengaruhi pesanan yang sudah tercatat sebelumnya.</p>
                </div>
            </div>

            @include('pemilik.kamar._form', ['kamar' => $kamar, 'homestay' => $kamar->homestay])
        </div>
    </div>
@endsection
