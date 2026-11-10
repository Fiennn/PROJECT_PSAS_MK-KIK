@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <section class="relative overflow-hidden py-10 sm:py-16" x-data="{
        showWaModal: false,
        showSocialModal: false,
        socialProvider: '',
        waPhone: '081234567890',
        waOtpSent: false,
        waOtpCode: '',
        waCountdown: 60,
        waTimer: null,
        sendWaOtp() {
            if (!this.waPhone) return;
            this.waOtpSent = true;
            this.waOtpCode = '882190';
            this.waCountdown = 60;
            if (this.waTimer) clearInterval(this.waTimer);
            this.waTimer = setInterval(() => {
                if (this.waCountdown > 0) {
                    this.waCountdown--;
                } else {
                    clearInterval(this.waTimer);
                }
            }, 1000);
        },
        openSocial(provider) {
            this.socialProvider = provider;
            this.showSocialModal = true;
        }
    }">
        <div class="pointer-events-none absolute -left-24 top-10 h-72 w-72 rounded-full bg-emerald-200/40 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-amber-200/30 blur-3xl" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-xl lg:grid-cols-[1.05fr_0.95fr]">
                <!-- Form Login -->
                <div class="p-6 sm:p-10 lg:p-12">
                    <div class="mb-6">
                        <x-logo />
                    </div>

                    <p class="text-xs font-bold uppercase tracking-wider text-[#ea580c]">Selamat Datang Kembali</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">Masuk ke Akun Anda</h1>
                    <p class="mt-2 text-sm leading-relaxed text-stone-500">
                        Akses reservasi liburan atau kelola homestay Anda dalam satu dashboard terpadu desa wisata.
                    </p>

                    <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-4">
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
                            <p class="text-xs text-stone-500">Gunakan akun yang telah didaftarkan.</p>
                            <a href="{{ route('register') }}" class="text-xs font-bold text-[#13391b] hover:text-[#ea580c] transition-colors">
                                Belum punya akun? Daftar
                            </a>
                        </div>

                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-sm font-bold shadow-xs hover:shadow-md transition-all cursor-pointer"
                        >
                            <span class="material-symbols-outlined text-base">login</span>
                            <span>Masuk Sekarang</span>
                        </button>
                    </form>

                    <!-- Divider Metode Alternatif -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-stone-200"></div>
                        </div>
                        <div class="relative flex justify-center text-[11px] uppercase tracking-wider">
                            <span class="bg-white px-3 text-stone-400 font-bold">Atau Masuk Cepat Dengan</span>
                        </div>
                    </div>

                    <!-- 3 Tombol Opsi: Google, X, WhatsApp OTP -->
                    <div class="grid grid-cols-3 gap-2.5">
                        <!-- Google -->
                        <button
                            type="button"
                            @click="openSocial('Google')"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 hover:border-stone-300 text-stone-700 text-xs font-semibold shadow-2xs transition-all cursor-pointer"
                            title="Masuk dengan akun Google"
                        >
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span class="truncate">Google</span>
                        </button>

                        <!-- X (Twitter) -->
                        <button
                            type="button"
                            @click="openSocial('X (Twitter)')"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 hover:border-stone-300 text-stone-700 text-xs font-semibold shadow-2xs transition-all cursor-pointer"
                            title="Masuk dengan akun X (Twitter)"
                        >
                            <svg class="h-3.5 w-3.5 fill-stone-900 shrink-0" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                            <span class="truncate">X</span>
                        </button>

                        <!-- WhatsApp OTP -->
                        <button
                            type="button"
                            @click="showWaModal = true"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl border border-emerald-200 bg-emerald-50/60 hover:bg-emerald-100/60 hover:border-emerald-300 text-emerald-900 text-xs font-semibold shadow-2xs transition-all cursor-pointer"
                            title="Masuk dengan OTP WhatsApp"
                        >
                            <svg class="h-4 w-4 fill-[#25D366] shrink-0" viewBox="0 0 24 24">
                                <path d="M12.031 2c-5.514 0-9.999 4.485-9.999 9.999 0 1.761.458 3.479 1.328 4.996l-1.411 5.16 5.297-1.389c1.464.799 3.111 1.232 4.785 1.232 5.514 0 9.999-4.485 9.999-9.999s-4.485-10.001-9.999-10.001zm5.834 14.161c-.244.686-1.229 1.306-1.748 1.377-.478.065-1.077.098-3.486-.889-2.915-1.196-4.793-4.179-4.938-4.372-.146-.194-1.187-1.579-1.187-3.009s.75-2.133 1.018-2.424c.267-.291.583-.364.778-.364.195 0 .389.002.559.01.18.009.421-.068.658.502.244.585.83 2.023.903 2.169.073.146.122.316.024.511-.097.195-.146.316-.292.486-.146.171-.307.382-.439.513-.146.146-.299.306-.129.598.17.291.757 1.248 1.624 2.019 1.116.993 2.057 1.3 2.349 1.446.292.146.462.122.632-.073.17-.195.729-.851.924-1.143.195-.292.389-.244.656-.146.267.097 1.699.802 1.991.948.292.146.486.219.559.341.073.122.073.705-.171 1.391z"/>
                            </svg>
                            <span class="truncate">WA OTP</span>
                        </button>
                    </div>
                </div>

                <!-- Aside Inspirasi & Demo Credentials (Luxury Deep Forest Green with Subtle Rural Landscape Texture) -->
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
                            <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
                            <span>Platform Resmi Desa Wisata Pokdarwis</span>
                        </span>

                        <h2 class="text-2xl sm:text-3xl font-extrabold leading-snug tracking-tight text-white">
                            “Pulang ke desa, menemukan cerita yang tidak ada di buku panduan.”
                        </h2>
                        <p class="mt-4 text-xs sm:text-sm text-stone-300 leading-relaxed">
                            Setiap homestay dikelola langsung oleh warga desa mitra. Reservasi Anda 100% mendukung ekonomi dan kelestarian budaya nusantara.
                        </p>
                    </div>

                    <!-- Demo Box -->
                    <div class="relative z-10 mt-8 rounded-2xl border border-white/15 bg-black/40 p-5 backdrop-blur-md">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-400">Akun Pengujian Demo</p>
                            <span class="material-symbols-outlined text-sm text-stone-400">key</span>
                        </div>
                        <div class="space-y-2 text-xs text-stone-300">
                            <div class="flex justify-between items-center py-1 border-b border-white/10">
                                <span class="font-semibold text-stone-200">Admin</span>
                                <code class="bg-black/60 px-2 py-0.5 rounded text-emerald-300 font-mono">admin / admin123</code>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-white/10">
                                <span class="font-semibold text-stone-200">Pemilik</span>
                                <code class="bg-black/60 px-2 py-0.5 rounded text-emerald-300 font-mono">pemilik1 / pemilik123</code>
                            </div>
                            <div class="flex justify-between items-center py-1">
                                <span class="font-semibold text-stone-200">Wisatawan</span>
                                <code class="bg-black/60 px-2 py-0.5 rounded text-emerald-300 font-mono">wisatawan1 / wisatawan123</code>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        <!-- Modal WhatsApp OTP -->
        <div
            x-show="showWaModal"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
        >
            <div
                @click.away="showWaModal = false"
                class="relative w-full max-w-md rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-stone-200"
            >
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-[#25D366]">
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                                <path d="M12.031 2c-5.514 0-9.999 4.485-9.999 9.999 0 1.761.458 3.479 1.328 4.996l-1.411 5.16 5.297-1.389c1.464.799 3.111 1.232 4.785 1.232 5.514 0 9.999-4.485 9.999-9.999s-4.485-10.001-9.999-10.001zm5.834 14.161c-.244.686-1.229 1.306-1.748 1.377-.478.065-1.077.098-3.486-.889-2.915-1.196-4.793-4.179-4.938-4.372-.146-.194-1.187-1.579-1.187-3.009s.75-2.133 1.018-2.424c.267-.291.583-.364.778-.364.195 0 .389.002.559.01.18.009.421-.068.658.502.244.585.83 2.023.903 2.169.073.146.122.316.024.511-.097.195-.146.316-.292.486-.146.171-.307.382-.439.513-.146.146-.299.306-.129.598.17.291.757 1.248 1.624 2.019 1.116.993 2.057 1.3 2.349 1.446.292.146.462.122.632-.073.17-.195.729-.851.924-1.143.195-.292.389-.244.656-.146.267.097 1.699.802 1.991.948.292.146.486.219.559.341.073.122.073.705-.171 1.391z"/>
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-stone-900">Masuk via WhatsApp OTP</h3>
                            <p class="text-[11px] text-stone-500">Autentikasi tanpa kata sandi</p>
                        </div>
                    </div>
                    <button @click="showWaModal = false" class="text-stone-400 hover:text-stone-700 text-lg">✕</button>
                </div>

                <form method="POST" action="{{ route('login.whatsapp') }}" class="mt-4 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">Nomor WhatsApp Terdaftar</label>
                        <div class="flex gap-2">
                            <input
                                type="tel"
                                name="no_hp"
                                x-model="waPhone"
                                placeholder="contoh: 081234567890"
                                required
                                class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none"
                            />
                            <button
                                type="button"
                                @click="sendWaOtp()"
                                :disabled="!waPhone || (waOtpSent && waCountdown > 0)"
                                class="shrink-0 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:bg-stone-300 text-white text-xs font-bold transition-all cursor-pointer"
                            >
                                <span x-show="!waOtpSent">Kirim OTP</span>
                                <span x-show="waOtpSent && waCountdown > 0" x-text="waCountdown + 's'"></span>
                                <span x-show="waOtpSent && waCountdown === 0">Kirim Lagi</span>
                            </button>
                        </div>
                        <p class="mt-1 text-[11px] text-stone-500">Nomor demo terisi otomatis: 081234567890 (Akun Wisatawan)</p>
                    </div>

                    <!-- Notifikasi Kode OTP Demo -->
                    <template x-if="waOtpSent">
                        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-xs text-emerald-900">
                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-emerald-600 text-base shrink-0 mt-0.5">check_circle</span>
                                <div>
                                    <p class="font-bold">Kode OTP WhatsApp Simulasi Terkirim!</p>
                                    <p class="mt-0.5">Gunakan kode rahasia: <span class="font-mono font-bold text-sm bg-white px-2 py-0.5 rounded border border-emerald-300 text-[#13391b]" x-text="waOtpCode"></span></p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">Kode OTP WhatsApp (6 Digit)</label>
                        <input
                            type="text"
                            name="wa_otp"
                            maxlength="6"
                            placeholder="Contoh: 882190"
                            required
                            class="w-full text-center tracking-[0.3em] font-mono font-bold text-lg py-2.5 px-3.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none"
                        />
                    </div>

                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-xl bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-sm">login</span>
                        <span>Verifikasi & Masuk</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Modal Sosial Login (Google / X) -->
        <div
            x-show="showSocialModal"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
        >
            <div
                @click.away="showSocialModal = false"
                class="relative w-full max-w-md rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-stone-200 text-center"
            >
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-[#13391b] mb-4">
                    <span class="material-symbols-outlined text-3xl">verified_user</span>
                </div>
                <h3 class="text-lg font-bold text-stone-900" x-text="'Masuk dengan ' + socialProvider"></h3>
                <p class="mt-2 text-xs sm:text-sm text-stone-600 leading-relaxed">
                    Sistem Single Sign-On (SSO) resmi Pokdarwis menghubungkan akun <span class="font-bold text-stone-900" x-text="socialProvider"></span> Anda secara langsung ke dashboard wisatawan demo HomeStay Go.
                </p>

                <div class="mt-6 flex flex-col gap-2">
                    <a
                        :href="'{{ url('/login/social') }}/' + (socialProvider === 'Google' ? 'google' : 'x')"
                        class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all"
                    >
                        <span>Lanjutkan dengan Akun Demo</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                    <button
                        type="button"
                        @click="showSocialModal = false"
                        class="w-full py-2.5 rounded-full border border-stone-200 text-stone-600 hover:bg-stone-50 text-xs font-semibold cursor-pointer"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection
