@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
    <section class="relative overflow-hidden py-10 sm:py-16">
        <div class="pointer-events-none absolute -right-24 top-10 h-72 w-72 rounded-full bg-sand-300/50 blur-3xl" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-clay-600">Mulai perjalanan Anda</p>
                <h1 class="mt-2 font-display text-3xl font-semibold text-forest-950 sm:text-4xl">Buat akun HomeStay Go</h1>
                <p class="mt-3 text-sm leading-relaxed text-stone-600">
                    Pilih peran Anda. Wisatawan dapat memesan homestay, pemilik dapat mengelola homestay dan paket wisata.
                </p>
            </div>

            <div class="mt-8 rounded-4xl border border-sand-200 bg-white p-6 shadow-lift sm:p-10">
                <form method="POST" action="{{ route('register') }}" class="space-y-6" x-data="{ role: '{{ old('role', 'wisatawan') }}' }">
                    @csrf

                    <fieldset>
                        <legend class="label">Saya mendaftar sebagai</legend>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label
                                class="relative cursor-pointer rounded-3xl border-2 p-4 transition"
                                :class="role === 'wisatawan' ? 'border-forest-600 bg-forest-50' : 'border-sand-200 bg-white hover:border-sand-300'"
                            >
                                <input type="radio" name="role" value="wisatawan" x-model="role" class="sr-only">
                                <span class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-forest-100 text-forest-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0" />
                                        </svg>
                                    </span>
                                    <span>
                                        <span class="block text-sm font-bold text-forest-950">Wisatawan</span>
                                        <span class="mt-1 block text-xs leading-relaxed text-stone-500">Cari dan pesan homestay di desa wisata.</span>
                                    </span>
                                </span>
                            </label>

                            <label
                                class="relative cursor-pointer rounded-3xl border-2 p-4 transition"
                                :class="role === 'pemilik' ? 'border-forest-600 bg-forest-50' : 'border-sand-200 bg-white hover:border-sand-300'"
                            >
                                <input type="radio" name="role" value="pemilik" x-model="role" class="sr-only">
                                <span class="flex items-start gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-clay-100 text-clay-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75 12 3l8.25 6.75v9a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-9Z" />
                                            <path stroke-linecap="round" d="M9 20.25v-6h6v6" />
                                        </svg>
                                    </span>
                                    <span>
                                        <span class="block text-sm font-bold text-forest-950">Pemilik Homestay</span>
                                        <span class="mt-1 block text-xs leading-relaxed text-stone-500">Kelola kamar, paket wisata, dan pesanan.</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        @error('role')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.input name="username" label="Username" placeholder="contoh: ayulestari" autocomplete="username" required />
                        <x-form.input name="email" type="email" label="Email" placeholder="nama@email.com" autocomplete="email" required />
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.input name="password" type="password" label="Password" placeholder="Minimal 8 karakter" autocomplete="new-password" required />
                        <x-form.input name="password_confirmation" type="password" label="Konfirmasi Password" placeholder="Ulangi password" autocomplete="new-password" required />
                    </div>

                    <div class="rounded-3xl bg-sand-50 p-5 sm:p-6">
                        <h2 class="font-display text-lg font-semibold text-forest-950">Data profil</h2>
                        <div class="mt-4 grid gap-5 sm:grid-cols-2">
                            <div x-show="role === 'wisatawan'" x-cloak class="sm:col-span-2">
                                <x-form.input name="nama_wisatawan" label="Nama lengkap" placeholder="Nama sesuai identitas" />
                            </div>

                            <div x-show="role === 'pemilik'" x-cloak class="sm:col-span-2">
                                <x-form.input name="nama_pemilik" label="Nama pemilik" placeholder="Nama pengelola homestay" />
                            </div>

                            <x-form.input name="no_hp" label="Nomor HP" placeholder="08xxxxxxxxxx" autocomplete="tel" required />

                            <div x-show="role === 'wisatawan'" x-cloak>
                                <x-form.input name="asal_kota" label="Asal kota" placeholder="contoh: Bandung" />
                            </div>

                            <div x-show="role === 'pemilik'" x-cloak>
                                <x-form.textarea name="alamat" label="Alamat" rows="3" placeholder="Alamat lengkap pemilik homestay" />
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 pt-2 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs leading-relaxed text-stone-500">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="font-bold text-forest-700 underline-offset-4 hover:underline">Masuk di sini</a>
                        </p>
                        <x-button type="submit" size="lg">Daftar Sekarang</x-button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
