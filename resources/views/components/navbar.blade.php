@php
    $user = auth()->user();
    $dashboardRoute = match ($user?->role?->value) {
        'admin' => 'admin.dashboard',
        'pemilik' => 'pemilik.dashboard',
        'wisatawan' => 'wisatawan.dashboard',
        default => null,
    };
@endphp

<header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-sand-200/80 bg-sand-50/90 backdrop-blur">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="focus-ring rounded-2xl">
            <x-logo />
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="Navigasi utama">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Beranda</a>
            @if (Route::has('homestay.index'))
                <a href="{{ route('homestay.index') }}" class="nav-link {{ request()->routeIs('homestay.*') ? 'nav-link-active' : '' }}">Jelajahi Homestay</a>
            @endif
            @auth
                @if ($dashboardRoute)
                    <a href="{{ route($dashboardRoute) }}" class="nav-link {{ request()->routeIs('*.dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                @endif
            @endauth
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            @guest
                <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                <x-button :href="route('register')" size="sm">Daftar Sekarang</x-button>
            @else
                <div class="text-right">
                    <p class="text-sm font-semibold text-forest-900">{{ $user->displayName() }}</p>
                    <p class="text-xs capitalize text-stone-500">{{ $user->role->value }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button type="submit" variant="ghost" size="sm">Keluar</x-button>
                </form>
            @endguest
        </div>

        <button
            type="button"
            class="focus-ring inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-sand-300 bg-white text-forest-900 lg:hidden"
            @click="open = !open"
            :aria-expanded="open"
            aria-controls="mobile-menu"
            aria-label="Buka menu"
        >
            <svg x-show="!open" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
            <svg x-show="open" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>
    </div>

    <div id="mobile-menu" x-show="open" x-cloak class="border-t border-sand-200 bg-sand-50 px-4 pb-6 pt-4 lg:hidden">
        <nav class="flex flex-col gap-1" aria-label="Navigasi seluler">
            <a href="{{ route('home') }}" class="nav-link">Beranda</a>
            @if (Route::has('homestay.index'))
                <a href="{{ route('homestay.index') }}" class="nav-link">Jelajahi Homestay</a>
            @endif
            @auth
                @if ($dashboardRoute)
                    <a href="{{ route($dashboardRoute) }}" class="nav-link">Dashboard</a>
                @endif
                <div class="mt-3 border-t border-sand-200 pt-4">
                    <p class="px-4 text-sm font-semibold text-forest-900">{{ $user->displayName() }}</p>
                    <p class="px-4 text-xs capitalize text-stone-500">{{ $user->role->value }}</p>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3 px-4">
                        @csrf
                        <x-button type="submit" variant="secondary" size="sm" class="w-full">Keluar</x-button>
                    </form>
                </div>
            @else
                <div class="mt-4 grid grid-cols-2 gap-3 border-t border-sand-200 pt-4">
                    <x-button :href="route('login')" variant="secondary">Masuk</x-button>
                    <x-button :href="route('register')">Daftar</x-button>
                </div>
            @endguest
        </nav>
    </div>
</header>
