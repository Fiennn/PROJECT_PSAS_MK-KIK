@extends('layouts.dashboard')

@section('title', 'Tinjau Pembayaran #'.$pembayaran->id_pembayaran)
@section('page_title', 'Validasi Pembayaran #'.$pembayaran->id_pembayaran)
@section('page_description', 'Cocokkan bukti transfer wisatawan dengan total tagihan reservasi sebelum memberikan pengesahan Pokdarwis.')

@section('page_actions')
    <a
        href="{{ route('admin.pembayaran.index') }}"
        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
    >
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Kembali ke Daftar Pembayaran</span>
    </a>
@endsection

@section('dashboard')
    @php
        $booking = $pembayaran->booking;
        $homestay = $booking->kamar->homestay;
        $isPending = $pembayaran->status_verifikasi->value === 'menunggu';
        $diff = (float) $pembayaran->jumlah - (float) $booking->total_harga;
    @endphp

    <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <div class="space-y-6">
            <!-- Kartu Audit Bukti Transfer -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 pb-6 border-b border-stone-100">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-50 text-[#13391b] text-[11px] font-bold uppercase tracking-wider mb-2">
                            Audit Keuangan Pokdarwis
                        </span>
                        <h2 class="text-2xl font-extrabold text-stone-900 tracking-tight">{{ $booking->wisatawan->nama_wisatawan }}</h2>
                        <p class="mt-1 text-xs text-stone-500">
                            Unit: <strong class="text-stone-800">{{ $homestay->nama_homestay }}</strong> &bull; {{ $booking->kamar->nama_kamar }}
                        </p>
                    </div>
                    <div class="self-start">
                        <x-badge :status="$pembayaran->status_verifikasi->value" />
                    </div>
                </div>

                <!-- Perbandingan Nominal -->
                <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="rounded-2xl border border-stone-200 bg-stone-50/70 p-4">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Nominal Transfer</span>
                        <p class="mt-1 text-base sm:text-lg font-extrabold text-[#13391b]">{{ rupiah($pembayaran->jumlah) }}</p>
                        <p class="text-[11px] text-stone-500">Sesuai bukti slip</p>
                    </div>

                    <div class="rounded-2xl border border-stone-200 bg-stone-50/70 p-4">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Total Tagihan</span>
                        <p class="mt-1 text-base sm:text-lg font-extrabold text-stone-900">{{ rupiah($booking->total_harga) }}</p>
                        <p class="text-[11px] text-stone-500">Kamar + Paket</p>
                    </div>

                    <div class="rounded-2xl border border-stone-200 bg-stone-50/70 p-4">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Metode Bayar</span>
                        <p class="mt-1 text-sm font-extrabold text-stone-900">{{ $pembayaran->metode }}</p>
                        <p class="text-[11px] text-stone-500">Transfer Bank / QRIS</p>
                    </div>

                    <div class="rounded-2xl border border-stone-200 bg-stone-50/70 p-4">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Waktu Transfer</span>
                        <p class="mt-1 text-xs font-extrabold text-stone-900">{{ $pembayaran->tgl_bayar->format('d/m/Y') }}</p>
                        <p class="text-[11px] text-stone-500">{{ $pembayaran->tgl_bayar->format('H:i') }} WIB</p>
                    </div>
                </div>

                @if ($diff != 0)
                    <div class="mt-5 rounded-2xl border border-amber-300 bg-amber-50 p-4 text-xs text-amber-900 flex items-start gap-3">
                        <span class="material-symbols-outlined text-amber-600 text-lg shrink-0 mt-0.5">warning</span>
                        <div>
                            <p class="font-bold">Perhatian: Nominal Transfer Berbeda dengan Total Tagihan!</p>
                            <p class="mt-0.5 leading-relaxed">
                                Bukti transfer tertera <strong>{{ rupiah($pembayaran->jumlah) }}</strong>, sedangkan tagihan pesanan adalah <strong>{{ rupiah($booking->total_harga) }}</strong> (selisih: {{ rupiah(abs($diff)) }}). Pastikan selisih ini telah disepakati sebelum memverifikasi.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Akses Berkas Bukti Transfer -->
                <div class="mt-6 pt-6 border-t border-stone-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold text-stone-900">Berkas Bukti Transfer Wisatawan</p>
                        <p class="text-[11px] text-stone-500">Berkas tersimpan aman di server privat desa wisata.</p>
                    </div>
                    <a
                        href="{{ route('pembayaran.bukti', $pembayaran) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-base">visibility</span>
                        <span>Buka Berkas Bukti Transfer</span>
                    </a>
                </div>
            </div>

            <!-- Detail Reservasi Terkait -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
                <h3 class="text-base font-extrabold text-stone-900 mb-4">Rincian Reservasi Terkait (#{{ $booking->id_booking }})</h3>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 text-xs">
                    <div>
                        <span class="text-stone-400 font-bold uppercase tracking-wider text-[10px]">Tanggal Check-in</span>
                        <p class="mt-1 font-bold text-stone-900">{{ tanggal_id($booking->tgl_checkin) }}</p>
                    </div>
                    <div>
                        <span class="text-stone-400 font-bold uppercase tracking-wider text-[10px]">Tanggal Check-out</span>
                        <p class="mt-1 font-bold text-stone-900">{{ tanggal_id($booking->tgl_checkout) }}</p>
                    </div>
                    <div>
                        <span class="text-stone-400 font-bold uppercase tracking-wider text-[10px]">Durasi & Tamu</span>
                        <p class="mt-1 font-bold text-stone-900">{{ $booking->jumlah_malam }} Malam &bull; {{ $booking->jumlah_tamu }} Tamu</p>
                    </div>
                    <div>
                        <span class="text-stone-400 font-bold uppercase tracking-wider text-[10px]">Status Pesanan</span>
                        <div class="mt-1"><x-badge :status="$booking->status->value" /></div>
                    </div>
                </div>

                @if ($booking->paket->isNotEmpty())
                    <div class="mt-5 pt-4 border-t border-stone-100">
                        <p class="text-xs font-bold text-stone-700 mb-2">Paket Wisata Tambahan</p>
                        <ul class="space-y-1.5 text-xs text-stone-600">
                            @foreach ($booking->paket as $pkg)
                                <li class="flex justify-between items-center py-1 border-b border-stone-50">
                                    <span>{{ $pkg->nama_paket }} ({{ $pkg->pivot->jumlah_peserta }} peserta)</span>
                                    <span class="font-extrabold text-stone-900">{{ rupiah($pkg->pivot->subtotal) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <!-- ASIDE: Keputusan Verifikasi & Profil Pemilik -->
        <div class="space-y-6 lg:sticky lg:top-28">
            <!-- Keputusan Pokdarwis -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
                <h3 class="text-sm font-extrabold text-stone-900 mb-2">Keputusan Validasi Pokdarwis</h3>

                @if ($isPending)
                    <p class="text-xs text-stone-500 leading-relaxed mb-4">
                        Periksa kecocokan nomor rekening dan nominal transfer pada berkas. Setelah diverifikasi, pemilik homestay dapat langsung mengonfirmasi penerimaan tamu.
                    </p>

                    <div class="space-y-3">
                        @can('verify', $pembayaran)
                            <x-confirm-button
                                :action="route('admin.pembayaran.verify', $pembayaran)"
                                method="PATCH"
                                label="Sahkan & Verifikasi Pembayaran"
                                title="Verifikasi pembayaran ini?"
                                message="Pembayaran akan dinyatakan sah dan dana masuk pembukuan Pokdarwis. Pemilik homestay akan menerima notifikasi siap konfirmasi tamu."
                                confirm-label="Ya, sahkan pembayaran"
                                variant="primary"
                            />
                        @endcan

                        @can('reject', $pembayaran)
                            <x-confirm-button
                                :action="route('admin.pembayaran.reject', $pembayaran)"
                                method="PATCH"
                                label="Tolak Bukti Transfer"
                                title="Tolak bukti transfer ini?"
                                message="Pesanan akan dikembalikan ke status menunggu pembayaran agar wisatawan dapat mengunggah bukti yang benar."
                                confirm-label="Ya, tolak bukti"
                                variant="danger"
                            />
                        @endcan
                    </div>
                @else
                    <div class="rounded-2xl {{ $pembayaran->status_verifikasi->value === 'terverifikasi' ? 'bg-emerald-50 border border-emerald-200 text-emerald-900' : 'bg-red-50 border border-red-200 text-red-900' }} p-4 text-xs">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <span class="material-symbols-outlined text-base">
                                {{ $pembayaran->status_verifikasi->value === 'terverifikasi' ? 'check_circle' : 'cancel' }}
                            </span>
                            <span>{{ $pembayaran->status_verifikasi->value === 'terverifikasi' ? 'Pembayaran Telah Sah Diverifikasi' : 'Bukti Pembayaran Telah Ditolak' }}</span>
                        </div>
                        <p class="text-[11px] leading-relaxed">
                            {{ $pembayaran->status_verifikasi->value === 'terverifikasi' ? 'Dana telah tercatat dalam kas desa dan pemilik homestay dapat mengonfirmasi kedatangan tamu.' : 'Wisatawan telah diminta untuk mengunggah ulang bukti transfer yang valid.' }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Profil Pemilik Homestay -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Tuan Rumah Pengelola</span>
                <p class="mt-2 text-sm font-extrabold text-stone-900">{{ $homestay->pemilik->nama_pemilik }}</p>
                <p class="text-xs text-stone-500 mt-0.5">{{ $homestay->pemilik->no_hp }}</p>
                <p class="text-xs text-stone-500 mt-0.5">{{ $homestay->pemilik->alamat }}</p>
            </div>
        </div>
    </div>
@endsection
