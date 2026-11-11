@extends('layouts.dashboard')

@section('title', 'Laporan Omset & Pendapatan')
@section('page_title', 'Laporan Omset & Pendapatan')
@section('page_description', 'Rekapitulasi pendapatan dari seluruh reservasi homestay yang berstatus dikonfirmasi dan selesai.')

@section('page_actions')
    <button
        type="button"
        onclick="window.print()"
        class="no-print inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all cursor-pointer"
    >
        <span class="material-symbols-outlined text-sm text-[#13391b]">print</span>
        <span>Cetak Laporan</span>
    </button>
@endsection

@section('dashboard')
    <!-- Filter Rentang Tanggal -->
    <div class="rounded-2xl border border-stone-200 bg-white p-4 sm:p-5 shadow-xs mb-6 no-print">
        <form method="GET" action="{{ route('pemilik.pendapatan') }}" class="grid gap-3 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
            <div>
                <label for="dari" class="block text-xs font-bold text-stone-700 mb-1">Dari Tanggal Check-in</label>
                <input
                    id="dari"
                    type="date"
                    name="dari"
                    value="{{ $dari->toDateString() }}"
                    class="w-full text-xs sm:text-sm px-3.5 py-2 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none"
                >
            </div>
            <div>
                <label for="sampai" class="block text-xs font-bold text-stone-700 mb-1">Sampai Tanggal Check-in</label>
                <input
                    id="sampai"
                    type="date"
                    name="sampai"
                    value="{{ $sampai->toDateString() }}"
                    class="w-full text-xs sm:text-sm px-3.5 py-2 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none"
                >
            </div>
            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
                >
                    <span class="material-symbols-outlined text-sm">filter_alt</span>
                    <span>Terapkan</span>
                </button>
                <a
                    href="{{ route('pemilik.pendapatan') }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-stone-200 bg-stone-50 hover:bg-stone-100 text-stone-700 text-xs font-semibold transition-all"
                >
                    Bulan Ini
                </a>
            </div>
        </form>
        @error('sampai')
            <p class="mt-2 text-xs text-red-600 font-semibold">{{ $message }}</p>
        @enderror
    </div>

    <!-- 3 KPI Metrics Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-8">
        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-xs">
            <div class="flex items-center justify-between text-stone-500">
                <span class="text-xs font-bold uppercase tracking-wider">Total Pendapatan</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-[#13391b]">
                    <span class="material-symbols-outlined text-lg">account_balance_wallet</span>
                </span>
            </div>
            <p class="mt-3 text-2xl sm:text-3xl font-extrabold text-[#13391b]">{{ rupiah($totals['pendapatan']) }}</p>
            <p class="mt-1 text-xs text-stone-400">Periode: {{ tanggal_id($dari) }} – {{ tanggal_id($sampai) }}</p>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-xs">
            <div class="flex items-center justify-between text-stone-500">
                <span class="text-xs font-bold uppercase tracking-wider">Pesanan Sukses</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                    <span class="material-symbols-outlined text-lg">verified</span>
                </span>
            </div>
            <p class="mt-3 text-2xl sm:text-3xl font-extrabold text-stone-900">{{ $totals['booking'] }} <span class="text-sm font-normal text-stone-400">transaksi</span></p>
            <p class="mt-1 text-xs text-stone-400">Dikonfirmasi & selesai</p>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-xs">
            <div class="flex items-center justify-between text-stone-500">
                <span class="text-xs font-bold uppercase tracking-wider">Malam Terjual</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-[#ea580c]">
                    <span class="material-symbols-outlined text-lg">nights_stay</span>
                </span>
            </div>
            <p class="mt-3 text-2xl sm:text-3xl font-extrabold text-[#ea580c]">{{ $totals['malam'] }} <span class="text-sm font-normal text-stone-400">malam</span></p>
            <p class="mt-1 text-xs text-stone-400">Okupansi penginapan</p>
        </div>
    </div>

    <!-- Grid Detail: Distribusi Per Homestay & Rincian Pesanan -->
    <div class="grid gap-6 xl:grid-cols-[0.8fr_1.2fr] xl:items-start">
        <!-- Distribusi Per Homestay -->
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
            <div class="pb-4 border-b border-stone-100 mb-4">
                <h3 class="text-base font-extrabold text-stone-900">Pendapatan Per Homestay</h3>
                <p class="text-xs text-stone-500">Omset per unit hunian pada rentang tanggal terpilih.</p>
            </div>

            @if ($perHomestay->isEmpty())
                <div class="py-8 text-center text-xs text-stone-400">
                    Belum ada omset transaksi pada rentang tanggal ini.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($perHomestay as $row)
                        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-stone-50/70 border border-stone-200/80">
                            <div>
                                <p class="text-xs font-extrabold text-stone-900">{{ $row['homestay'] }}</p>
                                <p class="text-[11px] text-stone-500">{{ $row['booking'] }} pesanan selesai/aktif</p>
                            </div>
                            <p class="text-sm font-extrabold text-[#13391b]">{{ rupiah($row['pendapatan']) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Tabel Rincian Booking Transaksi -->
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
            <div class="pb-4 border-b border-stone-100 mb-4">
                <h3 class="text-base font-extrabold text-stone-900">Rincian Transaksi Masuk</h3>
                <p class="text-xs text-stone-500">Daftar booking yang telah diverifikasi dan masuk rekap keuangan.</p>
            </div>

            @if ($bookings->isEmpty())
                <div class="py-10 text-center text-xs text-stone-400">
                    Tidak ada transaksi booking pada rentang waktu ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-stone-200 text-stone-400 font-bold uppercase tracking-wider text-[10px]">
                                <th class="pb-3 pr-4">Tamu & Kamar</th>
                                <th class="pb-3 px-3">Homestay</th>
                                <th class="pb-3 px-3">Check-in</th>
                                <th class="pb-3 pl-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach ($bookings as $booking)
                                <tr class="hover:bg-stone-50/50 transition-colors">
                                    <td class="py-3 pr-4">
                                        <a href="{{ route('pemilik.booking.show', $booking) }}" class="font-extrabold text-stone-900 hover:text-[#13391b] transition-colors">
                                            {{ $booking->wisatawan->nama_wisatawan }}
                                        </a>
                                        <p class="text-[11px] text-stone-400">{{ $booking->kamar->nama_kamar }} &bull; #{{ $booking->id_booking }}</p>
                                    </td>
                                    <td class="py-3 px-3 text-stone-700 font-medium">
                                        {{ $booking->kamar->homestay->nama_homestay }}
                                    </td>
                                    <td class="py-3 px-3 text-stone-600">
                                        {{ tanggal_id($booking->tgl_checkin) }}
                                    </td>
                                    <td class="py-3 pl-4 text-right font-extrabold text-[#13391b]">
                                        {{ rupiah($booking->total_harga) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-stone-200 font-bold">
                                <td colspan="3" class="pt-3 text-right uppercase tracking-wider text-[11px] text-stone-500">Total Halaman Ini</td>
                                <td class="pt-3 pl-4 text-right text-sm font-extrabold text-[#13391b]">
                                    {{ rupiah($bookings->sum('total_harga')) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-4 pt-3 border-t border-stone-100 no-print">
                    <x-pagination :paginator="$bookings" />
                </div>
            @endif
        </div>
    </div>
@endsection
