@extends('layouts.print')

@section('title', 'Laporan Booking')
@section('report_title', 'Laporan Booking')
@section('report_period', tanggal_id($dari).' – '.tanggal_id($sampai))

@section('content')
    <form method="GET" action="{{ route('admin.laporan.booking') }}" class="no-print mb-6 grid gap-3 rounded-2xl border border-stone-200 bg-stone-50 p-4 sm:grid-cols-[1fr_1fr_1fr_auto] sm:items-end">
        <div>
            <label for="dari" class="block text-xs font-bold text-stone-700 mb-1">Dari Tanggal Booking</label>
            <input id="dari" type="date" name="dari" value="{{ $dari->toDateString() }}" class="w-full text-xs px-3 py-2 rounded-xl border border-stone-300 focus:border-[#13391b] outline-none">
        </div>
        <div>
            <label for="sampai" class="block text-xs font-bold text-stone-700 mb-1">Sampai Tanggal Booking</label>
            <input id="sampai" type="date" name="sampai" value="{{ $sampai->toDateString() }}" class="w-full text-xs px-3 py-2 rounded-xl border border-stone-300 focus:border-[#13391b] outline-none">
        </div>
        <div>
            <label for="status" class="block text-xs font-bold text-stone-700 mb-1">Status Reservasi</label>
            <select id="status" name="status" class="w-full text-xs px-3 py-2 rounded-xl border border-stone-300 focus:border-[#13391b] outline-none bg-white">
                <option value="">Semua Status</option>
                @foreach (\App\Enums\StatusBooking::cases() as $case)
                    <option value="{{ $case->value }}" @selected(($filters['status'] ?? '') === $case->value)>{{ $case->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-xl bg-[#13391b] hover:bg-[#0e2c14] px-4 py-2 text-xs font-bold text-white transition-all cursor-pointer">Terapkan</button>
            <a href="{{ route('admin.laporan.booking') }}" class="rounded-xl border border-stone-300 bg-white px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50">Reset</a>
        </div>
    </form>

    <section class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-2xl border border-stone-200 p-4 bg-white shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Total Booking</p>
            <p class="mt-1 text-2xl font-extrabold text-stone-900">{{ $bookings->count() }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200 p-4 bg-white shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-600">Dikonfirmasi</p>
            <p class="mt-1 text-2xl font-extrabold text-sky-800">{{ $statusCounts['dikonfirmasi'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200 p-4 bg-white shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Selesai</p>
            <p class="mt-1 text-2xl font-extrabold text-[#13391b]">{{ $statusCounts['selesai'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200 p-4 bg-white shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Dibatalkan / Ditolak</p>
            <p class="mt-1 text-2xl font-extrabold text-stone-600">{{ ($statusCounts['dibatalkan'] ?? 0) + ($statusCounts['ditolak'] ?? 0) }}</p>
        </div>
    </section>

    @if ($bookings->isEmpty())
        <p class="rounded-2xl border border-stone-200 p-8 text-center text-xs text-stone-500 bg-stone-50">Tidak ada reservasi pada periode ini.</p>
    @else
        <table class="w-full border-collapse text-left text-xs">
            <thead>
                <tr class="border-b-2 border-stone-300 text-[10px] uppercase tracking-wider text-stone-400 font-bold">
                    <th class="py-2.5 pr-3">#ID</th>
                    <th class="py-2.5 pr-3">Tgl Booking</th>
                    <th class="py-2.5 pr-3">Wisatawan</th>
                    <th class="py-2.5 pr-3">Homestay & Kamar</th>
                    <th class="py-2.5 pr-3">Jadwal Menginap</th>
                    <th class="py-2.5 pr-3 text-right">Total Transaksi</th>
                    <th class="py-2.5 pl-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach ($bookings as $booking)
                    <tr class="align-top hover:bg-stone-50/50">
                        <td class="py-2.5 pr-3 font-mono text-stone-400">#{{ $booking->id_booking }}</td>
                        <td class="py-2.5 pr-3 text-stone-600">{{ tanggal_id($booking->tgl_booking) }}</td>
                        <td class="py-2.5 pr-3">
                            <p class="font-bold text-stone-900">{{ $booking->wisatawan->nama_wisatawan }}</p>
                            <p class="text-[10px] text-stone-400">{{ $booking->wisatawan->asal_kota ?: 'Wisatawan' }}</p>
                        </td>
                        <td class="py-2.5 pr-3">
                            <p class="font-semibold text-stone-900">{{ $booking->kamar->homestay->nama_homestay }}</p>
                            <p class="text-[10px] text-stone-400">{{ $booking->kamar->nama_kamar }}</p>
                        </td>
                        <td class="py-2.5 pr-3 text-stone-600">
                            {{ tanggal_id($booking->tgl_checkin) }}<br>
                            <span class="text-[10px] text-stone-400">s.d. {{ tanggal_id($booking->tgl_checkout) }} ({{ $booking->jumlah_malam }} malam)</span>
                        </td>
                        <td class="py-2.5 pr-3 text-right font-extrabold text-[#13391b]">{{ rupiah($booking->total_harga) }}</td>
                        <td class="py-2.5 pl-3 text-stone-600">{{ $booking->status->label() }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-stone-300 font-bold">
                    <td colspan="5" class="py-3 text-right text-[10px] uppercase tracking-wider text-stone-500">Total Nilai Perputaran Booking</td>
                    <td class="py-3 pr-3 text-right text-sm font-extrabold text-[#13391b]">{{ rupiah($bookings->sum('total_harga')) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @endif
@endsection
