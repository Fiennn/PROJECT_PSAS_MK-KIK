@extends('layouts.app')

@section('title', 'Sesi Halaman Kedaluwarsa (419)')

@section('content')
<section class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center bg-white p-8 sm:p-10 rounded-3xl border border-stone-200 shadow-xl relative overflow-hidden">
        <div class="pointer-events-none absolute -left-12 -top-12 h-36 w-36 rounded-full bg-emerald-100/50 blur-2xl"></div>
        <div class="pointer-events-none absolute -right-12 -bottom-12 h-36 w-36 rounded-full bg-amber-100/50 blur-2xl"></div>

        <div class="relative z-10">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-50 text-[#ea580c] mb-5 border border-amber-200">
                <span class="material-symbols-outlined text-3xl">history</span>
            </div>

            <span class="inline-block px-3 py-1 rounded-full bg-amber-100 text-[#ea580c] text-xs font-bold uppercase tracking-wider mb-2">
                Kode 419 &bull; Page Expired
            </span>

            <h1 class="text-2xl font-extrabold text-stone-900 tracking-tight">Sesi Form Telah Berakhir</h1>
            <p class="mt-3 text-sm text-stone-600 leading-relaxed">
                Halaman ini dibiarkan terbuka terlalu lama tanpa aktivitas sehingga token keamanan (CSRF) kedaluwarsa demi melindungi keamanan akun Anda.
            </p>

            <div class="mt-6 flex flex-col gap-3">
                <a
                    href="{{ url()->previous() ?: route('login') }}"
                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-sm font-bold shadow-xs hover:shadow-md transition-all cursor-pointer"
                >
                    <span class="material-symbols-outlined text-base">refresh</span>
                    <span>Muat Ulang & Coba Lagi</span>
                </a>

                <a
                    href="{{ route('home') }}"
                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-full border border-stone-200 text-stone-600 hover:bg-stone-50 text-xs font-semibold transition-all"
                >
                    <span class="material-symbols-outlined text-sm">home</span>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
