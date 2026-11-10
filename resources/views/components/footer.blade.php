<!-- UPGRADED FOOTER RESMI (MODERN DARK THEME SESUAI DESAIN PRODUK) -->
<footer class="bg-stone-950 text-stone-400 border-t border-stone-800 pt-16 pb-12 text-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-stone-800/80">
            
            <!-- Brand & Mission Column (Col 1-2) -->
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="inline-block focus:outline-none">
                    <x-logo light="true" />
                </a>
                
                <p class="mt-4 text-xs sm:text-sm text-stone-400 leading-relaxed max-w-sm">
                    Platform reservasi homestay resmi binaan Pokdarwis Nusantara. Menghubungkan wisatawan langsung dengan keluarga desa melalui teknologi kalender pintar bebas risiko double booking.
                </p>

                <!-- Badges Kemitraan Resmi -->
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-stone-900 text-stone-200 text-xs font-medium border border-stone-800">
                        <span class="material-symbols-outlined text-sm text-emerald-400">verified</span>
                        <span>Mitra Resmi Pokdarwis</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-stone-900 text-stone-200 text-xs font-medium border border-stone-800">
                        <span class="material-symbols-outlined text-sm text-amber-400">eco</span>
                        <span>Eco-Tourism Nusantara</span>
                    </span>
                </div>
            </div>

            <!-- Column 3: Navigasi Utama -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-white">Navigasi Utama</h3>
                <ul class="mt-4 space-y-2.5 text-xs sm:text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-emerald-400 transition-colors">Beranda</a>
                    </li>
                    @if (Route::has('homestay.index'))
                        <li>
                            <a href="{{ route('homestay.index') }}" class="hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                                <span>Jelajahi Homestay</span>
                                <span class="text-[10px] bg-emerald-900/60 text-emerald-300 px-1.5 py-0.5 rounded font-bold">12 Desa</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ request()->routeIs('home') ? '#paket' : route('home') . '#paket' }}" class="hover:text-emerald-400 transition-colors">Paket Wisata Desa</a>
                    </li>
                    <li>
                        <a href="{{ request()->routeIs('home') ? '#keunggulan' : route('home') . '#keunggulan' }}" class="hover:text-emerald-400 transition-colors">Standar Layanan</a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Kemitraan Warga -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-white">Kemitraan Warga</h3>
                <ul class="mt-4 space-y-2.5 text-xs sm:text-sm">
                    <li>
                        <a href="{{ route('register') }}" class="hover:text-emerald-400 transition-colors">Daftarkan Homestay</a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="hover:text-emerald-400 transition-colors">Masuk Pengelola / Pemilik</a>
                    </li>
                    <li class="flex items-center gap-2 text-stone-500">
                        <span>Kalender Anti-Double Booking</span>
                        <span class="text-[9px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded font-bold">Aktif</span>
                    </li>
                    <li class="text-stone-500">
                        <span>Verifikasi Pembayaran Pokdarwis</span>
                    </li>
                </ul>
            </div>

            <!-- Column 5: Layanan & Kontak -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-white">Bantuan &amp; Kontak</h3>
                <p class="mt-4 text-xs text-stone-400 leading-relaxed">
                    Pendampingan wisatawan &amp; pengelola homestay setiap hari:
                </p>
                <div class="mt-3 space-y-2 text-xs">
                    <p class="font-semibold text-stone-200">
                        <span class="block text-[10px] text-stone-500 font-normal uppercase">Email Dukungan:</span>
                        bantuan@homestaygo.id
                    </p>
                    <p class="font-semibold text-stone-200">
                        <span class="block text-[10px] text-stone-500 font-normal uppercase">Jam Operasional:</span>
                        08.00 — 20.00 WIB
                    </p>
                    <p class="text-[11px] text-stone-500 pt-1">
                        Banyumas &amp; Mitra Nusantara, Jawa Tengah
                    </p>
                </div>
            </div>

        </div>

        <!-- Bottom Bar: Academic & Copyright Information -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-500">
            <div class="flex items-center gap-2">
                <span class="font-bold text-stone-300">
                    <span class="text-emerald-400">HomeStay</span><span class="text-[#ea580c]">Go</span>
                </span>
                <span class="text-stone-700">|</span>
                <span class="text-stone-400">Smart Tourism &amp; Hospitality Nusantara</span>
            </div>

            <div class="text-center sm:text-right">
                <p class="font-semibold text-stone-400">Proyek PSAS SMK Telkom Purwokerto — Kelas XI PPLG 1</p>
                <p class="mt-0.5 text-[11px] text-stone-600">&copy; {{ date('Y') }} HomeStay Go. Seluruh hak cipta dilindungi undang-undang.</p>
            </div>
        </div>

    </div>
</footer>
