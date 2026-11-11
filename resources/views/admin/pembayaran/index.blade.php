@extends('layouts.dashboard')

@section('title', 'Verifikasi Pembayaran')
@section('page_title', 'Verifikasi Pembayaran Wisatawan')
@section('page_description', 'Validasi bukti transfer pembayaran wisatawan sebelum pemilik homestay mengonfirmasi kedatangan tamu.')

@section('dashboard')
    <!-- KPI Summary Bar -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-6">
        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-stone-500">
                <span class="text-xs font-bold uppercase tracking-wider">Total Bukti</span>
                <span class="material-symbols-outlined text-stone-400 text-lg">receipt_long</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-stone-900">{{ $stats['total'] ?? $payments->total() }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Seluruh berkas transfer</p>
        </div>

        <div class="rounded-2xl border {{ ($stats['menunggu'] ?? 0) > 0 ? 'border-amber-300 bg-amber-50/50' : 'border-stone-200 bg-white' }} p-4 shadow-xs">
            <div class="flex items-center justify-between text-amber-700">
                <span class="text-xs font-bold uppercase tracking-wider">Menunggu Validasi</span>
                <span class="material-symbols-outlined text-amber-600 text-lg">pending</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold {{ ($stats['menunggu'] ?? 0) > 0 ? 'text-[#ea580c]' : 'text-stone-900' }}">
                {{ $stats['menunggu'] ?? 0 }}
            </p>
            <p class="mt-0.5 text-[11px] text-stone-400">Perlu tindakan admin</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-emerald-600">
                <span class="text-xs font-bold uppercase tracking-wider">Terverifikasi</span>
                <span class="material-symbols-outlined text-emerald-500 text-lg">verified</span>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-[#13391b]">{{ $stats['terverifikasi'] ?? 0 }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Dana sah terkonfirmasi</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-[#13391b]">
                <span class="text-xs font-bold uppercase tracking-wider">Kas Terverifikasi</span>
                <span class="material-symbols-outlined text-emerald-600 text-lg">account_balance</span>
            </div>
            <p class="mt-2 text-xl sm:text-2xl font-extrabold text-[#13391b] truncate">{{ rupiah($stats['total_rupiah'] ?? 0) }}</p>
            <p class="mt-0.5 text-[11px] text-stone-400">Total nominal sah</p>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="rounded-2xl border border-stone-200 bg-white p-4 sm:p-5 shadow-xs mb-6">
        <form method="GET" action="{{ route('admin.pembayaran.index') }}" class="grid gap-3 sm:grid-cols-[1.5fr_1fr_auto] sm:items-end">
            <div>
                <label for="q" class="block text-xs font-bold text-stone-700 mb-1">Cari Pembayaran</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-stone-400 text-lg">search</span>
                    <input
                        id="q"
                        type="text"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="Nama wisatawan atau nama homestay..."
                        class="w-full text-xs sm:text-sm pl-9 pr-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none"
                    >
                </div>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-stone-700 mb-1">Status Verifikasi</label>
                <select
                    id="status"
                    name="status"
                    class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-stone-300 focus:border-[#13391b] focus:ring-2 focus:ring-[#13391b]/20 outline-none bg-white"
                >
                    <option value="">Semua Status Pembayaran</option>
                    <option value="menunggu" @selected(($filters['status'] ?? '') === 'menunggu')>Menunggu Verifikasi</option>
                    <option value="terverifikasi" @selected(($filters['status'] ?? '') === 'terverifikasi')>Terverifikasi (Sah)</option>
                    <option value="ditolak" @selected(($filters['status'] ?? '') === 'ditolak')>Ditolak (Perlu Upload Ulang)</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all cursor-pointer"
                >
                    <span class="material-symbols-outlined text-sm">filter_alt</span>
                    <span>Terapkan</span>
                </button>
                @if (!empty($filters['q']) || !empty($filters['status']))
                    <a
                        href="{{ route('admin.pembayaran.index') }}"
                        class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 text-xs font-semibold transition-all"
                        title="Reset Filter"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Pembayaran -->
    <div class="rounded-3xl border border-stone-200 bg-white shadow-xs overflow-hidden">
        @if ($payments->isEmpty())
            <div class="py-12 px-4 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-stone-100 text-stone-400 mb-3">
                    <span class="material-symbols-outlined text-3xl">receipt</span>
                </div>
                <h3 class="text-base font-bold text-stone-900">Tidak Ada Pembayaran</h3>
                <p class="mt-1 text-xs text-stone-500 max-w-sm mx-auto">
                    Belum ada bukti pembayaran yang cocok dengan filter atau kata kunci yang dipilih.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[760px]">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50/70 text-stone-500 font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-3.5 px-5">Wisatawan & ID</th>
                            <th class="py-3.5 px-5">Homestay & Kamar</th>
                            <th class="py-3.5 px-5">Nominal Transfer</th>
                            <th class="py-3.5 px-5">Status</th>
                            <th class="py-3.5 px-5 text-right">Tindakan Validasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($payments as $payment)
                            <tr class="hover:bg-stone-50/50 transition-colors">
                                <td class="py-3.5 px-5">
                                    <p class="font-extrabold text-stone-900 text-sm">{{ $payment->booking->wisatawan->nama_wisatawan }}</p>
                                    <p class="text-[11px] text-stone-400">
                                        Booking #{{ $payment->id_booking }} &bull; Bukti #{{ $payment->id_pembayaran }}
                                    </p>
                                </td>

                                <td class="py-3.5 px-5">
                                    <p class="font-bold text-stone-900">{{ $payment->booking->kamar->homestay->nama_homestay }}</p>
                                    <p class="text-[11px] text-stone-500">{{ $payment->booking->kamar->nama_kamar }}</p>
                                </td>

                                <td class="py-3.5 px-5">
                                    <p class="text-sm font-extrabold text-[#13391b]">{{ rupiah($payment->jumlah) }}</p>
                                    <p class="text-[11px] text-stone-400">
                                        {{ $payment->metode }} &bull; {{ $payment->tgl_bayar->format('d/m/Y H:i') }} WIB
                                    </p>
                                </td>

                                <td class="py-3.5 px-5">
                                    <x-badge :status="$payment->status_verifikasi->value" />
                                </td>

                                <td class="py-3.5 px-5 text-right">
                                    <a
                                        href="{{ route('admin.pembayaran.show', $payment) }}"
                                        class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-full {{ $payment->status_verifikasi->value === 'menunggu' ? 'bg-[#ea580c] hover:bg-[#c2410c] text-white shadow-xs' : 'bg-stone-100 hover:bg-stone-200 text-stone-700' }} text-xs font-bold transition-all"
                                    >
                                        <span>{{ $payment->status_verifikasi->value === 'menunggu' ? 'Validasi Bukti' : 'Lihat Detail' }}</span>
                                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-stone-100">
                <x-pagination :paginator="$payments" />
            </div>
        @endif
    </div>
@endsection
