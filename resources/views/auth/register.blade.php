@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
    <section class="relative overflow-hidden py-10 sm:py-16">
        <div class="pointer-events-none absolute -left-24 top-10 h-72 w-72 rounded-full bg-emerald-200/40 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-amber-200/30 blur-3xl" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-xl lg:grid-cols-[1.1fr_0.9fr]">
                <!-- Sisi Kiri: Form Registrasi Lengkap -->
                <div class="p-6 sm:p-10 lg:p-12">
                    <div class="mb-6">
                        <x-logo />
                    </div>

                    <p class="text-xs font-bold uppercase tracking-wider text-[#ea580c]">Mulai Perjalanan Anda</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">Buat Akun HomeStay Go</h1>
                    <p class="mt-2 text-sm leading-relaxed text-stone-500">
                        Pilih peran akun Anda untuk memulai. Verifikasi keamanan akun menggunakan kode OTP yang dikirim langsung ke Gmail Anda.
                    </p>

                    <form method="POST" action="{{ route('register') }}" class="mt-7 space-y-5" x-data="{ role: '{{ old('role', 'wisatawan') }}' }">
                        @csrf

                        <!-- Pilihan Peran Akun (Wisatawan vs Pemilik) -->
                        <fieldset>
                            <legend class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Pilih Peran Akun</legend>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label
                                    class="relative cursor-pointer rounded-2xl border-2 p-3.5 transition-all"
                                    :class="role === 'wisatawan' ? 'border-[#13391b] bg-emerald-50/60 shadow-xs' : 'border-stone-200 bg-white hover:border-stone-300'"
                                >
                                    <input type="radio" name="role" value="wisatawan" x-model="role" class="sr-only">
                                    <span class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-[#13391b]">
                                            <span class="material-symbols-outlined text-lg">luggage</span>
                                        </span>
                                        <span>
                                            <span class="block text-xs font-bold text-stone-900">Wisatawan</span>
                                            <span class="block text-[11px] text-stone-500 leading-tight">Cari & reservasi homestay desa</span>
                                        </span>
                                    </span>
                                </label>

                                <label
                                    class="relative cursor-pointer rounded-2xl border-2 p-3.5 transition-all"
                                    :class="role === 'pemilik' ? 'border-[#13391b] bg-emerald-50/60 shadow-xs' : 'border-stone-200 bg-white hover:border-stone-300'"
                                >
                                    <input type="radio" name="role" value="pemilik" x-model="role" class="sr-only">
                                    <span class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-[#ea580c]">
                                            <span class="material-symbols-outlined text-lg">cabin</span>
                                        </span>
                                        <span>
                                            <span class="block text-xs font-bold text-stone-900">Pemilik Homestay</span>
                                            <span class="block text-[11px] text-stone-500 leading-tight">Kelola kamar & paket wisata</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            @error('role')
                                <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <!-- Identitas Login: Username & Gmail -->
                        <div class="grid gap-3 sm:grid-cols-2">
                            <x-form.input
                                name="username"
                                label="Username"
                                placeholder="contoh: ayulestari"
                                autocomplete="username"
                                required
                            />
                            <x-form.input
                                name="email"
                                type="email"
                                label="Alamat Email (Gmail)"
                                placeholder="nama@gmail.com"
                                autocomplete="email"
                                required
                            />
                        </div>

                        <!-- Kata Sandi -->
                        <div class="grid gap-3 sm:grid-cols-2">
                            <x-form.input
                                name="password"
                                type="password"
                                label="Password"
                                placeholder="Minimal 8 karakter"
                                autocomplete="new-password"
                                required
                            />
                            <x-form.input
                                name="password_confirmation"
                                type="password"
                                label="Konfirmasi Password"
                                placeholder="Ulangi kata sandi"
                                autocomplete="new-password"
                                required
                            />
                        </div>

                        <!-- Data Profil Khusus Role -->
                        <div class="rounded-2xl bg-stone-50/80 border border-stone-200/80 p-4 sm:p-5">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-xs font-bold uppercase tracking-wider text-stone-700">Informasi Identitas & Kontak</h2>
                                <span class="text-[11px] font-semibold text-[#13391b] bg-emerald-100/70 px-2 py-0.5 rounded-full" x-text="role === 'wisatawan' ? 'Profil Wisatawan' : 'Profil Pengelola Homestay'"></span>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div x-show="role === 'wisatawan'" x-cloak class="sm:col-span-2">
                                    <x-form.input
                                        name="nama_wisatawan"
                                        label="Nama Lengkap"
                                        placeholder="Nama sesuai identitas KTP"
                                    />
                                </div>

                                <div x-show="role === 'pemilik'" x-cloak class="sm:col-span-2">
                                    <x-form.input
                                        name="nama_pemilik"
                                        label="Nama Pemilik Homestay"
                                        placeholder="Nama pengelola homestay warga"
                                    />
                                </div>

                                <div class="sm:col-span-1">
                                    <x-form.input
                                        name="no_hp"
                                        label="Nomor WhatsApp / HP"
                                        placeholder="08xxxxxxxxxx"
                                        autocomplete="tel"
                                        required
                                    />
                                </div>

                                <div x-show="role === 'wisatawan'" x-cloak class="sm:col-span-1">
                                    <x-form.input
                                        name="asal_kota"
                                        label="Kota Asal"
                                        placeholder="contoh: Banyumas / Jakarta"
                                    />
                                </div>

                                <div x-show="role === 'pemilik'" x-cloak class="sm:col-span-2">
                                    <x-form.textarea
                                        name="alamat"
                                        label="Alamat Lengkap Homestay"
                                        rows="2"
                                        placeholder="Dusun, RT/RW, Desa Wisata..."
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Verifikasi OTP Email -->
                        <div class="rounded-xl bg-emerald-50/70 border border-emerald-200/80 p-3 text-xs text-emerald-900 flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-emerald-700 text-lg shrink-0 mt-0.5">verified_user</span>
                            <div class="leading-relaxed">
                                <p class="font-bold text-emerald-950">Verifikasi OTP Gmail Otomatis</p>
                                <p class="text-[11px] text-emerald-800">
                                    Setelah menekan tombol daftar, sistem akan mengirimkan <strong>6 digit kode OTP rahasia</strong> ke alamat Gmail Anda untuk aktivasi akun instan.
                                </p>
                            </div>
                        </div>

                        <!-- Tombol Submit & Switch ke Login -->
                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-sm font-bold shadow-xs hover:shadow-md transition-all cursor-pointer"
                            >
                                <span class="material-symbols-outlined text-base">outgoing_mail</span>
                                <span>Daftar & Kirim Kode OTP Gmail</span>
                            </button>

                            <div class="mt-4 flex items-center justify-center gap-1.5 text-xs text-stone-500">
                                <span>Sudah memiliki akun terdaftar?</span>
                                <a href="{{ route('login') }}" class="font-bold text-[#13391b] hover:text-[#ea580c] transition-colors">
                                    Masuk Sekarang
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sisi Kanan: Green Card with Subtle Rural Landscape Background & Registration Perks -->
                <aside class="relative overflow-hidden bg-[#0d2813] p-6 text-white sm:p-10 lg:p-12 flex flex-col justify-between">
                    <!-- Subtle village background texture -->
                    <div class="absolute inset-0 z-0 pointer-events-none">
                        <img
                            src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=80"
                            alt="Suasana Desa Wisata"
                            class="h-full w-full object-cover object-center opacity-25 mix-blend-overlay filter contrast-125"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-[#091a0c] via-[#0d2813]/90 to-[#13391b]/95"></div>
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(34,197,94,0.18),transparent_65%)]"></div>
                    </div>

                    <div class="relative z-10">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white text-stone-900 text-xs font-bold shadow-md mb-6">
                            <span class="material-symbols-outlined text-sm text-[#1b4d24]">hotel_class</span>
                            <span>Platform Resmi Desa Wisata Pokdarwis</span>
                        </span>

                        <h2 class="text-2xl sm:text-3xl font-extrabold leading-snug tracking-tight text-white">
                            “Merawat kehangatan desa, menyambut setiap tamu nusantara.”
                        </h2>
                        <p class="mt-4 text-xs sm:text-sm text-stone-300 leading-relaxed">
                            Bergabunglah dalam ekosistem pariwisata berkelanjutan yang memberdayakan warga lokal desa binaan Pokdarwis secara langsung dan transparan.
                        </p>

                        <!-- Manfaat Bergabung -->
                        <div class="mt-8 space-y-3.5">
                            <div class="flex items-start gap-3 rounded-2xl bg-white/5 border border-white/10 p-3.5 backdrop-blur-xs">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-400/20 text-emerald-300">
                                    <span class="material-symbols-outlined text-base">favorite</span>
                                </span>
                                <div>
                                    <h4 class="text-xs font-bold text-white">100% Pemberdayaan Warga Lokal</h4>
                                    <p class="text-[11px] text-stone-300 leading-relaxed mt-0.5">Biaya menginap disalurkan langsung ke pemilik homestay dan dana kas konservasi desa wisata.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 rounded-2xl bg-white/5 border border-white/10 p-3.5 backdrop-blur-xs">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-400/20 text-amber-300">
                                    <span class="material-symbols-outlined text-base">event_available</span>
                                </span>
                                <div>
                                    <h4 class="text-xs font-bold text-white">Bebas Double Booking Realtime</h4>
                                    <p class="text-[11px] text-stone-300 leading-relaxed mt-0.5">Sistem reservasi berbasis kalender otomatis mencegah bentrok jadwal booking kamar.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 rounded-2xl bg-white/5 border border-white/10 p-3.5 backdrop-blur-xs">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-sky-400/20 text-sky-300">
                                    <span class="material-symbols-outlined text-base">verified</span>
                                </span>
                                <div>
                                    <h4 class="text-xs font-bold text-white">Verifikasi OTP Gmail & E-Tiket Instan</h4>
                                    <p class="text-[11px] text-stone-300 leading-relaxed mt-0.5">Setiap booking menghasilkan e-tiket resmi lengkap dengan kontak darurat tuan rumah.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Pokdarwis -->
                    <div class="relative z-10 mt-8 pt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-stone-400">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-emerald-400 text-xs">shield_check</span>
                            Data Terenkripsi SSL
                        </span>
                        <span>Pokdarwis Nusantara Binaan</span>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
