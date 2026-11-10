@extends('layouts.app')

@section('title', 'Verifikasi Kode OTP Email')

@section('content')
    <section class="relative overflow-hidden py-10 sm:py-16">
        <div class="pointer-events-none absolute -left-24 top-10 h-72 w-72 rounded-full bg-emerald-200/40 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-amber-200/30 blur-3xl" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-xl lg:grid-cols-[1.05fr_0.95fr]">
                <!-- Sisi Kiri: Form Verifikasi OTP -->
                <div class="p-6 sm:p-10 lg:p-12 flex flex-col justify-between">
                    <div>
                        <div class="mb-6">
                            <x-logo />
                        </div>

                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-bold uppercase tracking-wider mb-2">
                            <span class="material-symbols-outlined text-sm text-[#13391b]">mark_email_read</span>
                            <span>Verifikasi Akun Baru</span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">Periksa Gmail Anda</h1>
                        <p class="mt-2 text-sm leading-relaxed text-stone-600">
                            Kami telah mengirimkan 6 digit kode OTP rahasia ke alamat email:
                        </p>
                        <p class="mt-1 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-stone-100 font-semibold text-xs text-stone-800 border border-stone-200">
                            <span class="material-symbols-outlined text-sm text-[#ea580c]">mail</span>
                            <span>{{ $email }}</span>
                        </p>

                        @if(!empty($demoOtp))
                            <!-- Banner Demo Otomatis -->
                            <div class="mt-5 rounded-2xl bg-amber-50/90 border border-amber-200/80 p-3.5 text-xs text-amber-900">
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-amber-600 text-base shrink-0 mt-0.5">info</span>
                                    <div>
                                        <p class="font-bold">Mode Simulasi Ujian / Demo Lokal:</p>
                                        <p class="mt-0.5 text-amber-800">
                                            Kode OTP Anda adalah: <strong class="font-mono text-sm tracking-wider text-stone-900 bg-white px-2 py-0.5 rounded border border-amber-300">{{ $demoOtp }}</strong>
                                        </p>
                                        <p class="mt-1 text-[11px] text-amber-700/80">
                                            (Pesan ini juga otomatis tersimpan pada file <code class="font-mono">storage/logs/laravel.log</code>)
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.verify-otp') }}" class="mt-6 space-y-5">
                            @csrf

                            <div>
                                <label for="otp" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                    Masukkan 6 Digit Kode OTP
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        name="otp"
                                        id="otp"
                                        maxlength="6"
                                        value="{{ old('otp', $demoOtp ?? '') }}"
                                        required
                                        autofocus
                                        placeholder="······"
                                        class="w-full text-center tracking-[0.5em] text-2xl font-mono font-bold py-3 px-4 rounded-2xl border @error('otp') border-red-500 bg-red-50/20 @else border-stone-300 bg-white @enderror focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none transition-all placeholder:text-stone-300"
                                    />
                                </div>
                                @error('otp')
                                    <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">error</span>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-sm font-bold shadow-md hover:shadow-lg transition-all cursor-pointer"
                            >
                                <span class="material-symbols-outlined text-base">verified</span>
                                <span>Verifikasi & Masuk ke Dashboard</span>
                            </button>
                        </form>
                    </div>

                    <div class="mt-8 pt-4 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-stone-500">
                        <div>
                            Tidak menerima email?
                            <form method="POST" action="{{ route('register.resend-otp') }}" class="inline">
                                @csrf
                                <button type="submit" class="font-bold text-[#ea580c] hover:underline cursor-pointer">
                                    Kirim Ulang Kode
                                </button>
                            </form>
                        </div>
                        <a href="{{ route('register') }}" class="text-stone-500 hover:text-stone-800 transition-colors">
                            ← Ubah data pendaftaran
                        </a>
                    </div>
                </div>

                <!-- Sisi Kanan: Green Card with Subtle Rural Landscape Background -->
                <aside class="relative overflow-hidden bg-[#0d2813] p-6 text-white sm:p-10 lg:p-12 flex flex-col justify-between">
                    <!-- Subtle village background texture -->
                    <div class="absolute inset-0 z-0 pointer-events-none">
                        <img
                            src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=80"
                            alt="Desa Wisata"
                            class="h-full w-full object-cover object-center opacity-25 mix-blend-overlay filter contrast-125"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-[#091a0c] via-[#0d2813]/90 to-[#13391b]/95"></div>
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(34,197,94,0.18),transparent_65%)]"></div>
                    </div>

                    <div class="relative z-10">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white text-stone-900 text-xs font-bold shadow-md mb-6">
                            <span class="material-symbols-outlined text-sm text-[#1b4d24]">shield</span>
                            <span>Sistem Keamanan & Verifikasi Resmi</span>
                        </span>

                        <h2 class="text-2xl sm:text-3xl font-extrabold leading-snug tracking-tight text-white">
                            “Menjaga integritas data warga dan kenyamanan wisatawan.”
                        </h2>
                        <p class="mt-4 text-xs sm:text-sm text-stone-300 leading-relaxed">
                            Verifikasi email dengan kode OTP memastikan setiap akun yang terdaftar adalah akun riil yang dapat menerima e-tiket reservasi resmi, tanda terima pembayaran, dan informasi kontak tuan rumah desa wisata.
                        </p>

                        <!-- Poin Keamanan -->
                        <div class="mt-8 space-y-3">
                            <div class="flex items-start gap-3 rounded-xl bg-white/5 border border-white/10 p-3.5 backdrop-blur-xs">
                                <span class="material-symbols-outlined text-emerald-400 text-lg shrink-0 mt-0.5">check_circle</span>
                                <div>
                                    <p class="text-xs font-bold text-white">Konfirmasi Reservasi Instan</p>
                                    <p class="text-[11px] text-stone-300">E-tiket homestay dan bukti reservasi dikirimkan langsung ke Gmail Anda.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 rounded-xl bg-white/5 border border-white/10 p-3.5 backdrop-blur-xs">
                                <span class="material-symbols-outlined text-emerald-400 text-lg shrink-0 mt-0.5">lock</span>
                                <div>
                                    <p class="text-xs font-bold text-white">Perlindungan Anti Pembajakan</p>
                                    <p class="text-[11px] text-stone-300">Akun terikat kuat pada kepemilikan email yang valid tanpa risiko penyalahgunaan.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pokdarwis Footnote -->
                    <div class="relative z-10 mt-8 pt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-stone-400">
                        <span>Pokdarwis Mitra Nusantara</span>
                        <span>100% Aman & Terenkripsi</span>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
