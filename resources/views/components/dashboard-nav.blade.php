@php
    $role = auth()->user()->role->value;
    $links = match ($role) {
        'wisatawan' => [
            ['route' => 'wisatawan.dashboard', 'label' => 'Ringkasan', 'active' => 'wisatawan.dashboard', 'icon' => 'dashboard'],
            ['route' => 'wisatawan.booking.index', 'label' => 'Pemesanan Saya', 'active' => 'wisatawan.booking.*', 'icon' => 'receipt_long'],
        ],
        'pemilik' => [
            ['route' => 'pemilik.dashboard', 'label' => 'Ringkasan', 'active' => 'pemilik.dashboard', 'icon' => 'dashboard'],
            ['route' => 'pemilik.homestay.index', 'label' => 'Homestay Saya', 'active' => 'pemilik.homestay.*', 'icon' => 'cottage'],
            ['route' => 'pemilik.booking.index', 'label' => 'Booking Masuk', 'active' => 'pemilik.booking.*', 'icon' => 'inbox'],
            ['route' => 'pemilik.pendapatan', 'label' => 'Pendapatan', 'active' => 'pemilik.pendapatan', 'icon' => 'payments'],
        ],
        'admin' => [
            ['route' => 'admin.dashboard', 'label' => 'Ringkasan', 'active' => 'admin.dashboard', 'icon' => 'dashboard'],
            ['route' => 'admin.users.index', 'label' => 'Pengguna', 'active' => 'admin.users.*', 'icon' => 'manage_accounts'],
            ['route' => 'admin.homestay.index', 'label' => 'Verifikasi Homestay', 'active' => 'admin.homestay.*', 'icon' => 'verified'],
            ['route' => 'admin.pembayaran.index', 'label' => 'Pembayaran', 'active' => 'admin.pembayaran.*', 'icon' => 'account_balance_wallet'],
            ['route' => 'admin.fasilitas.index', 'label' => 'Fasilitas', 'active' => 'admin.fasilitas.*', 'icon' => 'list_alt'],
            ['route' => 'admin.laporan.booking', 'label' => 'Laporan', 'active' => 'admin.laporan.*', 'icon' => 'analytics'],
        ],
        default => [],
    };
@endphp

<nav class="no-print -mx-4 overflow-x-auto px-4 pb-2 sm:mx-0 sm:px-0 no-scrollbar" aria-label="Navigasi dashboard">
    <div class="inline-flex min-w-max gap-1.5 rounded-full border border-stone-200 bg-white p-1.5 shadow-xs">
        @foreach ($links as $link)
            @if (Route::has($link['route']))
                <a
                    href="{{ route($link['route']) }}"
                    class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-4 py-2 text-xs font-bold transition-all {{ request()->routeIs($link['active']) ? 'bg-[#13391b] text-white shadow-xs' : 'text-stone-600 hover:text-[#13391b] hover:bg-stone-50' }}"
                >
                    <span class="material-symbols-outlined text-[16px]">{{ $link['icon'] }}</span>
                    <span>{{ $link['label'] }}</span>
                </a>
            @endif
        @endforeach
    </div>
</nav>
