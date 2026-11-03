@extends('layouts.dashboard')

@section('title', 'Tinjau Pembayaran')
@section('page_title', 'Pembayaran #'.$pembayaran->id_pembayaran)
@section('page_description', 'Cocokkan nominal transfer dengan total pesanan sebelum memverifikasi.')

@section('page_actions')
    <x-button :href="route('admin.pembayaran.index')" variant="secondary">Kembali</x-button>
@endsection

@section('dashboard')
    @php
        $booking = $pembayaran->booking;
        $homestay = $booking->kamar->homestay;
        $isPending = $pembayaran->status_verifikasi->value === 'menunggu';
    @endphp

    <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <div class="space-y-6">
            <x-card>
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-clay-600">Bukti pembayaran</p>
                        <h2 class="mt-1 font-display text-2xl font-semibold text-forest-950">{{ $booking->wisatawan->nama_wisatawan }}</h2>
                        <p class="mt-1 text-sm text-stone-500">{{ $homestay->nama_homestay }} · {{ $booking->kamar->nama_kamar }}</p>
                    </div>
                    <x-badge :status="$pembayaran->status_verifikasi->value" />
                </div>

                <dl class="mt-6 grid gap-5 border-t border-sand-100 pt-5 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Nominal ditransfer</dt>
                        <dd class="mt-1 font-display text-xl font-semibold text-forest-900">{{ rupiah($pembayaran->jumlah) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Total pesanan</dt>
                        <dd class="mt-1 font-display text-xl font-semibold text-forest-900">{{ rupiah($booking->total_harga) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Metode</dt>
                        <dd class="mt-1 font-semibold text-forest-950">{{ $pembayaran->metode }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Tanggal bayar</dt>
                        <dd class="mt-1 font-semibold text-forest-950">{{ $pembayaran->tgl_bayar->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>

                @if ((float) $pembayaran->jumlah !== (float) $booking->total_harga)
                    <div class="mt-5">
                        <x-alert type="warning">
                            Nominal transfer berbeda dengan total pesanan. Pastikan selisih ini memang disepakati sebelum memverifikasi.
                        </x-alert>
                    </div>
                @endif

                <div class="mt-6 border-t border-sand-100 pt-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Berkas bukti</p>
                    <a
                        href="{{ route('pembayaran.bukti', $pembayaran) }}"
                        target="_blank"
                        rel="noopener"
                        class="focus-ring mt-3 inline-flex items-center gap-2 rounded-full border border-sand-300 bg-white px-5 py-2.5 text-sm font-bold text-forest-800 transition hover:bg-sand-100"
                    >
                        Buka bukti transfer
                    </a>
                    <p class="mt-2 text-xs text-stone-400">Berkas bersifat privat dan hanya dapat diakses admin serta wisatawan terkait.</p>
                </div>
            </x-card>

            <x-card>
                <h2 class="font-display text-xl font-semibold text-forest-950">Detail pesanan</h2>
                <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Check-in</dt>
                        <dd class="mt-1 font-semibold text-forest-950">{{ tanggal_id($booking->tgl_checkin) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Check-out</dt>
                        <dd class="mt-1 font-semibold text-forest-950">{{ tanggal_id($booking->tgl_checkout) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Jumlah tamu</dt>
                        <dd class="mt-1 font-semibold text-forest-950">{{ $booking->jumlah_tamu }} orang</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Status pesanan</dt>
                        <dd class="mt-1"><x-badge :status="$booking->status->value" /></dd>
                    </div>
                </dl>

                @if ($booking->paket->isNotEmpty())
                    <div class="mt-5 border-t border-sand-100 pt-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Paket wisata</p>
                        <ul class="mt-2 space-y-1.5 text-sm text-stone-600">
                            @foreach ($booking->paket as $package)
                                <li class="flex justify-between gap-3">
                                    <span>{{ $package->nama_paket }} ({{ $package->pivot->jumlah_peserta }} peserta)</span>
                                    <span class="font-semibold text-forest-900">{{ rupiah($package->pivot->subtotal) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </x-card>
        </div>

        <aside class="space-y-4 lg:sticky lg:top-28">
            <x-card>
                <h2 class="font-display text-xl font-semibold text-forest-950">Keputusan</h2>

                @if ($isPending)
                    <p class="mt-1 text-sm leading-relaxed text-stone-500">
                        Verifikasi jika nominal dan bukti transfer sudah sesuai. Menolak pembayaran akan mengembalikan pesanan ke status menunggu pembayaran.
                    </p>

                    <div class="mt-4 space-y-3">
                        @can('verify', $pembayaran)
                            <x-confirm-button
                                :action="route('admin.pembayaran.verify', $pembayaran)"
                                method="PATCH"
                                label="Verifikasi Pembayaran"
                                title="Verifikasi pembayaran ini?"
                                message="Pemilik homestay akan dapat mengonfirmasi pesanan setelah pembayaran diverifikasi."
                                confirm-label="Ya, verifikasi"
                                variant="primary"
                            />
                        @endcan

                        @can('reject', $pembayaran)
                            <x-confirm-button
                                :action="route('admin.pembayaran.reject', $pembayaran)"
                                method="PATCH"
                                label="Tolak Pembayaran"
                                title="Tolak bukti pembayaran ini?"
                                message="Pesanan akan kembali ke status menunggu pembayaran agar wisatawan dapat mengunggah bukti baru."
                                confirm-label="Ya, tolak"
                                variant="danger"
                            />
                        @endcan
                    </div>
                @else
                    <div class="mt-3">
                        <x-alert :type="$pembayaran->status_verifikasi->value === 'terverifikasi' ? 'success' : 'warning'">
                            @if ($pembayaran->status_verifikasi->value === 'terverifikasi')
                                Pembayaran ini sudah diverifikasi. Pemilik homestay dapat mengonfirmasi pesanan.
                            @else
                                Pembayaran ini ditolak. Wisatawan perlu mengunggah bukti transfer baru.
                            @endif
                        </x-alert>
                    </div>
                @endif
            </x-card>

            <x-card>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-clay-600">Pemilik homestay</p>
                <p class="mt-2 font-semibold text-forest-950">{{ $homestay->pemilik->nama_pemilik }}</p>
                <p class="mt-1 text-sm text-stone-500">{{ $homestay->pemilik->no_hp }}</p>
            </x-card>
        </aside>
    </div>
@endsection
