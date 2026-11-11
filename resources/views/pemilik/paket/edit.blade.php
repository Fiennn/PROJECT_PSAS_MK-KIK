@extends('layouts.dashboard')

@section('title', 'Ubah Data Paket Wisata')
@section('page_title', 'Ubah Data: '.$paket->nama_paket)
@section('page_description', 'Perbarui detail aktivitas wisata, durasi waktu, atau tarif per peserta.')

@section('page_actions')
    <a
        href="{{ route('pemilik.homestay.paket.index', $paket->homestay) }}"
        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
    >
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Kembali ke Paket Wisata</span>
    </a>
@endsection

@section('dashboard')
    <div class="max-w-2xl">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-2 pb-4 border-b border-stone-100 mb-6">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-[#ea580c]">
                    <span class="material-symbols-outlined text-lg">edit</span>
                </span>
                <div>
                    <h2 class="text-base font-extrabold text-stone-900">Formulir Perubahan Paket Wisata</h2>
                    <p class="text-xs text-stone-500">Perubahan tarif tidak mempengaruhi pesanan paket yang sudah berlangsung sebelumnya.</p>
                </div>
            </div>

            @include('pemilik.paket._form', ['paket' => $paket, 'homestay' => $paket->homestay])
        </div>
    </div>
@endsection
