@extends('layouts.dashboard')

@section('title', 'Dashboard Pemilik Homestay')
@section('page_title', 'Ruang Kerja Pengelola Homestay')
@section('page_description', 'Kelola penginapan desa Anda, pantau reservasi tamu wisatawan, dan pantau perkembangan omset ekonomi lokal.')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a
            href="{{ route('pemilik.homestay.create') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs hover:shadow-md transition-all cursor-pointer"
        >
            <span class="material-symbols-outlined text-sm">add_home</span>
            <span>+ Tambah Homestay Baru</span>
        </a>
        <a
            href="{{ route('pemilik.pendapatan') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
        >
            <span class="material-symbols-outlined text-sm text-[#ea580c]">payments</span>
            <span>Laporan Omset</span>
        </a>
    </div>
@endsection

@section('dashboard')
    <!-- Banner Sambutan Pengelola & Pokdarwis -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0d2813] via-[#13391b] to-[#1c5026] p-6 sm:p-8 text-white shadow-lg mb-6">
        <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-emerald-400/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-10 -bottom-10 h-48 w-48 rounded-full bg-amber-400/10 blur-2xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-xs border border-white/15 text-xs text-emerald-200 font-semibold mb-3">
                    <span class="material-symbols-outlined text-xs text-amber-400">eco</span>
                    <span>Mitra Pokdarwis &bull; Tuan Rumah Desa Wisata</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Sugeng Rawuh, {{ $pemilik->nama_pemilik }}!
                </h2>
                <p class="mt-2 text-xs sm:text-sm text-stone-200 max-w-2xl leading-relaxed">
                    Setiap tamu yang Anda sambut adalah denyut ekonomi dan pelestarian budaya desa. Pastikan kamar selalu bersih dan siap menyajikan kehangatan khas Nusantara.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <div class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl px-4 py-3 text-center min-w-[130px]">
                    <p class="text-[10px] uppercase font-bold text-stone-300 tracking-wider">Status Akun</p>
                    <div class="flex items-center justify-center gap-1.5 mt-1 text-emerald-400 font-bold text-xs sm:text-sm">
                        <span class="material-symbols-outlined text-base">verified</span>
                        <span>Terverifikasi</span>
                    </div>
                </div>
                <div class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl px-4 py-3 text-center min-w-[130px]">
                    <p class="text-[10px] uppercase font-bold text-stone-300 tracking-wider">Unit Homestay</p>
                    <p class="mt-1 text-lg font-extrabold text-amber-400">{{ $stats['homestay'] }} <span class="text-xs font-normal text-stone-200">Rumah</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Prioritas: Booking Butuh Konfirmasi Tuan Rumah -->
    @if ($stats['perlu_konfirmasi'] > 0)
        <div class="mb-6 rounded-3xl border border-amber-200 bg-amber-50/90 p-5 shadow-xs transition-all">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-[#ea580c] text-white shadow-xs">
                        <span class="material-symbols-outlined text-xl">notification_important</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-stone-900">
                            Ada {{ $stats['perlu_konfirmasi'] }} Pesanan Menunggu Konfirmasi Sambutan Anda!
                        </h3>
                        <p class="mt-0.5 text-xs text-stone-600 leading-relaxed">
                            Admin Pokdarwis telah memverifikasi bukti transfer pembayaran tamu. Segera konfirmasi ketersediaan kamar agar e-tiket resmi terbit.
                        </p>
                    </div>
                </div>
                <a
                    href="{{ route('pemilik.booking.index', ['status' => 'menunggu_verifikasi']) }}"
                    class="inline-flex shrink-0 items-center justify-center gap-1.5 px-4 py-2 rounded-full bg-[#ea580c] hover:bg-[#c2410c] text-white text-xs font-bold shadow-xs transition-all"
                >
                    <span>Konfirmasi Sekarang</span>
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        </div>
    @endif

    <!-- 4 KPI Metrics Card -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-8">
        <!-- Kartu 1: Total Homestay -->
        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-xs hover:border-emerald-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Homestay Dikelola</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-[#13391b]">
                    <span class="material-symbols-outlined text-lg">cottage</span>
                </span>
            </div>
            <p class="mt-3 text-2xl sm:text-3xl font-extrabold text-stone-900">{{ $stats['homestay'] }}</p>
            <p class="mt-1 text-xs text-stone-400">
                <span class="font-semibold text-emerald-700">{{ $stats['homestay_disetujui'] }} aktif</span> &bull; {{ $stats['total_kamar'] }} kamar total
            </p>
        </div>

        <!-- Kartu 2: Menunggu Konfirmasi -->
        <div class="rounded-3xl border {{ $stats['perlu_konfirmasi'] > 0 ? 'border-amber-300 bg-amber-50/40' : 'border-stone-200 bg-white' }} p-5 shadow-xs transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider {{ $stats['perlu_konfirmasi'] > 0 ? 'text-amber-800' : 'text-stone-500' }}">Perlu Konfirmasi</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $stats['perlu_konfirmasi'] > 0 ? 'bg-amber-100 text-[#ea580c]' : 'bg-stone-100 text-stone-500' }}">
                    <span class="material-symbols-outlined text-lg">pending_actions</span>
                </span>
            </div>
            <p class="mt-3 text-2xl sm:text-3xl font-extrabold {{ $stats['perlu_konfirmasi'] > 0 ? 'text-[#ea580c]' : 'text-stone-900' }}">{{ $stats['perlu_konfirmasi'] }}</p>
            <p class="mt-1 text-xs text-stone-400">Pembayaran terverifikasi siap ditindak</p>
        </div>

        <!-- Kartu 3: Tamu Menginap / Reservasi Aktif -->
        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-xs hover:border-sky-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Tamu Menginap</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                    <span class="material-symbols-outlined text-lg">hotel</span>
                </span>
            </div>
            <p class="mt-3 text-2xl sm:text-3xl font-extrabold text-stone-900">{{ $stats['tamu_aktif'] }} <span class="text-sm font-semibold text-stone-400">kamar hari ini</span></p>
            <p class="mt-1 text-xs text-stone-400">
                <span class="font-semibold text-sky-700">{{ $stats['dikonfirmasi'] }} booking</span> aktif berjalan
            </p>
        </div>

        <!-- Kartu 4: Total Omset -->
        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-xs hover:border-amber-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Omset Diterima</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-[#ea580c]">
                    <span class="material-symbols-outlined text-lg">account_balance_wallet</span>
                </span>
            </div>
            <p class="mt-3 text-xl sm:text-2xl font-extrabold text-[#13391b] truncate">{{ rupiah($stats['pendapatan']) }}</p>
            <p class="mt-1 text-xs text-stone-400">Dari {{ $stats['dikonfirmasi'] + $stats['selesai'] }} reservasi sukses</p>
        </div>
    </div>

    <!-- Card Validasi E-Tiket Tamu Cepat -->
    <div class="mb-8 rounded-3xl border border-emerald-200 bg-gradient-to-r from-emerald-50 via-white to-stone-50 p-5 sm:p-6 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#13391b] text-emerald-300 shadow-xs">
                    <span class="material-symbols-outlined text-2xl">qr_code_scanner</span>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-stone-900 flex items-center gap-2">
                        <span>Validasi Check-In E-Tiket Tamu Desa</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-[#ea580c]">Instan</span>
                    </h3>
                    <p class="mt-1 text-xs text-stone-600 max-w-xl leading-relaxed">
                        Wisatawan tiba di homestay Anda? Masukkan kode tiket (misal: <code class="font-mono font-bold text-emerald-800">HSG-00012</code> atau nomor booking) dari e-tiket tamu untuk cek keaslian pesanan.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('pemilik.booking.validate-ticket') }}" class="flex items-center gap-2 w-full lg:w-auto shrink-0">
                @csrf
                <div class="relative flex-1 sm:w-64">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-stone-400">
                        <span class="material-symbols-outlined text-sm">confirmation_number</span>
                    </span>
                    <input
                        type="text"
                        name="kode_tiket"
                        required
                        placeholder="Contoh: HSG-00012"
                        class="w-full rounded-xl border border-stone-300 bg-white py-2 pl-9 pr-3 text-xs font-mono font-bold text-stone-900 placeholder-stone-400 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                    />
                </div>
                <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all active:scale-95 shrink-0"
                >
                    <span class="material-symbols-outlined text-sm">verified_user</span>
                    <span>Cek Tiket</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Grid Utama: 2 Kolom (Konten Utama & Sidebar Informasi) -->
    <div class="grid gap-8 lg:grid-cols-[1.55fr_1fr]">
        <!-- KOLOM KIRI (UTAMA) -->
        <div class="space-y-8">
            <!-- Section 1: Homestay Yang Anda Kelola -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-5">
                    <div>
                        <h3 class="text-lg font-extrabold text-stone-900">Homestay Anda</h3>
                        <p class="text-xs text-stone-500">Kelola informasi hunian, ketersediaan kamar, dan paket wisata.</p>
                    </div>
                    <a
                        href="{{ route('pemilik.homestay.index') }}"
                        class="text-xs font-bold text-[#13391b] hover:text-[#ea580c] transition-colors"
                    >
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if ($homestays->isEmpty())
                    <div class="text-center py-10 px-4">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-[#13391b] mb-3">
                            <span class="material-symbols-outlined text-3xl">add_home</span>
                        </div>
                        <h4 class="text-base font-bold text-stone-900">Belum Ada Homestay Terdaftar</h4>
                        <p class="mt-1 text-xs text-stone-500 max-w-sm mx-auto">
                            Daftarkan rumah atau kamar kosong Anda sekarang untuk diverifikasi oleh Pokdarwis desa wisata.
                        </p>
                        <a
                            href="{{ route('pemilik.homestay.create') }}"
                            class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all"
                        >
                            <span>Daftarkan Homestay Pertama</span>
                        </a>
                    </div>
                @else
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($homestays as $homestay)
                            <div class="group relative rounded-2xl border border-stone-200 bg-stone-50/50 p-4 hover:border-emerald-300 hover:bg-white transition-all shadow-2xs">
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="text-sm font-extrabold text-stone-900 group-hover:text-[#13391b] transition-colors line-clamp-1">
                                        {{ $homestay->nama_homestay }}
                                    </h4>
                                    <x-badge :status="$homestay->status_verifikasi->value" />
                                </div>

                                <p class="mt-1 text-xs text-stone-500 line-clamp-2 leading-relaxed">
                                    {{ $homestay->alamat }}
                                </p>

                                <div class="mt-3 flex items-center gap-4 text-xs text-stone-600 bg-white rounded-xl p-2 border border-stone-100">
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base text-stone-400">bed</span>
                                        <span><strong>{{ $homestay->kamar_count }}</strong> Kamar</span>
                                    </div>
                                    <div class="h-3 w-px bg-stone-200"></div>
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base text-stone-400">hiking</span>
                                        <span><strong>{{ $homestay->paket_count }}</strong> Paket</span>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between pt-3 border-t border-stone-200/60 text-xs">
                                    <a
                                        href="{{ route('pemilik.homestay.kamar.index', $homestay) }}"
                                        class="font-bold text-[#13391b] hover:underline inline-flex items-center gap-1"
                                    >
                                        <span>Atur Kamar</span>
                                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                    </a>
                                    <div class="flex items-center gap-2">
                                        <a
                                            href="{{ route('pemilik.homestay.paket.index', $homestay) }}"
                                            class="text-stone-500 hover:text-stone-800"
                                            title="Kelola Paket Wisata"
                                        >
                                            Paket
                                        </a>
                                        <span class="text-stone-300">&bull;</span>
                                        <a
                                            href="{{ route('pemilik.homestay.edit', $homestay) }}"
                                            class="text-stone-500 hover:text-stone-800"
                                            title="Ubah Profil Homestay"
                                        >
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Section 2: Pesanan Tamu Terbaru -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-5">
                    <div>
                        <h3 class="text-lg font-extrabold text-stone-900">Reservasi Tamu Terbaru</h3>
                        <p class="text-xs text-stone-500">Pantau booking yang baru masuk ke homestay Anda.</p>
                    </div>
                    <a
                        href="{{ route('pemilik.booking.index') }}"
                        class="text-xs font-bold text-[#13391b] hover:text-[#ea580c] transition-colors"
                    >
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if ($bookings->isEmpty())
                    <div class="text-center py-10 px-4">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-stone-100 text-stone-400 mb-3">
                            <span class="material-symbols-outlined text-3xl">inbox</span>
                        </div>
                        <h4 class="text-base font-bold text-stone-900">Belum Ada Pesanan Masuk</h4>
                        <p class="mt-1 text-xs text-stone-500 max-w-sm mx-auto">
                            Pesanan wisatawan akan muncul di sini secara otomatis setelah homestay Anda aktif di katalog desa wisata.
                        </p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($bookings as $booking)
                            <div class="rounded-2xl border border-stone-200 bg-stone-50/40 p-4 hover:bg-white hover:border-emerald-200 transition-all flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-start gap-3 min-w-0">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-[#13391b] font-extrabold text-sm shadow-2xs">
                                        {{ strtoupper(substr($booking->wisatawan->nama_wisatawan ?? 'W', 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h4 class="text-sm font-extrabold text-stone-900 truncate">
                                                {{ $booking->wisatawan->nama_wisatawan }}
                                            </h4>
                                            <x-badge :status="$booking->status->value" />
                                        </div>
                                        <p class="mt-0.5 text-xs text-stone-600 truncate">
                                            <span class="font-medium text-[#13391b]">{{ $booking->kamar->homestay->nama_homestay }}</span> &bull; {{ $booking->kamar->nama_kamar }}
                                        </p>
                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-[11px] text-stone-500">
                                            <span class="inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs text-stone-400">calendar_today</span>
                                                <span>{{ tanggal_id($booking->tgl_checkin) }} – {{ tanggal_id($booking->tgl_checkout) }}</span>
                                            </span>
                                            <span>&bull;</span>
                                            <span>{{ $booking->jumlah_tamu }} Tamu ({{ $booking->jumlah_malam }} Malam)</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex sm:flex-col items-center sm:items-end justify-between shrink-0 gap-2 pt-2 sm:pt-0 border-t sm:border-0 border-stone-200">
                                    <p class="text-base font-extrabold text-stone-900">{{ rupiah($booking->total_harga) }}</p>
                                    <a
                                        href="{{ route('pemilik.booking.show', $booking) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-white hover:bg-stone-100 border border-stone-200 text-xs font-bold text-stone-700 hover:text-[#13391b] shadow-2xs transition-all"
                                    >
                                        <span>Tinjau</span>
                                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN (SIDEBAR JADWAL & ULASAN) -->
        <div class="space-y-8">
            <!-- Section 3: Jadwal Kedatangan Tamu Terdekat -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-700 text-lg">event_available</span>
                        <h3 class="text-base font-extrabold text-stone-900">Jadwal Kedatangan Tamu</h3>
                    </div>
                </div>

                @if ($upcomingGuests->isEmpty())
                    <div class="text-center py-6">
                        <span class="material-symbols-outlined text-3xl text-stone-300">calendar_month</span>
                        <p class="mt-2 text-xs text-stone-500">Tidak ada tamu yang check-in dalam waktu dekat.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($upcomingGuests as $upcoming)
                            <div class="rounded-2xl border border-stone-200/80 bg-stone-50/50 p-3.5 flex items-start gap-3">
                                <div class="bg-emerald-100 text-[#13391b] rounded-xl px-2.5 py-1.5 text-center shrink-0">
                                    <p class="text-[10px] font-bold uppercase tracking-wider">{{ \Carbon\Carbon::parse($upcoming->tgl_checkin)->format('M') }}</p>
                                    <p class="text-base font-extrabold leading-none">{{ \Carbon\Carbon::parse($upcoming->tgl_checkin)->format('d') }}</p>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-stone-900 truncate">{{ $upcoming->wisatawan->nama_wisatawan }}</p>
                                    <p class="text-[11px] text-stone-500 truncate">{{ $upcoming->kamar->homestay->nama_homestay }}</p>
                                    <p class="mt-0.5 text-[10px] text-emerald-800 font-semibold">
                                        Check-out: {{ tanggal_id($upcoming->tgl_checkout) }} ({{ $upcoming->jumlah_malam }} malam)
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Section 4: Ulasan & Kepuasan Tamu Terbaru -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500 text-lg">star</span>
                        <h3 class="text-base font-extrabold text-stone-900">Ulasan Tamu Homestay</h3>
                    </div>
                </div>

                @if ($recentReviews->isEmpty())
                    <div class="text-center py-6">
                        <span class="material-symbols-outlined text-3xl text-stone-300">rate_review</span>
                        <p class="mt-2 text-xs text-stone-500">Belum ada ulasan yang ditinggalkan tamu.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($recentReviews as $review)
                            <div class="rounded-2xl border border-stone-200/80 bg-stone-50/50 p-3.5">
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <p class="text-xs font-bold text-stone-900 truncate">
                                        {{ $review->booking->wisatawan->nama_wisatawan ?? 'Wisatawan' }}
                                    </p>
                                    <div class="flex items-center text-amber-500 text-xs">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined text-xs {{ $i <= $review->rating ? 'fill-current' : 'text-stone-300' }}">star</span>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-xs text-stone-600 italic line-clamp-2 leading-relaxed">
                                    “{{ $review->komentar }}”
                                </p>
                                <p class="mt-1 text-[10px] text-stone-400">
                                    {{ $review->booking->kamar->homestay->nama_homestay ?? 'Homestay' }} &bull; {{ tanggal_id($review->tgl_ulasan) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Section 5: Pedoman Keramahan Pokdarwis -->
            <div class="rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50/70 to-white p-6 shadow-xs">
                <div class="flex items-center gap-2 mb-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-white">
                        <span class="material-symbols-outlined text-base">recommend</span>
                    </span>
                    <h3 class="text-sm font-extrabold text-[#13391b]">Standar Sambutan Pokdarwis</h3>
                </div>
                <ul class="space-y-2.5 text-xs text-stone-600">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-emerald-600 text-sm mt-0.5 shrink-0">check_circle</span>
                        <span>Pastikan kamar dibersihkan & sprei diganti sebelum waktu check-in (14.00 WIB).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-emerald-600 text-sm mt-0.5 shrink-0">check_circle</span>
                        <span>Sediakan welcome drink minuman tradisional khas desa untuk menghangatkan tamu.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-emerald-600 text-sm mt-0.5 shrink-0">check_circle</span>
                        <span>Segera konfirmasi pesanan agar tamu merasa tenang dan diperhatikan.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
