@php
    $user = auth()->user();
    $dashboardRoute = match ($user?->role?->value) {
        'admin' => 'admin.dashboard',
        'pemilik' => 'pemilik.dashboard',
        'wisatawan' => 'wisatawan.dashboard',
        default => null,
    };
@endphp

<!-- TOP NAVBAR (BACKGROUND PUTIH/KREM HANGAT, LOGO HIJAU & ORANGE, PILL BUTTON HIJAU BOTOL) -->
<header x-data="{ open: false }" class="sticky top-0 z-50 bg-[#faf9f5]/95 backdrop-blur-md border-b border-stone-200/80 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
        
        <!-- Logo Brand -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 group focus:outline-none">
            <x-logo />
        </a>

        <!-- Menu Links & Pill Action Desktop -->
        <nav class="hidden md:flex items-center gap-2 text-sm font-semibold text-stone-700" aria-label="Navigasi Utama">
            <a href="{{ route('home') }}" class="px-4 py-2 rounded-full transition-colors {{ request()->routeIs('home') ? 'text-stone-900 font-bold hover:text-[#13391b]' : 'hover:text-[#13391b]' }}">
                Beranda
            </a>

            @if (Route::has('homestay.index'))
                <a href="{{ route('homestay.index') }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white px-5 py-2.5 rounded-full shadow-xs transition-all flex items-center gap-1.5 hover:shadow-sm">
                    <span class="material-symbols-outlined text-base">travel_explore</span>
                    <span>Jelajahi Homestay</span>
                </a>
            @endif

            <a href="{{ route('radar.index') }}" class="px-4 py-2 rounded-full transition-colors flex items-center gap-1.5 {{ request()->routeIs('radar.index') ? 'text-[#13391b] font-bold bg-emerald-100/60' : 'hover:text-[#13391b]' }}">
                <span class="material-symbols-outlined text-base text-orange-600">radar</span>
                <span>Radar AR</span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase bg-orange-100 text-[#ea580c]">Baru</span>
            </a>

            <a href="{{ request()->routeIs('home') ? '#paket' : route('home') . '#paket' }}" class="px-4 py-2 rounded-full hover:text-[#13391b] transition-colors">
                Paket Wisata
            </a>

            <a href="{{ request()->routeIs('home') ? '#keunggulan' : route('home') . '#keunggulan' }}" class="px-4 py-2 rounded-full hover:text-[#13391b] transition-colors">
                Keunggulan
            </a>

            @auth
                @if ($dashboardRoute)
                    <a href="{{ route($dashboardRoute) }}" class="px-4 py-2 rounded-full text-emerald-800 font-bold hover:text-[#13391b] transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                @endif
            @endauth
        </nav>

        <!-- Auth Buttons Desktop -->
        <div class="hidden md:flex items-center gap-3">
            @guest
                <a href="{{ route('login') }}" class="text-sm font-semibold text-stone-700 hover:text-[#13391b] px-4 py-2 rounded-full transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-sm font-bold px-5 py-2.5 rounded-full shadow-xs transition-all hover:shadow-sm">
                    Daftar Sekarang
                </a>
            @else
                <div class="flex items-center gap-3 bg-white px-3.5 py-1.5 rounded-full border border-stone-200 shadow-xs">
                    <div class="leading-tight text-right">
                        <span class="block text-xs font-bold text-stone-900">{{ $user->displayName() }}</span>
                        <span class="block text-[10px] font-semibold uppercase tracking-wider text-emerald-700">{{ $user->role->value }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-8 h-8 rounded-full bg-stone-100 hover:bg-red-50 text-stone-600 hover:text-red-600 flex items-center justify-center transition-colors cursor-pointer" title="Keluar">
                            <span class="material-symbols-outlined text-base">logout</span>
                        </button>
                    </form>
                </div>
            @endguest
        </div>

        <!-- Mobile Hamburger Button -->
        <button
            type="button"
            class="md:hidden inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-stone-300 bg-white text-stone-900 shadow-xs transition-colors hover:bg-stone-50 cursor-pointer"
            @click="open = !open"
            :aria-expanded="open"
            aria-label="Buka Menu"
        >
            <span class="material-symbols-outlined text-2xl" x-text="open ? 'close' : 'menu'">menu</span>
        </button>
    </div>

    <!-- Mobile Drawer Menu -->
    <div id="mobile-menu" x-show="open" x-cloak class="border-t border-stone-200 bg-[#faf9f5] px-5 pb-6 pt-4 md:hidden shadow-lg">
        <nav class="flex flex-col gap-2">
            <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('home') ? 'bg-emerald-50 text-[#13391b]' : 'text-stone-700' }}">
                Beranda
            </a>
            @if (Route::has('homestay.index'))
                <a href="{{ route('homestay.index') }}" class="bg-[#13391b] text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-xs flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">travel_explore</span>
                    <span>Jelajahi Homestay</span>
                </a>
            @endif
            <a href="{{ route('radar.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold flex items-center justify-between {{ request()->routeIs('radar.index') ? 'bg-emerald-50 text-[#13391b]' : 'text-stone-700 hover:bg-stone-100' }}">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base text-orange-600">radar</span>
                    <span>Radar Penunjuk Arah (AR)</span>
                </span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase bg-orange-100 text-[#ea580c]">Baru</span>
            </a>
            <a href="{{ request()->routeIs('home') ? '#paket' : route('home') . '#paket' }}" @click="open = false" class="px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-stone-100">
                Paket Wisata
            </a>
            <a href="{{ request()->routeIs('home') ? '#keunggulan' : route('home') . '#keunggulan' }}" @click="open = false" class="px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-stone-100">
                Keunggulan
            </a>

            @auth
                @if ($dashboardRoute)
                    <a href="{{ route($dashboardRoute) }}" class="px-3 py-2 rounded-xl text-sm font-bold text-emerald-800 bg-emerald-50 flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">dashboard</span>
                        <span>Dashboard ({{ ucfirst($user->role->value) }})</span>
                    </a>
                @endif
                <div class="mt-4 pt-4 border-t border-stone-200 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-stone-900">{{ $user->displayName() }}</p>
                        <p class="text-xs uppercase font-semibold text-emerald-700">{{ $user->role->value }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-100 cursor-pointer">
                            Keluar
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-4 pt-4 border-t border-stone-200 grid grid-cols-2 gap-3">
                    <a href="{{ route('login') }}" class="text-center py-2.5 rounded-xl border border-stone-300 bg-white text-stone-800 text-sm font-bold">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="text-center py-2.5 rounded-xl bg-[#13391b] text-white text-sm font-bold shadow-xs">
                        Daftar
                    </a>
                </div>
            @endguest
        </nav>
    </div>
</header>
