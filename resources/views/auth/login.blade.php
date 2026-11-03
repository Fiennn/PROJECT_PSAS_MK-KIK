@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <section class="relative overflow-hidden py-10 sm:py-16">
        <div class="pointer-events-none absolute -left-24 top-10 h-72 w-72 rounded-full bg-forest-200/40 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-sand-300/50 blur-3xl" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid overflow-hidden rounded-4xl border border-sand-200 bg-white shadow-lift lg:grid-cols-[1.05fr_0.95fr]">
                <div class="p-6 sm:p-10 lg:p-12">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-clay-600">Selamat datang kembali</p>
                    <h1 class="mt-2 font-display text-3xl font-semibold text-forest-950 sm:text-4xl">Masuk ke HomeStay Go</h1>
                    <p class="mt-3 text-sm leading-relaxed text-stone-600">
                        Kelola perjalanan atau homestay Anda dalam satu tempat.
                    </p>

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                        @csrf

                        <x-form.input
                            name="username"
                            label="Username"
                            placeholder="Masukkan username"
                            autocomplete="username"
                            autofocus
                            required
                        />

                        <x-form.input
                            name="password"
                            type="password"
                            label="Password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        />

                        <div class="flex items-center justify-between gap-4 pt-1">
                            <p class="text-xs text-stone-500">Gunakan akun yang sudah terdaftar.</p>
                            <a href="{{ route('register') }}" class="text-xs font-bold text-forest-700 underline-offset-4 hover:underline">Buat akun baru</a>
                        </div>

                        <x-button type="submit" size="lg" class="w-full">Masuk</x-button>
                    </form>
                </div>

                <aside class="relative overflow-hidden bg-forest-900 p-6 text-sand-50 sm:p-10 lg:p-12">
                    <div class="absolute inset-0 bg-grain opacity-60" aria-hidden="true"></div>
                    <svg class="absolute -bottom-10 -right-10 h-64 w-64 text-white/10" viewBox="0 0 100 100" fill="currentColor" aria-hidden="true">
                        <path d="M50 92c18-3 31-15 36-32-17 4-31 14-36 32Zm0 0C32 89 19 77 14 60c17 4 31 14 36 32Zm0-10C33 74 25 58 30 39c11 12 19 25 20 43Zm0 0c17-8 25-24 20-43-11 12-19 25-20 43Z" />
                    </svg>

                    <div class="relative">
                        <p class="font-display text-2xl font-semibold leading-snug sm:text-3xl">
                            “Pulang ke desa, menemukan cerita yang tidak ada di buku panduan.”
                        </p>
                        <p class="mt-4 text-sm leading-relaxed text-sand-200/80">
                            Setiap homestay dikelola langsung oleh warga desa wisata. Setiap kunjungan Anda ikut menghidupkan ekonomi lokal.
                        </p>

                        <div class="mt-8 rounded-3xl border border-white/15 bg-white/10 p-5 backdrop-blur-sm">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-sand-300">Akun demo</p>
                            <div class="mt-3 space-y-2 text-xs text-sand-100/90">
                                <p class="flex justify-between gap-3"><span>Admin</span><span class="font-mono">admin / admin123</span></p>
                                <p class="flex justify-between gap-3"><span>Pemilik</span><span class="font-mono">pemilik1 / pemilik123</span></p>
                                <p class="flex justify-between gap-3"><span>Wisatawan</span><span class="font-mono">wisatawan1 / wisatawan123</span></p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
