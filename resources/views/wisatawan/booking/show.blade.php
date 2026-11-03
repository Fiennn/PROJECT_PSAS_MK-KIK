@extends('layouts.dashboard')

@section('title', 'Detail Pemesanan')
@section('page_title', 'Detail Pemesanan')

@section('page_actions')
    <x-button :href="route('wisatawan.booking.index')" variant="secondary">Kembali ke daftar</x-button>
@endsection

@section('dashboard')
    @php
        $homestay = $booking->kamar->homestay;
        $latestPayment = $booking->pembayaran->first();
        $hasPendingPayment = $booking->pembayaran->contains(fn ($payment) => $payment->status_verifikasi->value === 'menunggu');
    @endphp

    <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <div class="space-y-6">
            <x-card padding="p-0">
                <div class="flex flex-col gap-3 border-b border-sand-200 p-5 sm:flex-row sm:items-start sm:justify-between sm:p-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-clay-600">Pemesanan #{{ $booking->id_booking }}</p>
                        <h2 class="mt-1 font-display text-2xl font-semibold text-forest-950">{{ $homestay->nama_homestay }}</h2>
                        <p class="mt-1 text-sm text-stone-500">{{ $homestay->alamat }}</p>
                    </div>
                    <x-badge :status="$booking->status->value" class="self-start" />
                </div>

                <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Kamar</p>
                        <p class="mt-1 font-semibold text-forest-950">{{ $booking->kamar->nama_kamar }}</p>
                        <p class="mt-0.5 text-sm text-stone-500">Maks. {{ $booking->kamar->kapasitas }} tamu</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Jumlah tamu</p>
                        <p class="mt-1 font-semibold text-forest-950">{{ $booking->jumlah_tamu }} orang</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Check-in</p>
                        <p class="mt-1 font-semibold text-forest-950">{{ tanggal_id($booking->tgl_checkin) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Check-out</p>
                        <p class="mt-1 font-semibold text-forest-950">{{ tanggal_id($booking->tgl_checkout) }}</p>
                        <p class="mt-0.5 text-xs text-stone-400">{{ $booking->jumlah_malam }} malam</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Dipesan pada</p>
                        <p class="mt-1 font-semibold text-forest-950">{{ tanggal_id($booking->tgl_booking) }}</p>
                    </div>
                </div>

                @if ($booking->paket->isNotEmpty())
                    <div class="border-t border-sand-100 p-5 sm:p-6">
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Paket wisata</p>
                        <ul class="mt-3 space-y-2">
                            @foreach ($booking->paket as $package)
                                <li class="flex items-center justify-between gap-3 rounded-2xl bg-sand-50 px-4 py-3 text-sm">
                                    <div>
                                        <p class="font-semibold text-forest-950">{{ $package->nama_paket }}</p>
                                        <p class="text-xs text-stone-500">{{ $package->pivot->jumlah_peserta }} peserta</p>
                                    </div>
                                    <p class="font-semibold text-forest-900">{{ rupiah($package->pivot->subtotal) }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </x-card>

            <x-card padding="p-0">
                <div class="border-b border-sand-200 p-5 sm:p-6">
                    <h2 class="font-display text-xl font-semibold text-forest-950">Riwayat pembayaran</h2>
                    <p class="mt-1 text-sm text-stone-500">Bukti transfer diverifikasi oleh admin desa wisata.</p>
                </div>

                @if ($booking->pembayaran->isEmpty())
                    <div class="p-5 sm:p-6">
                        <p class="text-sm text-stone-500">Belum ada bukti pembayaran yang diunggah.</p>
                    </div>
                @else
                    <ul class="divide-y divide-sand-100">
                        @foreach ($booking->pembayaran as $payment)
                            <li class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="font-semibold text-forest-950">{{ rupiah($payment->jumlah) }}</p>
                                        <x-badge :status="$payment->status_verifikasi->value" />
                                    </div>
                                    <p class="mt-1 text-sm text-stone-500">{{ $payment->metode }} · {{ $payment->tgl_bayar->format('d/m/Y H:i') }}</p>
                                </div>
                                <a href="{{ route('pembayaran.bukti', $payment) }}" target="_blank" rel="noopener" class="focus-ring inline-flex items-center gap-1.5 rounded-full border border-sand-300 bg-white px-4 py-2 text-xs font-bold text-forest-800 hover:bg-sand-100">
                                    Lihat bukti
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>

            @can('uploadPayment', $booking)
                <x-card>
                    <h2 class="font-display text-xl font-semibold text-forest-950">Unggah bukti pembayaran</h2>
                    <p class="mt-1 text-sm text-stone-500">
                        Transfer sebesar <span class="font-semibold text-forest-900">{{ rupiah($booking->total_harga) }}</span>, lalu unggah buktinya.
                        Format JPG, PNG, atau PDF maksimal 2 MB.
                    </p>

                    @if ($latestPayment && $latestPayment->status_verifikasi->value === 'ditolak')
                        <div class="mt-4">
                            <x-alert type="warning">
                                Bukti pembayaran sebelumnya ditolak. Silakan unggah bukti transfer yang benar agar pesanan dapat diproses.
                            </x-alert>
                        </div>
                    @endif

                    @if ($hasPendingPayment)
                        <div class="mt-4">
                            <x-alert type="info">Bukti pembayaran Anda sedang menunggu verifikasi admin desa.</x-alert>
                        </div>
                    @else
                        <form method="POST" action="{{ route('wisatawan.booking.pembayaran', $booking) }}" enctype="multipart/form-data" class="mt-5 space-y-5">
                            @csrf

                            <x-form.select name="metode" label="Metode pembayaran" required>
                                <option value="">Pilih metode</option>
                                @foreach (['Transfer Bank', 'QRIS', 'E-Wallet'] as $method)
                                    <option value="{{ $method }}" @selected(old('metode') === $method)>{{ $method }}</option>
                                @endforeach
                            </x-form.select>

                            <div>
                                <label for="bukti_transfer" class="label">Bukti transfer <span class="text-clay-600" aria-hidden="true">*</span></label>
                                <input
                                    id="bukti_transfer"
                                    type="file"
                                    name="bukti_transfer"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    required
                                    class="field file:mr-4 file:rounded-full file:border-0 file:bg-forest-50 file:px-4 file:py-2 file:text-xs file:font-bold file:text-forest-800"
                                >
                                @error('bukti_transfer')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                                <p class="hint">Pastikan nominal dan nama pengirim terlihat jelas.</p>
                            </div>

                            <x-button type="submit">Kirim Bukti Pembayaran</x-button>
                        </form>
                    @endif
                </x-card>
            @endcan

            @if ($booking->ulasan)
                <x-card>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h2 class="font-display text-xl font-semibold text-forest-950">Ulasan Anda</h2>
                        <x-rating-stars :rating="$booking->ulasan->rating" show-value />
                    </div>
                    <p class="mt-3 text-sm leading-relaxed text-stone-600">{{ $booking->ulasan->komentar }}</p>
                    <p class="mt-2 text-xs text-stone-400">Diulas {{ tanggal_id($booking->ulasan->tgl_ulasan) }}</p>
                </x-card>
            @endif
        </div>

        <aside class="space-y-4 lg:sticky lg:top-28">
            <x-card>
                <h2 class="font-display text-xl font-semibold text-forest-950">Ringkasan biaya</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-stone-600">
                            {{ $booking->kamar->nama_kamar }}<br>
                            <span class="text-xs text-stone-400">{{ rupiah($booking->kamar->harga_per_malam) }} × {{ $booking->jumlah_malam }} malam</span>
                        </dt>
                        <dd class="font-semibold text-forest-950">{{ rupiah((float) $booking->kamar->harga_per_malam * $booking->jumlah_malam) }}</dd>
                    </div>
                    @if ($booking->paket->isNotEmpty())
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-stone-600">Paket wisata</dt>
                            <dd class="font-semibold text-forest-950">{{ rupiah($booking->paket->sum(fn ($package) => (float) $package->pivot->subtotal)) }}</dd>
                        </div>
                    @endif
                    <div class="border-t border-sand-200 pt-3">
                        <div class="flex items-end justify-between gap-3">
                            <dt class="font-semibold text-forest-950">Total</dt>
                            <dd class="font-display text-2xl font-semibold text-forest-900">{{ rupiah($booking->total_harga) }}</dd>
                        </div>
                    </div>
                </dl>

                <div class="mt-5 space-y-3 border-t border-sand-100 pt-5">
                    @can('review', $booking)
                        <x-button :href="route('wisatawan.ulasan.create', $booking)" class="w-full">Tulis Ulasan</x-button>
                    @endcan

                    @can('cancel', $booking)
                        <x-confirm-button
                            :action="route('wisatawan.booking.cancel', $booking)"
                            method="PATCH"
                            label="Batalkan Pemesanan"
                            title="Batalkan pemesanan ini?"
                            message="Pemesanan yang dibatalkan tidak dapat dikembalikan. Kamar akan tersedia kembali untuk tamu lain."
                            confirm-label="Ya, batalkan"
                            class="w-full"
                        />
                    @endcan
                </div>
            </x-card>

            <x-card class="!bg-forest-900 !border-forest-800 text-sand-50">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-sand-300">Pengelola homestay</p>
                <h2 class="mt-2 font-display text-xl font-semibold text-white">{{ $homestay->pemilik->nama_pemilik }}</h2>
                <p class="mt-2 text-sm leading-relaxed text-sand-100/75">{{ $homestay->pemilik->no_hp }}</p>
                <p class="mt-3 text-xs leading-relaxed text-sand-200/70">
                    Hubungi pemilik jika Anda perlu mengatur waktu kedatangan atau kebutuhan khusus.
                </p>
            </x-card>
        </aside>
    </div>
@endsection
