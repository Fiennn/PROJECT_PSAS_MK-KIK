@extends('layouts.dashboard')

@section('title', 'Tambah Homestay')
@section('page_title', 'Tambah Homestay')
@section('page_description', 'Lengkapi data homestay Anda. Pengajuan akan ditinjau oleh admin desa wisata.')

@section('page_actions')
    <x-button :href="route('pemilik.homestay.index')" variant="secondary">Kembali</x-button>
@endsection

@section('dashboard')
    <div class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr] lg:items-start">
        <div>
            @include('pemilik.homestay._form', ['homestay' => null, 'fasilitas' => $fasilitas, 'selectedFacilities' => []])
        </div>

        <x-card class="!bg-forest-900 !border-forest-800 text-sand-50">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-sand-300">Sebelum mengajukan</p>
            <ul class="mt-4 space-y-3 text-sm leading-relaxed text-sand-100/85">
                <li class="flex gap-3">
                    <span class="text-sand-400" aria-hidden="true">01</span>
                    Pastikan nama dan alamat homestay sesuai dengan lokasi asli di desa wisata.
                </li>
                <li class="flex gap-3">
                    <span class="text-sand-400" aria-hidden="true">02</span>
                    Tulis deskripsi yang jujur agar tamu tahu suasana yang akan mereka dapatkan.
                </li>
                <li class="flex gap-3">
                    <span class="text-sand-400" aria-hidden="true">03</span>
                    Setelah disetujui, tambahkan kamar dan paket wisata agar dapat dipesan.
                </li>
            </ul>
            <p class="mt-5 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-xs leading-relaxed text-sand-200/75">
                Homestay baru berstatus <span class="font-semibold text-white">Menunggu Verifikasi</span> sampai admin desa menyetujuinya.
            </p>
        </x-card>
    </div>
@endsection
