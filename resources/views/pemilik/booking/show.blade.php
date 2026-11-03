@extends('layouts.dashboard')

@section('title', 'Detail Pesanan')
@section('page_title', 'Pesanan #'.$booking->id_booking)
@section('page_description', 'Periksa detail tamu dan status pembayaran sebelum mengambil tindakan.')

@section('page_actions')
    <x-button :href="route('pemilik.booking.index')" variant="secondary">Kembali ke daftar</x-button>
@endsection

@section('dashboard')
    @php
        $homestay = $booking->kamar->homestay;
        $hasVerifiedPayment = $booking->pembayaran->contains(fn ($payment) => $payment->status_verifikasi->value === 'terverifikasi');
    @endphp

    <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <div class="space-y-6">
            <x-card padding="p-0">
                <div class="flex flex-col gap-3 border-b border-sand-200 p-5 sm:flex-row sm:items-start sm:justify-between sm:p-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-clay-600">Pesanan #{{ $booking->id_booking }}</p>
                        <h2 class="mt-1 font-display text-2xl font-semibold text-forest-950">{{ $homestay->nama_homestay }}</h2>
                        <p class="mt-1 text-sm text-stone-500">{{ $booking->kamar->nama_kamar }}</p>
                    </div>
                    <x-badge :status="$booking->status->value" class="self-start" />
                </div>

                <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Tamu</p>
                        <p class="mt-1 font-semibold text-forest-950">{{ $booking->wisatawan->nama_wisatawan }}</p>
                        <p class="mt-0.5 text-sm text-stone-500">{{ $booking->wisatawan->no_hp }} · {{ $booking->wisatawan->asal_kota }}</p>
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
                </div>

                @if ($booking->paket->isNotEmpty())
                    <div class="border-t border-sand-100 p-5 sm:p-6">
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Paket wisata</p>
                        <ul class="mt-3 space-y-2">
                            @foreach ($booking->paket as $package)
                                <li class="flex items-center justify-between gap-3 rounded-2xl bg-sand-50 px-4 py-3 text-sm">
                                    <div>
                                        <p class="font-semibold text-forest-950">{{ $package->nama_paket }}</p>
                                        <p class="text-xs text-stone-500">{{ $package->pivot->jumlah_peserta }} peserta · {{ $package->durasi }}</p>
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
                    <h2 class="font-display text-xl font-semibold text-forest-950">Pembayaran</h2>
                    <p class="mt-1 text-sm text-stone-500">Bukti transfer hanya dapat dibuka oleh admin desa dan wisatawan terkait.</p>
                </div>

                @if ($booking->pembayaran->isEmpty())
                    <div class="p-5 sm:p-6">
                        <p class="text-sm text-stone-500">Tamu belum mengunggah bukti pembayaran.</p>
                    </div>
                @else
                    <ul class="divide-y divide-sand-100">
                        @foreach ($booking->pembayaran as $payment)
                            <li class="flex flex-col gap-2 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="font-semibold text-forest-950">{{ rupiah($payment->jumlah) }}</p>
                                        <x-badge :status="$payment->status_verifikasi->value" />
                                    </div>
                                    <p class="mt-1 text-sm text-stone-500">{{ $payment->metode }} · {{ $payment->tgl_bayar->format('d/m/Y H:i') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>

            @if ($booking->ulasan)
                <x-card>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h2 class="font-display text-xl font-semibold text-forest-950">Ulasan tamu</h2>
                        <x-rating-stars :rating="$booking->ulasan->rating" show-value />
                    </div>
                    <p class="mt-3 text-sm leading-relaxed text-stone-600">{{ $booking->ulasan->komentar }}</p>
                </x-card>
            @endif
        </div>

        <aside class="space-y-4 lg:sticky lg:top-28">
            <x-card>
                <h2 class="font-display text-xl font-semibold text-forest-950">Tindakan</h2>

                @if ($booking->status->value === 'menunggu_verifikasi' && ! $hasVerifiedPayment)
                    <div class="mt-3">
                        <x-alert type="info">Pembayaran tamu belum diverifikasi admin. Konfirmasi tersedia setelah pembayaran terverifikasi.</x-alert>
                    </div>
                @endif

                <div class="mt-4 space-y-3">
                    @can('confirm', $booking)
                        <x-confirm-button
                            :action="route('pemilik.booking.confirm', $booking)"
                            method="PATCH"
                            label="Konfirmasi Pesanan"
                            title="Konfirmasi pesanan ini?"
                            message="Pastikan pembayaran sudah terverifikasi. Setelah dikonfirmasi, tamu akan datang sesuai tanggal check-in."
                            confirm-label="Ya, konfirmasi"
                            variant="primary"
                        />
                    @endcan

                    @can('complete', $booking)
                        <x-confirm-button
                            :action="route('pemilik.booking.complete', $booking)"
                            method="PATCH"
                            label="Tandai Selesai"
                            title="Tandai pesanan selesai?"
                            message="Tandai selesai setelah tanggal check-out. Tamu akan dapat menulis ulasan."
                            confirm-label="Ya, selesaikan"
                            variant="primary"
                        />
                    @endcan

                    @can('reject', $booking)
                        <x-confirm-button
                            :action="route('pemilik.booking.reject', $booking)"
                            method="PATCH"
                            label="Tolak Pesanan"
                            title="Tolak pesanan ini?"
                            message="Pesanan akan ditolak dan kamar kembali tersedia. Pastikan Anda sudah menghubungi tamu jika perlu."
                            confirm-label="Ya, tolak pesanan"
                            variant="danger"
                        />
                    @endcan

                    @if (! auth()->user()->can('confirm', $booking) && ! auth()->user()->can('complete', $booking) && ! auth()->user()->can('reject', $booking))
                        <p class="text-sm text-stone-500">Tidak ada tindakan yang tersedia untuk status pesanan ini.</p>
                    @endif
                </div>
            </x-card>

            <x-card class="!bg-forest-900 !border-forest-800 text-sand-50">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-sand-300">Total pesanan</p>
                <p class="mt-2 font-display text-3xl font-semibold text-white">{{ rupiah($booking->total_harga) }}</p>
                <p class="mt-3 text-xs leading-relaxed text-sand-200/70">
                    Total sudah termasuk kamar dan paket wisata yang dipilih tamu. Nilai ini tersimpan sebagai snapshot saat pemesanan.
                </p>
            </x-card>
        </aside>
    </div>
@endsection
