@extends('layouts.print')

@section('title', 'Laporan Booking')
@section('report_title', 'Laporan Booking')
@section('report_period', tanggal_id($dari).' – '.tanggal_id($sampai))

@section('content')
    <form method="GET" action="{{ route('admin.laporan.booking') }}" class="no-print mb-6 grid gap-3 rounded-2xl border border-stone-200 bg-stone-50 p-4 sm:grid-cols-[1fr_1fr_1fr_auto] sm:items-end">
        <div>
            <label for="dari" class="label">Dari tanggal booking</label>
            <input id="dari" type="date" name="dari" value="{{ $dari->toDateString() }}" class="field">
        </div>
        <div>
            <label for="sampai" class="label">Sampai tanggal booking</label>
            <input id="sampai" type="date" name="sampai" value="{{ $sampai->toDateString() }}" class="field">
        </div>
        <div>
            <label for="status" class="label">Status</label>
            <select id="status" name="status" class="field">
                <option value="">Semua status</option>
                @foreach (\App\Enums\StatusBooking::cases() as $case)
                    <option value="{{ $case->value }}" @selected(($filters['status'] ?? '') === $case->value)>{{ $case->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-full bg-forest-700 px-5 py-2.5 text-sm font-semibold text-white">Terapkan</button>
            <a href="{{ route('admin.laporan.booking') }}" class="rounded-full border border-stone-300 bg-white px-5 py-2.5 text-sm font-semibold text-stone-700">Reset</a>
        </div>
    </form>

    <section class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-2xl border border-stone-200 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total booking</p>
            <p class="mt-1 font-display text-2xl font-semibold text-forest-950">{{ $bookings->count() }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Dikonfirmasi</p>
            <p class="mt-1 font-display text-2xl font-semibold text-forest-950">{{ $statusCounts['dikonfirmasi'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Selesai</p>
            <p class="mt-1 font-display text-2xl font-semibold text-forest-950">{{ $statusCounts['selesai'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-stone-200 p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Dibatalkan / ditolak</p>
            <p class="mt-1 font-display text-2xl font-semibold text-forest-950">{{ ($statusCounts['dibatalkan'] ?? 0) + ($statusCounts['ditolak'] ?? 0) }}</p>
        </div>
    </section>

    @if ($bookings->isEmpty())
        <p class="rounded-2xl border border-stone-200 p-6 text-center text-sm text-stone-500">Tidak ada booking pada periode ini.</p>
    @else
        <table class="w-full border-collapse text-left text-xs">
            <thead>
                <tr class="border-b-2 border-stone-300 text-[10px] uppercase tracking-wider text-stone-500">
                    <th class="py-2 pr-3 font-semibold">#</th>
                    <th class="py-2 pr-3 font-semibold">Tanggal Booking</th>
                    <th class="py-2 pr-3 font-semibold">Wisatawan</th>
                    <th class="py-2 pr-3 font-semibold">Homestay</th>
                    <th class="py-2 pr-3 font-semibold">Menginap</th>
                    <th class="py-2 pr-3 text-right font-semibold">Total</th>
                    <th class="py-2 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                    <tr class="border-b border-stone-200 align-top">
                        <td class="py-2 pr-3 text-stone-500">{{ $booking->id_booking }}</td>
                        <td class="py-2 pr-3 text-stone-600">{{ tanggal_id($booking->tgl_booking) }}</td>
                        <td class="py-2 pr-3">
                            <p class="font-semibold text-stone-900">{{ $booking->wisatawan->nama_wisatawan }}</p>
                            <p class="text-[10px] text-stone-500">{{ $booking->wisatawan->asal_kota }}</p>
                        </td>
                        <td class="py-2 pr-3">
                            <p class="text-stone-900">{{ $booking->kamar->homestay->nama_homestay }}</p>
                            <p class="text-[10px] text-stone-500">{{ $booking->kamar->nama_kamar }}</p>
                        </td>
                        <td class="py-2 pr-3 text-stone-600">
                            {{ tanggal_id($booking->tgl_checkin) }}<br>
                            <span class="text-[10px] text-stone-500">s.d. {{ tanggal_id($booking->tgl_checkout) }}</span>
                        </td>
                        <td class="py-2 pr-3 text-right font-semibold text-stone-900">{{ rupiah($booking->total_harga) }}</td>
                        <td class="py-2 text-stone-600">{{ $booking->status->label() }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-stone-300">
                    <td colspan="5" class="py-3 text-right text-[10px] font-bold uppercase tracking-wider text-stone-500">Total nilai booking</td>
                    <td class="py-3 pr-3 text-right font-display text-base font-semibold text-forest-900">{{ rupiah($bookings->sum('total_harga')) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @endif
@endsection
