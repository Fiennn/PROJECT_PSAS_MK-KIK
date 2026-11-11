@extends('layouts.dashboard')

@section('title', 'Daftarkan Homestay Baru')
@section('page_title', 'Daftarkan Homestay Baru')
@section('page_description', 'Lengkapi profil homestay warga desa Anda. Pengajuan akan ditinjau dan diverifikasi oleh tim Pokdarwis.')

@section('page_actions')
    <a
        href="{{ route('pemilik.homestay.index') }}"
        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
    >
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Kembali ke Homestay Saya</span>
    </a>
@endsection

@section('dashboard')
    <div class="grid gap-6 lg:grid-cols-[1.45fr_0.55fr] lg:items-start">
        <div>
            @include('pemilik.homestay._form', ['homestay' => null, 'fasilitas' => $fasilitas, 'selectedFacilities' => []])
        </div>

        <!-- Sidebar Panduan Pokdarwis -->
        <div class="rounded-3xl bg-gradient-to-br from-[#0d2813] via-[#13391b] to-[#1e5429] p-6 sm:p-7 text-white shadow-md border border-white/10 lg:sticky lg:top-28">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/15 text-emerald-300">
                    <span class="material-symbols-outlined text-base">verified</span>
                </span>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-200">Panduan Pokdarwis</p>
            </div>

            <ul class="space-y-3.5 text-xs text-stone-200 leading-relaxed">
                <li class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/20 text-[10px] font-bold text-white mt-0.5">1</span>
                    <span>Pastikan alamat lengkap dan nomor patokan rumah jelas agar memudahkan tamu saat tiba di desa.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/20 text-[10px] font-bold text-white mt-0.5">2</span>
                    <span>Jelaskan keunikan rumah warga Anda: arsitektur lokal, pemandangan kebun, atau sarapan khas desa.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/20 text-[10px] font-bold text-white mt-0.5">3</span>
                    <span>Setelah diajukan, status homestay adalah <strong class="text-amber-300">Menunggu Verifikasi</strong>. Setelah disetujui, Anda dapat menambahkan kamar dan paket wisata.</span>
                </li>
            </ul>

            <div class="mt-6 rounded-2xl bg-white/10 border border-white/15 p-4 text-[11px] text-stone-200 flex items-start gap-2">
                <span class="material-symbols-outlined text-amber-400 text-base shrink-0 mt-0.5">info</span>
                <span>Homestay yang telah disetujui akan langsung tampil di halaman katalog utama wisatawan.</span>
            </div>
        </div>
    </div>
@endsection
