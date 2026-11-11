@extends('layouts.dashboard')

@section('title', 'Detail Pesanan #'.$booking->id_booking)
@section('page_title', 'Detail Reservasi #'.$booking->id_booking)
@section('page_description', 'Tinjau rincian tamu, jadwal menginap, serta status verifikasi pembayaran sebelum mengambil tindakan konfirmasi.')

@section('page_actions')
    <a
        href="{{ route('pemilik.booking.index') }}"
        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
    >
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Kembali ke Daftar Booking</span>
    </a>
@endsection

@section('dashboard')
    @php
        $homestay = $booking->kamar->homestay;
        $hasVerifiedPayment = $booking->pembayaran->contains(fn ($payment) => $payment->status_verifikasi->value === 'terverifikasi');
        $cleanPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $booking->wisatawan->no_hp ?? ''));
        $waMessage = urlencode("Halo {$booking->wisatawan->nama_wisatawan}, kami dari pengelola {$homestay->nama_homestay} desa wisata ingin menyapa terkait pesanan Anda (#{$booking->id_booking}).");
    @endphp

    <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <div class="space-y-6">
            <!-- Kartu Rincian Tamu & Reservasi -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
                <!-- Header Card -->
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 pb-6 border-b border-stone-100">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-[11px] font-bold uppercase tracking-wider mb-2">
                            Homestay Mitra Pokdarwis
                        </span>
                        <h2 class="text-2xl font-extrabold text-stone-900 tracking-tight">{{ $homestay->nama_homestay }}</h2>
                        <p class="mt-1 text-sm font-semibold text-stone-500">{{ $booking->kamar->nama_kamar }} &bull; {{ $homestay->alamat }}</p>
                    </div>
                    <div class="self-start">
                        <x-badge :status="$booking->status->value" />
                    </div>
                </div>

                <!-- Informasi Wisatawan -->
                <div class="mt-6">
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400 mb-3">Data Wisatawan</p>
                    <div class="rounded-2xl border border-stone-200 bg-stone-50/60 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#13391b] text-white font-extrabold text-base">
                                {{ strtoupper(substr($booking->wisatawan->nama_wisatawan ?? 'W', 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-stone-900">{{ $booking->wisatawan->nama_wisatawan }}</h3>
                                <p class="text-xs text-stone-500">Asal: {{ $booking->wisatawan->asal_kota ?: 'Wisatawan Nusantara' }}</p>
                                <p class="text-xs text-stone-600 mt-0.5">{{ $booking->wisatawan->no_hp }}</p>
                            </div>
                        </div>

                        @if ($cleanPhone)
                            <a
                                href="https://wa.me/{{ $cleanPhone }}?text={{ $waMessage }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
                            >
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.031 2c-5.514 0-9.999 4.485-9.999 9.999 0 1.761.458 3.479 1.328 4.996l-1.411 5.16 5.297-1.389c1.464.799 3.111 1.232 4.785 1.232 5.514 0 9.999-4.485 9.999-9.999s-4.485-10.001-9.999-10.001zm5.834 14.161c-.244.686-1.229 1.306-1.748 1.377-.478.065-1.077.098-3.486-.889-2.915-1.196-4.793-4.179-4.938-4.372-.146-.194-1.187-1.579-1.187-3.009s.75-2.133 1.018-2.424c.267-.291.583-.364.778-.364.195 0 .389.002.559.01.18.009.421-.068.658.502.244.585.83 2.023.903 2.169.073.146.122.316.024.511-.097.195-.146.316-.292.486-.146.171-.307.382-.439.513-.146.146-.299.306-.129.598.17.291.757 1.248 1.624 2.019 1.116.993 2.057 1.3 2.349 1.446.292.146.462.122.632-.073.17-.195.729-.851.924-1.143.195-.292.389-.244.656-.146.267.097 1.699.802 1.991.948.292.146.486.219.559.341.073.122.073.705-.171 1.391z"/>
                                </svg>
                                <span>Hubungi via WhatsApp</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Jadwal Menginap & Tamu -->
                <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="rounded-2xl border border-stone-200/80 bg-stone-50/50 p-4">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Tanggal Check-In</span>
                        <p class="mt-1 text-sm font-extrabold text-stone-900">{{ tanggal_id($booking->tgl_checkin) }}</p>
                        <p class="text-[11px] text-stone-500">Mulai 14.00 WIB</p>
                    </div>

                    <div class="rounded-2xl border border-stone-200/80 bg-stone-50/50 p-4">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Tanggal Check-Out</span>
                        <p class="mt-1 text-sm font-extrabold text-stone-900">{{ tanggal_id($booking->tgl_checkout) }}</p>
                        <p class="text-[11px] text-stone-500">Sebelum 12.00 WIB</p>
                    </div>

                    <div class="rounded-2xl border border-stone-200/80 bg-stone-50/50 p-4">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Durasi Menginap</span>
                        <p class="mt-1 text-sm font-extrabold text-[#13391b]">{{ $booking->jumlah_malam }} Malam</p>
                        <p class="text-[11px] text-stone-500">Kamar siap dihuni</p>
                    </div>

                    <div class="rounded-2xl border border-stone-200/80 bg-stone-50/50 p-4">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Jumlah Tamu</span>
                        <p class="mt-1 text-sm font-extrabold text-[#ea580c]">{{ $booking->jumlah_tamu }} Orang</p>
                        <p class="text-[11px] text-stone-500">Kapasitas: {{ $booking->kamar->kapasitas }} orang</p>
                    </div>
                </div>

                <!-- Paket Wisata yang Dipesan -->
                @if ($booking->paket->isNotEmpty())
                    <div class="mt-6 pt-6 border-t border-stone-100">
                        <p class="text-xs font-bold uppercase tracking-wider text-stone-400 mb-3">Paket Wisata Tambahan yang Dipilih</p>
                        <div class="space-y-2">
                            @foreach ($booking->paket as $package)
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-amber-50/60 border border-amber-200/70 text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <span class="material-symbols-outlined text-[#ea580c]">hiking</span>
                                        <div>
                                            <p class="font-extrabold text-stone-900">{{ $package->nama_paket }}</p>
                                            <p class="text-[11px] text-stone-500">{{ $package->pivot->jumlah_peserta }} peserta &bull; Durasi {{ $package->durasi }}</p>
                                        </div>
                                    </div>
                                    <p class="font-extrabold text-stone-900">{{ rupiah($package->pivot->subtotal) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Status Pembayaran Pokdarwis -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-5">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-[#13391b]">
                            <span class="material-symbols-outlined text-base">payments</span>
                        </span>
                        <div>
                            <h3 class="text-base font-extrabold text-stone-900">Verifikasi Pembayaran Tamu</h3>
                            <p class="text-xs text-stone-500">Audit pembayaran dikelola terpusat oleh Admin Pokdarwis.</p>
                        </div>
                    </div>
                </div>

                @if ($booking->pembayaran->isEmpty())
                    <div class="rounded-2xl border border-amber-200 bg-amber-50/80 p-4 text-xs text-amber-900 flex items-start gap-3">
                        <span class="material-symbols-outlined text-amber-600 text-lg shrink-0 mt-0.5">pending</span>
                        <div>
                            <p class="font-bold">Menunggu Pembayaran dari Tamu</p>
                            <p class="mt-0.5 text-stone-600 leading-relaxed">
                                Wisatawan belum mengunggah bukti transfer pembayaran. Konfirmasi pesanan akan dibuka setelah bukti diunggah dan diverifikasi admin desa.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($booking->pembayaran as $payment)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-2xl border border-stone-200 bg-stone-50/50 gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-base font-extrabold text-stone-900">{{ rupiah($payment->jumlah) }}</p>
                                        <x-badge :status="$payment->status_verifikasi->value" />
                                    </div>
                                    <p class="mt-1 text-xs text-stone-500">
                                        Metode: <span class="font-semibold text-stone-800">{{ $payment->metode }}</span> &bull; Ditransfer: {{ $payment->tgl_bayar->format('d/m/Y H:i') }} WIB
                                    </p>
                                </div>

                                @if ($payment->status_verifikasi->value === 'terverifikasi')
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200">
                                        <span class="material-symbols-outlined text-base text-emerald-600">verified</span>
                                        <span>Dana Aman di Rekening Pokdarwis</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Ulasan Wisatawan (Jika Ada) -->
            @if ($booking->ulasan)
                <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
                    <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-4">
                        <h3 class="text-base font-extrabold text-stone-900">Ulasan & Kesan Menginap Tamu</h3>
                        <div class="flex items-center gap-1 text-amber-500">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-sm {{ $i <= $booking->ulasan->rating ? 'fill-current' : 'text-stone-300' }}">star</span>
                            @endfor
                            <span class="text-xs font-bold text-stone-700 ml-1">({{ $booking->ulasan->rating }}/5)</span>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-stone-700 italic leading-relaxed">
                        “{{ $booking->ulasan->komentar }}”
                    </p>
                    <p class="mt-2 text-[11px] text-stone-400">
                        Ditulis pada {{ tanggal_id($booking->ulasan->tgl_ulasan) }}
                    </p>
                </div>
            @endif
        </div>

        <!-- ASIDE: Total Folio & Tindakan Pemilik -->
        <div class="space-y-6 lg:sticky lg:top-28">
            <!-- Kartu Total Omset Pesanan -->
            <div class="rounded-3xl bg-gradient-to-br from-[#0d2813] via-[#13391b] to-[#1c5026] p-6 text-white shadow-lg border border-white/10">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-300">Total Tagihan Reservasi</span>
                <p class="mt-2 text-3xl font-extrabold text-white tracking-tight">{{ rupiah($booking->total_harga) }}</p>
                <div class="mt-4 pt-4 border-t border-white/15 space-y-2 text-xs text-stone-200">
                    <div class="flex justify-between">
                        <span>Sewa Kamar ({{ $booking->jumlah_malam }} malam)</span>
                        <span class="font-bold text-white">{{ rupiah($booking->kamar->harga_per_malam * $booking->jumlah_malam) }}</span>
                    </div>
                    @if ($booking->paket->isNotEmpty())
                        <div class="flex justify-between text-amber-200">
                            <span>Total Paket Wisata</span>
                            <span class="font-bold text-amber-300">{{ rupiah($booking->paket->sum('pivot.subtotal')) }}</span>
                        </div>
                    @endif
                </div>
                <p class="mt-4 text-[10px] text-stone-300 leading-relaxed">
                    Dana reservasi akan dicairkan ke kas desa dan pengelola homestay setelah verifikasi check-out selesai.
                </p>
            </div>

            <!-- Kartu Tindakan Konfirmasi -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
                <h3 class="text-sm font-extrabold text-stone-900 mb-3">Tindakan Pengelola</h3>

                @if ($booking->status->value === 'menunggu_verifikasi' && ! $hasVerifiedPayment)
                    <div class="mb-4 rounded-2xl bg-amber-50 border border-amber-200 p-3 text-xs text-amber-900 flex items-start gap-2">
                        <span class="material-symbols-outlined text-amber-600 text-base shrink-0 mt-0.5">info</span>
                        <span>Pembayaran tamu sedang menunggu validasi Admin Pokdarwis. Tombol konfirmasi akan aktif setelah pembayaran diverifikasi.</span>
                    </div>
                @endif

                <div class="space-y-2.5">
                    @can('confirm', $booking)
                        <form
                            method="POST"
                            action="{{ route('pemilik.booking.confirm', $booking) }}"
                            onsubmit="return confirm('Konfirmasi penerimaan pesanan tamu ini? Setelah dikonfirmasi, kamar otomatis dikunci dan e-tiket resmi terbit untuk tamu.');"
                        >
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
                            >
                                <span class="material-symbols-outlined text-base">check_circle</span>
                                <span>Konfirmasi Sambut Tamu</span>
                            </button>
                        </form>
                    @endcan

                    @can('complete', $booking)
                        <form
                            method="POST"
                            action="{{ route('pemilik.booking.complete', $booking) }}"
                            onsubmit="return confirm('Tandai pesanan ini telah selesai? Tamu akan dapat memberikan ulasan dan rating keramahan homestay Anda.');"
                        >
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
                            >
                                <span class="material-symbols-outlined text-base">done_all</span>
                                <span>Tandai Selesai (Check-Out)</span>
                            </button>
                        </form>
                    @endcan

                    @can('reject', $booking)
                        <form
                            method="POST"
                            action="{{ route('pemilik.booking.reject', $booking) }}"
                            onsubmit="return confirm('Tolak pesanan ini? Kamar akan kembali dibuka untuk tamu lain. Pastikan Anda telah mengonfirmasi alasan kepada tamu.');"
                        >
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-full border border-red-200 text-red-600 hover:bg-red-50 text-xs font-semibold transition-all cursor-pointer"
                            >
                                <span class="material-symbols-outlined text-sm">cancel</span>
                                <span>Tolak Pesanan</span>
                            </button>
                        </form>
                    @endcan

                    @if (! auth()->user()->can('confirm', $booking) && ! auth()->user()->can('complete', $booking) && ! auth()->user()->can('reject', $booking))
                        <div class="rounded-2xl bg-stone-50 border border-stone-200/80 p-3.5 text-center text-xs text-stone-500">
                            Tidak ada tindakan manual yang diperlukan untuk status saat ini ({{ status_label($booking->status->value) }}).
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
