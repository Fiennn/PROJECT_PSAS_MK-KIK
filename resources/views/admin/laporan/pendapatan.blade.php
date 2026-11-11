@extends('layouts.print')

@section('title', 'Laporan Pendapatan')
@section('report_title', 'Laporan Pendapatan')
@section('report_period', 'Periode Check-in: '.tanggal_id($dari).' – '.tanggal_id($sampai))

@section('content')
    <form method="GET" action="{{ route('admin.laporan.pendapatan') }}" class="no-print mb-6 grid gap-3 rounded-2xl border border-stone-200 bg-stone-50 p-4 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
        <div>
            <label for="dari" class="block text-xs font-bold text-stone-700 mb-1">Dari Tanggal Check-in</label>
            <input id="dari" type="date" name="dari" value="{{ $dari->toDateString() }}" class="w-full text-xs px-3 py-2 rounded-xl border border-stone-300 focus:border-[#13391b] outline-none">
        </div>
        <div>
            <label for="sampai" class="block text-xs font-bold text-stone-700 mb-1">Sampai Tanggal Check-in</label>
            <input id="sampai" type="date" name="sampai" value="{{ $sampai->toDateString() }}" class="w-full text-xs px-3 py-2 rounded-xl border border-stone-300 focus:border-[#13391b] outline-none">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-xl bg-[#13391b] hover:bg-[#0e2c14] px-4 py-2 text-xs font-bold text-white transition-all cursor-pointer">Terapkan</button>
            <a href="{{ route('admin.laporan.pendapatan') }}" class="rounded-xl border border-stone-300 bg-white px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50">Reset</a>
        </div>
    </form>

    <section class="mb-6 grid gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border border-stone-200 p-4 bg-white shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Total Omset Pendapatan</p>
            <p class="mt-1 text-2xl font-extrabold text-[#13391b]">{{ rupiah($total) }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200 p-4 bg-white shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-600">Booking Dihitung</p>
            <p class="mt-1 text-2xl font-extrabold text-stone-900">{{ $bookings->count() }} transaksi</p>
        </div>
        <div class="rounded-2xl border border-stone-200 p-4 bg-white shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Homestay Terlibat</p>
            <p class="mt-1 text-2xl font-extrabold text-stone-900">{{ $perHomestay->count() }} unit</p>
        </div>
    </section>

    <p class="mb-4 text-xs text-stone-400 italic">Catatan: Laporan hanya menghitung reservasi dengan status Dikonfirmasi dan Selesai.</p>

    @if ($bookings->isEmpty())
        <p class="rounded-2xl border border-stone-200 p-8 text-center text-xs text-stone-500 bg-stone-50">Tidak ada data pendapatan pada periode yang dipilih.</p>
    @else
        <h3 class="mb-3 text-sm font-extrabold text-stone-900">Rekapitulasi Omset Per Homestay</h3>
        <table class="mb-8 w-full border-collapse text-left text-xs">
            <thead>
                <tr class="border-b-2 border-stone-300 text-[10px] uppercase tracking-wider text-stone-400 font-bold">
                    <th class="py-2.5 pr-3">Homestay Warga</th>
                    <th class="py-2.5 pr-3 text-center">Jumlah Reservasi</th>
                    <th class="py-2.5 text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach ($perHomestay as $row)
                    <tr class="hover:bg-stone-50/50">
                        <td class="py-2.5 pr-3 font-bold text-stone-900">{{ $row['homestay'] }}</td>
                        <td class="py-2.5 pr-3 text-center text-stone-600 font-medium">{{ $row['booking'] }} pesanan</td>
                        <td class="py-2.5 text-right font-extrabold text-[#13391b]">{{ rupiah($row['pendapatan']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3 class="mb-3 text-sm font-extrabold text-stone-900">Rincian Seluruh Transaksi</h3>
        <table class="w-full border-collapse text-left text-xs">
            <thead>
                <tr class="border-b-2 border-stone-300 text-[10px] uppercase tracking-wider text-stone-400 font-bold">
                    <th class="py-2.5 pr-3">#ID</th>
                    <th class="py-2.5 pr-3">Wisatawan</th>
                    <th class="py-2.5 pr-3">Homestay & Kamar</th>
                    <th class="py-2.5 pr-3">Check-In</th>
                    <th class="py-2.5 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach ($bookings as $b)
                    <tr class="hover:bg-stone-50/50">
                        <td class="py-2.5 pr-3 font-mono text-stone-400">#{{ $b->id_booking }}</td>
                        <td class="py-2.5 pr-3 font-bold text-stone-900">{{ $b->wisatawan->nama_wisatawan }}</td>
                        <td class="py-2.5 pr-3 text-stone-600">{{ $b->kamar->homestay->nama_homestay }} ({{ $b->kamar->nama_kamar }})</td>
                        <td class="py-2.5 pr-3 text-stone-600">{{ tanggal_id($b->tgl_checkin) }}</td>
                        <td class="py-2.5 text-right font-extrabold text-[#13391b]">{{ rupiah($b->total_harga) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-stone-300 font-bold">
                    <td colspan="4" class="py-3 text-right text-[10px] uppercase tracking-wider text-stone-500">Total Akumulasi Periode Ini</td>
                    <td class="py-3 text-right text-sm font-extrabold text-[#13391b]">{{ rupiah($bookings->sum('total_harga')) }}</td>
                </tr>
            </tfoot>
        </table>
    @endif
@endsection
