@php
    $role = auth()->user()->role->value;
    $links = match ($role) {
        'wisatawan' => [
            ['route' => 'wisatawan.dashboard', 'label' => 'Ringkasan', 'active' => 'wisatawan.dashboard'],
            ['route' => 'wisatawan.booking.index', 'label' => 'Pemesanan Saya', 'active' => 'wisatawan.booking.*'],
        ],
        'pemilik' => [
            ['route' => 'pemilik.dashboard', 'label' => 'Ringkasan', 'active' => 'pemilik.dashboard'],
            ['route' => 'pemilik.homestay.index', 'label' => 'Homestay Saya', 'active' => 'pemilik.homestay.*'],
            ['route' => 'pemilik.booking.index', 'label' => 'Booking Masuk', 'active' => 'pemilik.booking.*'],
            ['route' => 'pemilik.pendapatan', 'label' => 'Pendapatan', 'active' => 'pemilik.pendapatan'],
        ],
        'admin' => [
            ['route' => 'admin.dashboard', 'label' => 'Ringkasan', 'active' => 'admin.dashboard'],
            ['route' => 'admin.users.index', 'label' => 'Pengguna', 'active' => 'admin.users.*'],
            ['route' => 'admin.homestay.index', 'label' => 'Verifikasi Homestay', 'active' => 'admin.homestay.*'],
            ['route' => 'admin.pembayaran.index', 'label' => 'Pembayaran', 'active' => 'admin.pembayaran.*'],
            ['route' => 'admin.fasilitas.index', 'label' => 'Fasilitas', 'active' => 'admin.fasilitas.*'],
            ['route' => 'admin.laporan.booking', 'label' => 'Laporan', 'active' => 'admin.laporan.*'],
        ],
        default => [],
    };
@endphp

<nav class="no-print -mx-4 overflow-x-auto px-4 pb-1 sm:mx-0 sm:px-0" aria-label="Navigasi dashboard">
    <div class="flex min-w-max gap-2 rounded-3xl border border-sand-200 bg-white p-2 shadow-soft">
        @foreach ($links as $link)
            <a
                href="{{ route($link['route']) }}"
                class="focus-ring whitespace-nowrap rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ request()->routeIs($link['active']) ? 'bg-forest-700 text-white' : 'text-forest-900 hover:bg-forest-50' }}"
            >
                {{ $link['label'] }}
            </a>
        @endforeach
    </div>
</nav>
