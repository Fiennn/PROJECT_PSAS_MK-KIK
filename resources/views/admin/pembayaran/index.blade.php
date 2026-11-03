@extends('layouts.dashboard')

@section('title', 'Verifikasi Pembayaran')
@section('page_title', 'Verifikasi Pembayaran')
@section('page_description', 'Periksa bukti transfer wisatawan sebelum pemilik homestay mengonfirmasi pesanan.')

@section('dashboard')
    <x-card>
        <form method="GET" action="{{ route('admin.pembayaran.index') }}" class="grid gap-4 sm:grid-cols-[1.4fr_0.7fr_auto] sm:items-end">
            <div>
                <label for="q" class="label">Cari pembayaran</label>
                <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama wisatawan atau homestay..." class="field">
            </div>
            <x-form.select name="status" label="Status verifikasi">
                <option value="">Semua status</option>
                <option value="menunggu" @selected(($filters['status'] ?? '') === 'menunggu')>Menunggu</option>
                <option value="terverifikasi" @selected(($filters['status'] ?? '') === 'terverifikasi')>Terverifikasi</option>
                <option value="ditolak" @selected(($filters['status'] ?? '') === 'ditolak')>Ditolak</option>
            </x-form.select>
            <div class="flex gap-2">
                <x-button type="submit">Terapkan</x-button>
                <x-button :href="route('admin.pembayaran.index')" variant="secondary">Reset</x-button>
            </div>
        </form>
    </x-card>

    @if ($payments->isEmpty())
        <div class="mt-6">
            <x-empty-state title="Tidak ada pembayaran" description="Belum ada bukti pembayaran yang sesuai filter." />
        </div>
    @else
        <x-card padding="p-0" class="mt-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-sand-50 text-xs uppercase tracking-wider text-stone-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Wisatawan</th>
                            <th class="px-5 py-3 font-semibold">Homestay</th>
                            <th class="px-5 py-3 font-semibold">Pembayaran</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 text-right font-semibold">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sand-100">
                        @foreach ($payments as $payment)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-forest-950">{{ $payment->booking->wisatawan->nama_wisatawan }}</p>
                                    <p class="mt-0.5 text-xs text-stone-400">Booking #{{ $payment->id_booking }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-forest-950">{{ $payment->booking->kamar->homestay->nama_homestay }}</p>
                                    <p class="mt-0.5 text-xs text-stone-400">{{ $payment->booking->kamar->nama_kamar }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-forest-900">{{ rupiah($payment->jumlah) }}</p>
                                    <p class="mt-0.5 text-xs text-stone-400">{{ $payment->metode }} · {{ $payment->tgl_bayar->format('d/m/Y H:i') }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <x-badge :status="$payment->status_verifikasi->value" />
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.pembayaran.show', $payment) }}" class="focus-ring inline-flex rounded-full bg-forest-50 px-4 py-2 text-xs font-bold text-forest-800 transition hover:bg-forest-700 hover:text-white">
                                        Tinjau
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-pagination :paginator="$payments" />
    @endif
@endsection
