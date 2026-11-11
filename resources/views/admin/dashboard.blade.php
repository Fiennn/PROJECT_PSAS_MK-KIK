@extends('layouts.dashboard')

@section('title', 'Dashboard Admin Pokdarwis')
@section('page_title', 'Pusat Kendali Pokdarwis')
@section('page_description', 'Pantau ekosistem desa wisata: verifikasi pembayaran tiket, kelayakan homestay warga, audit omset keuangan, dan manajemen pengguna.')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a
            href="{{ route('admin.homestay.create') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs hover:shadow-md transition-all cursor-pointer"
        >
            <span class="material-symbols-outlined text-sm">add_home</span>
            <span>+ Daftarkan Homestay Baru</span>
        </a>
        <a
            href="{{ route('admin.pembayaran.index') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
        >
            <span class="material-symbols-outlined text-sm text-[#ea580c]">account_balance_wallet</span>
            <span>Verifikasi Pembayaran</span>
        </a>
    </div>
@endsection

@section('dashboard')
    <!-- Banner Pusat Kendali Pokdarwis -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0d2813] via-[#13391b] to-[#1a4a22] p-6 sm:p-8 text-white shadow-lg mb-6">
        <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-emerald-400/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-10 -bottom-10 h-48 w-48 rounded-full bg-amber-400/10 blur-2xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-xs border border-white/15 text-xs text-emerald-200 font-semibold mb-3">
                    <span class="material-symbols-outlined text-xs text-amber-400">admin_panel_settings</span>
                    <span>Pusat Administrasi Terpadu &bull; Pokdarwis Desa Wisata</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Pusat Pengawasan Ekosistem Wisata
                </h2>
                <p class="mt-2 text-xs sm:text-sm text-stone-200 max-w-2xl leading-relaxed">
                    Kelola standarisasi hunian warga, verifikasi transparansi pembayaran tanpa perantara berbelit, dan awasi denyut perputaran ekonomi desa secara terpadu.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <div class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl px-4 py-3 text-center min-w-[140px]">
                    <p class="text-[10px] uppercase font-bold text-stone-300 tracking-wider">Omset Terverifikasi</p>
                    <p class="mt-1 text-base sm:text-lg font-extrabold text-amber-400 truncate">{{ rupiah($stats['pendapatan_terverifikasi']) }}</p>
                </div>
                <div class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl px-4 py-3 text-center min-w-[130px]">
                    <p class="text-[10px] uppercase font-bold text-stone-300 tracking-wider">Homestay Aktif</p>
                    <p class="mt-1 text-base sm:text-lg font-extrabold text-emerald-300">{{ $stats['homestay_disetujui'] }} / {{ $stats['total_homestay'] }} <span class="text-xs font-normal text-stone-300">Unit</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Antrean Verifikasi (Jika ada yang butuh tindakan admin) -->
    @if ($stats['pembayaran_menunggu'] > 0 || $stats['homestay_menunggu'] > 0)
        <div class="grid gap-4 sm:grid-cols-2 mb-6">
            @if ($stats['pembayaran_menunggu'] > 0)
                <div class="rounded-2xl border border-amber-300 bg-amber-50/90 p-4 shadow-xs flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#ea580c] text-white">
                            <span class="material-symbols-outlined text-xl">payments</span>
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-stone-900 truncate">{{ $stats['pembayaran_menunggu'] }} Pembayaran Menunggu Validasi</p>
                            <p class="text-[11px] text-stone-600">Bukti transfer wisatawan perlu dicek.</p>
                        </div>
                    </div>
                    <a
                        href="{{ route('admin.pembayaran.index', ['status' => 'menunggu']) }}"
                        class="inline-flex shrink-0 items-center gap-1 px-3 py-1.5 rounded-full bg-[#ea580c] hover:bg-[#c2410c] text-white text-xs font-bold shadow-xs transition-all"
                    >
                        <span>Tinjau</span>
                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                    </a>
                </div>
            @endif

            @if ($stats['homestay_menunggu'] > 0)
                <div class="rounded-2xl border border-emerald-300 bg-emerald-50/90 p-4 shadow-xs flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#13391b] text-white">
                            <span class="material-symbols-outlined text-xl">cottage</span>
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-stone-900 truncate">{{ $stats['homestay_menunggu'] }} Homestay Menunggu Persetujuan</p>
                            <p class="text-[11px] text-stone-600">Pengajuan warga siap ditinjau.</p>
                        </div>
                    </div>
                    <a
                        href="{{ route('admin.homestay.index', ['status' => 'menunggu']) }}"
                        class="inline-flex shrink-0 items-center gap-1 px-3 py-1.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all"
                    >
                        <span>Inspeksi</span>
                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- 6 KPI Metrics Cards -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6 mb-8">
        <!-- Card 1: Pembayaran Menunggu -->
        <div class="rounded-3xl border {{ $stats['pembayaran_menunggu'] > 0 ? 'border-amber-300 bg-amber-50/50' : 'border-stone-200 bg-white' }} p-4 shadow-xs transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $stats['pembayaran_menunggu'] > 0 ? 'text-amber-800' : 'text-stone-500' }}">Bukti Bayar</span>
                <span class="flex h-7 w-7 items-center justify-center rounded-lg {{ $stats['pembayaran_menunggu'] > 0 ? 'bg-amber-100 text-[#ea580c]' : 'bg-stone-100 text-stone-500' }}">
                    <span class="material-symbols-outlined text-sm">receipt</span>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold {{ $stats['pembayaran_menunggu'] > 0 ? 'text-[#ea580c]' : 'text-stone-900' }}">{{ $stats['pembayaran_menunggu'] }}</p>
            <p class="mt-0.5 text-[10px] text-stone-400">Butuh verifikasi</p>
        </div>

        <!-- Card 2: Homestay Menunggu -->
        <div class="rounded-3xl border {{ $stats['homestay_menunggu'] > 0 ? 'border-emerald-300 bg-emerald-50/50' : 'border-stone-200 bg-white' }} p-4 shadow-xs transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $stats['homestay_menunggu'] > 0 ? 'text-emerald-800' : 'text-stone-500' }}">Pengajuan</span>
                <span class="flex h-7 w-7 items-center justify-center rounded-lg {{ $stats['homestay_menunggu'] > 0 ? 'bg-emerald-100 text-[#13391b]' : 'bg-stone-100 text-stone-500' }}">
                    <span class="material-symbols-outlined text-sm">home_work</span>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold {{ $stats['homestay_menunggu'] > 0 ? 'text-[#13391b]' : 'text-stone-900' }}">{{ $stats['homestay_menunggu'] }}</p>
            <p class="mt-0.5 text-[10px] text-stone-400">Homestay baru</p>
        </div>

        <!-- Card 3: Booking Aktif -->
        <div class="rounded-3xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-sky-700">
                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500">Booking Aktif</span>
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-50 text-sky-700">
                    <span class="material-symbols-outlined text-sm">bed</span>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-stone-900">{{ $activeBookings }}</p>
            <p class="mt-0.5 text-[10px] text-stone-400">Tamu berjalan</p>
        </div>

        <!-- Card 4: Booking Bulan Ini -->
        <div class="rounded-3xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-stone-500">
                <span class="text-[10px] font-bold uppercase tracking-wider">Bulan Ini</span>
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-stone-100 text-stone-600">
                    <span class="material-symbols-outlined text-sm">calendar_month</span>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-stone-900">{{ $stats['booking_bulan_ini'] }}</p>
            <p class="mt-0.5 text-[10px] text-stone-400">Reservasi terdaftar</p>
        </div>

        <!-- Card 5: Total Pengguna -->
        <div class="rounded-3xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-stone-500">
                <span class="text-[10px] font-bold uppercase tracking-wider">Pengguna</span>
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-stone-100 text-stone-600">
                    <span class="material-symbols-outlined text-sm">group</span>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-stone-900">{{ $stats['pengguna'] }}</p>
            <p class="mt-0.5 text-[10px] text-stone-400">{{ $stats['total_wisatawan'] }} tamu &bull; {{ $stats['total_pemilik'] }} warga</p>
        </div>

        <!-- Card 6: Total Homestay -->
        <div class="rounded-3xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-stone-500">
                <span class="text-[10px] font-bold uppercase tracking-wider">Homestay</span>
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-[#13391b]">
                    <span class="material-symbols-outlined text-sm">cottage</span>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-[#13391b]">{{ $stats['total_homestay'] }}</p>
            <p class="mt-0.5 text-[10px] text-stone-400">{{ $stats['homestay_disetujui'] }} aktif di katalog</p>
        </div>
    </div>

    <!-- Dua Kolom Operasional: Homestay Menunggu & Pembayaran Menunggu -->
    <div class="grid gap-6 lg:grid-cols-2 mb-8">
        <!-- Kolom Kiri: Homestay Menunggu Verifikasi -->
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-[#13391b]">
                            <span class="material-symbols-outlined text-base">domain_verification</span>
                        </span>
                        <div>
                            <h3 class="text-base font-extrabold text-stone-900">Homestay Menunggu Verifikasi</h3>
                            <p class="text-xs text-stone-500">Tinjau kelayakan fasilitas dan alamat rumah warga.</p>
                        </div>
                    </div>
                    <a
                        href="{{ route('admin.homestay.index') }}"
                        class="text-xs font-bold text-[#13391b] hover:text-[#ea580c] transition-colors"
                    >
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if ($pendingHomestays->isEmpty())
                    <div class="py-10 text-center">
                        <span class="material-symbols-outlined text-3xl text-emerald-600 mb-1">task_alt</span>
                        <p class="text-xs font-bold text-stone-800">Semua Pengajuan Sudah Ditinjau</p>
                        <p class="text-[11px] text-stone-500 mt-0.5">Tidak ada antrean homestay yang menunggu persetujuan saat ini.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($pendingHomestays as $homestay)
                            <div class="flex items-center justify-between p-3.5 rounded-2xl border border-stone-200 bg-stone-50/50 hover:bg-white hover:border-emerald-300 transition-all">
                                <div class="min-w-0 pr-3">
                                    <h4 class="text-xs font-extrabold text-stone-900 truncate">{{ $homestay->nama_homestay }}</h4>
                                    <p class="text-[11px] text-stone-500 truncate">Pemilik: {{ $homestay->pemilik->nama_pemilik }} &bull; {{ $homestay->alamat }}</p>
                                </div>
                                <a
                                    href="{{ route('admin.homestay.show', $homestay) }}"
                                    class="inline-flex shrink-0 items-center gap-1 px-3 py-1.5 rounded-xl bg-[#13391b] hover:bg-[#0e2c14] text-white text-[11px] font-bold shadow-2xs transition-all"
                                >
                                    <span>Tinjau</span>
                                    <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-between text-[11px] text-stone-500">
                <span>Total Menunggu: <strong>{{ $stats['homestay_menunggu'] }} unit</strong></span>
                <a href="{{ route('admin.homestay.create') }}" class="font-bold text-[#13391b] hover:underline">+ Daftarkan Manual</a>
            </div>
        </div>

        <!-- Kolom Kanan: Pembayaran Menunggu Validasi -->
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-[#ea580c]">
                            <span class="material-symbols-outlined text-base">receipt_long</span>
                        </span>
                        <div>
                            <h3 class="text-base font-extrabold text-stone-900">Pembayaran Menunggu Validasi</h3>
                            <p class="text-xs text-stone-500">Periksa bukti transfer sebelum pemilik mengonfirmasi kamar.</p>
                        </div>
                    </div>
                    <a
                        href="{{ route('admin.pembayaran.index') }}"
                        class="text-xs font-bold text-[#13391b] hover:text-[#ea580c] transition-colors"
                    >
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if ($pendingPayments->isEmpty())
                    <div class="py-10 text-center">
                        <span class="material-symbols-outlined text-3xl text-emerald-600 mb-1">verified</span>
                        <p class="text-xs font-bold text-stone-800">Semua Pembayaran Telah Tuntas Diverifikasi</p>
                        <p class="text-[11px] text-stone-500 mt-0.5">Tidak ada bukti transfer tertunda yang perlu dicek.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($pendingPayments as $payment)
                            <div class="flex items-center justify-between p-3.5 rounded-2xl border border-stone-200 bg-stone-50/50 hover:bg-white hover:border-amber-300 transition-all">
                                <div class="min-w-0 pr-3">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs font-extrabold text-stone-900 truncate">{{ $payment->booking->wisatawan->nama_wisatawan }}</h4>
                                        <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded">Menunggu</span>
                                    </div>
                                    <p class="text-[11px] text-stone-500 truncate mt-0.5">
                                        {{ $payment->booking->kamar->homestay->nama_homestay }} &bull; <strong class="text-stone-800">{{ rupiah($payment->jumlah) }}</strong>
                                    </p>
                                </div>
                                <a
                                    href="{{ route('admin.pembayaran.show', $payment) }}"
                                    class="inline-flex shrink-0 items-center gap-1 px-3 py-1.5 rounded-xl bg-[#ea580c] hover:bg-[#c2410c] text-white text-[11px] font-bold shadow-2xs transition-all"
                                >
                                    <span>Cek Bukti</span>
                                    <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-between text-[11px] text-stone-500">
                <span>Total Menunggu: <strong>{{ $stats['pembayaran_menunggu'] }} transaksi</strong></span>
                <a href="{{ route('admin.pembayaran.index') }}" class="font-bold text-[#ea580c] hover:underline">Kelola Seluruh Pembayaran</a>
            </div>
        </div>
    </div>

    <!-- Aktivitas Booking Real-Time Desa Wisata -->
    <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
        <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-4">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                    <span class="material-symbols-outlined text-base">travel_explore</span>
                </span>
                <div>
                    <h3 class="text-base font-extrabold text-stone-900">Aktivitas Reservasi Terkini</h3>
                    <p class="text-xs text-stone-500">Monitoring transaksi pemesanan homestay di seluruh jaringan desa wisata Pokdarwis.</p>
                </div>
            </div>
            <a
                href="{{ route('admin.laporan.booking') }}"
                class="text-xs font-bold text-[#13391b] hover:text-[#ea580c] transition-colors"
            >
                Buka Laporan Booking &rarr;
            </a>
        </div>

        @if ($recentBookings->isEmpty())
            <div class="py-8 text-center text-xs text-stone-400">
                Belum ada aktivitas reservasi yang tercatat dalam sistem.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-400 font-bold uppercase tracking-wider text-[10px]">
                            <th class="pb-3 pr-4">Wisatawan & ID</th>
                            <th class="pb-3 px-3">Homestay & Kamar</th>
                            <th class="pb-3 px-3">Jadwal Menginap</th>
                            <th class="pb-3 px-3">Status</th>
                            <th class="pb-3 pl-4 text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($recentBookings as $b)
                            <tr class="hover:bg-stone-50/50 transition-colors">
                                <td class="py-3 pr-4">
                                    <p class="font-extrabold text-stone-900">{{ $b->wisatawan->nama_wisatawan }}</p>
                                    <p class="text-[11px] text-stone-400">Booking #{{ $b->id_booking }} &bull; {{ tanggal_id($b->tgl_booking) }}</p>
                                </td>
                                <td class="py-3 px-3 text-stone-700">
                                    <p class="font-semibold text-stone-900">{{ $b->kamar->homestay->nama_homestay }}</p>
                                    <p class="text-[11px] text-stone-400">{{ $b->kamar->nama_kamar }}</p>
                                </td>
                                <td class="py-3 px-3 text-stone-600">
                                    <p class="font-medium">{{ tanggal_id($b->tgl_checkin) }} – {{ tanggal_id($b->tgl_checkout) }}</p>
                                    <p class="text-[11px] text-stone-400">{{ $b->jumlah_malam }} malam &bull; {{ $b->jumlah_tamu }} tamu</p>
                                </td>
                                <td class="py-3 px-3">
                                    <x-badge :status="$b->status->value" />
                                </td>
                                <td class="py-3 pl-4 text-right font-extrabold text-[#13391b] text-sm">
                                    {{ rupiah($b->total_harga) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
