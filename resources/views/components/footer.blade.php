<footer class="mt-20 border-t border-sand-200 bg-forest-950 text-sand-100">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
            <x-logo class="[&_span]:!text-sand-50" />
            <p class="mt-4 max-w-sm text-sm leading-relaxed text-sand-200/80">
                Menghubungkan wisatawan dengan homestay warga desa wisata. Menginap lebih dekat,
                merasakan keramahan yang sesungguhnya.
            </p>
        </div>
        <div>
            <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-sand-300">Jelajahi</h2>
            <ul class="mt-4 space-y-2 text-sm text-sand-100/90">
                @if (Route::has('homestay.index'))
                    <li><a href="{{ route('homestay.index') }}" class="hover:text-white">Katalog homestay</a></li>
                @endif
                <li><a href="{{ route('register') }}" class="hover:text-white">Menjadi pemilik homestay</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-white">Masuk ke akun</a></li>
            </ul>
        </div>
        <div>
            <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-sand-300">Butuh bantuan?</h2>
            <p class="mt-4 text-sm leading-relaxed text-sand-100/90">
                Tim desa wisata siap membantu setiap hari, pukul 08.00–20.00 WIB.
            </p>
            <p class="mt-2 text-sm font-semibold">halo@homestaygo.id</p>
        </div>
    </div>
    <div class="border-t border-white/10 py-5 text-center text-xs text-sand-200/70">
        &copy; {{ date('Y') }} HomeStay Go. Dibuat untuk desa wisata Indonesia.
    </div>
</footer>
