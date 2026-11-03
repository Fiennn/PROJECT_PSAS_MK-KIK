@extends('layouts.dashboard')

@section('title', 'Pendapatan')
@section('page_title', 'Pendapatan')
@section('page_description', 'Rekap pendapatan dari pesanan yang sudah dikonfirmasi dan selesai.')

@section('dashboard')
    <x-card>
        <form method="GET" action="{{ route('pemilik.pendapatan') }}" class="grid gap-4 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
            <div>
                <label for="dari" class="label">Dari tanggal check-in</label>
                <input id="dari" type="date" name="dari" value="{{ $dari->toDateString() }}" class="field">
            </div>
            <div>
                <label for="sampai" class="label">Sampai tanggal check-in</label>
                <input id="sampai" type="date" name="sampai" value="{{ $sampai->toDateString() }}" class="field">
            </div>
            <div class="flex gap-2">
                <x-button type="submit">Terapkan</x-button>
                <x-button :href="route('pemilik.pendapatan')" variant="secondary">Bulan Ini</x-button>
            </div>
        </form>
        @error('sampai')
            <p class="error-text">{{ $message }}</p>
        @enderror
    </x-card>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <x-stat-card label="Total Pendapatan" :value="rupiah($totals['pendapatan'])" hint="Dikonfirmasi & selesai" tone="forest" />
        <x-stat-card label="Jumlah Pesanan" :value="$totals['booking']" hint="Pesanan" tone="sky" />
        <x-stat-card label="Total Malam" :value="$totals['malam']" hint="Malam terjual" tone="sand" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[0.8fr_1.2fr] xl:items-start">
        <x-card padding="p-0">
            <div class="border-b border-sand-200 p-5">
                <h2 class="font-display text-xl font-semibold text-forest-950">Per homestay</h2>
                <p class="mt-1 text-sm text-stone-500">Pendapatan pada rentang tanggal yang dipilih.</p>
            </div>

            @if ($perHomestay->isEmpty())
                <div class="p-5">
                    <p class="text-sm text-stone-500">Belum ada pendapatan pada rentang ini.</p>
                </div>
            @else
                <ul class="divide-y divide-sand-100">
                    @foreach ($perHomestay as $row)
                        <li class="flex items-center justify-between gap-3 p-5">
                            <div>
                                <p class="font-semibold text-forest-950">{{ $row['homestay'] }}</p>
                                <p class="mt-0.5 text-xs text-stone-400">{{ $row['booking'] }} pesanan</p>
                            </div>
                            <p class="font-display text-lg font-semibold text-forest-900">{{ rupiah($row['pendapatan']) }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>

        <x-card padding="p-0">
            <div class="border-b border-sand-200 p-5">
                <h2 class="font-display text-xl font-semibold text-forest-950">Rincian pesanan</h2>
                <p class="mt-1 text-sm text-stone-500">Pesanan dikonfirmasi dan selesai pada rentang tanggal.</p>
            </div>

            @if ($bookings->isEmpty())
                <div class="p-5">
                    <x-empty-state title="Belum ada pesanan" description="Pendapatan akan muncul setelah pesanan dikonfirmasi atau selesai." />
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-sand-50 text-xs uppercase tracking-wider text-stone-500">
                            <tr>
                                <th class="px-5 py-3 font-semibold">Tamu</th>
                                <th class="px-5 py-3 font-semibold">Homestay</th>
                                <th class="px-5 py-3 font-semibold">Check-in</th>
                                <th class="px-5 py-3 text-right font-semibold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sand-100">
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td class="px-5 py-3">
                                        <p class="font-semibold text-forest-950">{{ $booking->wisatawan->nama_wisatawan }}</p>
                                        <p class="text-xs text-stone-400">{{ $booking->kamar->nama_kamar }}</p>
                                    </td>
                                    <td class="px-5 py-3 text-stone-600">{{ $booking->kamar->homestay->nama_homestay }}</td>
                                    <td class="px-5 py-3 text-stone-600">{{ tanggal_id($booking->tgl_checkin) }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-forest-900">{{ rupiah($booking->total_harga) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t border-sand-200 bg-sand-50">
                            <tr>
                                <td colspan="3" class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider text-stone-500">Total halaman ini</td>
                                <td class="px-5 py-3 text-right font-display text-lg font-semibold text-forest-900">{{ rupiah($bookings->sum('total_harga')) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="px-5 pb-5">
                    <x-pagination :paginator="$bookings" />
                </div>
            @endif
        </x-card>
    </div>
@endsection
