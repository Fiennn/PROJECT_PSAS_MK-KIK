@extends('layouts.print')

@section('title', 'Laporan Pendapatan')
@section('report_title', 'Laporan Pendapatan')
@section('report_period', 'Check-in '.tanggal_id($dari).' – '.tanggal_id($sampai))

@section('content')
    <form method="GET" action="{{ route('admin.laporan.pendapatan') }}" class="no-print mb-6 grid gap-3 rounded-2xl border border-stone-200 bg-stone-50 p-4 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
        <div>
            <label for="dari" class="label">Dari tanggal check-in</label>
            <input id="dari" type="date" name="dari" value="{{ $dari->toDateString() }}" class="field">
        </div>
        <div>
            <label for="sampai" class="label">Sampai tanggal check-in</label>
            <input id="sampai" type="date" name="sampai" value="{{ $sampai->toDateString() }}" class="field">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-full bg-forest-700 px-5 py-2.5 text-sm font-semibold text-white">Terapkan</button>
            <a href="{{ route('admin.laporan.pendapatan') }}" class="rounded-full border border-stone-300 bg-white px-5 py-2.5 text-sm font-semibold text-stone-700">Reset</a>
        </div>
    </form>

    <section class="mb-6 grid gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border border-stone-200 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total pendapatan</p>
            <p class="mt-1 font-display text-2xl font-semibold text-forest-950">{{ rupiah($total) }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Booking dihitung</p>
            <p class="mt-1 font-display text-2xl font-semibold text-forest-950">{{ $bookings->count() }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Homestay terlibat</p>
            <p class="mt-1 font-display text-2xl font-semibold text-forest-950">{{ $perHomestay->count() }}</p>
        </div>
    </section>

    <p class="mb-4 text-xs text-stone-500">Hanya booking berstatus dikonfirmasi dan selesai yang dihitung dalam laporan ini.</p>

    @if ($bookings->isEmpty())
        <p class="rounded-2xl border border-stone-200 p-6 text-center text-sm text-stone-500">Tidak ada pendapatan pada periode ini.</p>
    @else
        <h2 class="mb-2 font-display text-lg font-semibold text-forest-950">Rekap per homestay</h2>
        <table class="mb-8 w-full border-collapse text-left text-xs">
            <thead>
                <tr class="border-b-2 border-stone-300 text-[10px] uppercase tracking-wider text-stone-500">
                    <th class="py-2 pr-3 font-semibold">Homestay</th>
                    <th class="py-2 pr-3 text-center font-semibold">Jumlah booking</th>
                    <th class="py-2 text-right font-semibold">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($perHomestay as $row)
                    <tr class="border-b border-stone-200">
                        <td class="py-2 pr-3 font-semibold text-stone-900">{{ $row['homestay'] }}</td>
                        <td class="py-2 pr-3 text-center text-stone-600">{{ $row['booking'] }}</td>
                        <td class="py-2 text-right font-semibold text-stone-900">{{ rupiah($row['pendapatan']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2 class="mb-2 font-display text-lg font-semibold text-forest-950">Rincian booking</h2>
        <table class="w-full border-collapse text-left text-xs">
            <thead>
                <tr class="border-b-2 border-stone-300 text-[10px] uppercase tracking-wider text-stone-500">
                    <th class="py-2 pr-3 font-semibold">#</th>
                    <th class="py-2 pr-3 font-semibold">Wisatawan</th>
                    <th class="py-2 pr-3 font-semibold">Homestay</th>
                    <th class="py-2 pr-3 font-semibold">Check-in</th>
                    <th class="py-2 pr-3 font-semibold">Check-out</th>
                    <th class="py-2 pr-3 text-right font-semibold">Total</th>
                    <th class="py-2 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                    <tr class="border-b border-stone-200 align-top">
                        <td class="py-2 pr-3 text-stone-500">{{ $booking->id_booking }}</td>
                        <td class="py-2 pr-3 font-semibold text-stone-900">{{ $booking->wisatawan->nama_wisatawan }}</td>
                        <td class="py-2 pr-3 text-stone-600">{{ $booking->kamar->homestay->nama_homestay }}</td>
                        <td class="py-2 pr-3 text-stone-600">{{ tanggal_id($booking->tgl_checkin) }}</td>
                        <td class="py-2 pr-3 text-stone-600">{{ tanggal_id($booking->tgl_checkout) }}</td>
                        <td class="py-2 pr-3 text-right font-semibold text-stone-900">{{ rupiah($booking->total_harga) }}</td>
                        <td class="py-2 text-stone-600">{{ $booking->status->label() }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-stone-300">
                    <td colspan="5" class="py-3 text-right text-[10px] font-bold uppercase tracking-wider text-stone-500">Total pendapatan</td>
                    <td class="py-3 pr-3 text-right font-display text-base font-semibold text-forest-900">{{ rupiah($total) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @endif
@endsection
