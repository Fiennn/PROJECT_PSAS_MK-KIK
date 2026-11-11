@extends('layouts.dashboard')

@section('title', 'Pantau Homestay: ' . $homestay->nama_homestay)
@section('page_title', $homestay->nama_homestay)
@section('page_description', 'Pantau ketersediaan, periksa kelengkapan data, dan kelola status homestay desa wisata.')

@section('page_actions')
    <div class="flex items-center gap-2">
        <x-button :href="route('admin.homestay.edit', $homestay)" variant="primary">
            <span class="material-symbols-outlined text-sm">edit</span>
            <span>Ubah Data Homestay</span>
        </x-button>
        <x-button :href="route('admin.homestay.index')" variant="secondary">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali ke Daftar</span>
        </x-button>
    </div>
@endsection

@section('dashboard')
    <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <div class="space-y-6">
            <x-card>
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[#ea580c]">Profil Homestay Desa</p>
                        <h2 class="mt-1 font-display text-2xl font-bold text-stone-900">{{ $homestay->nama_homestay }}</h2>
                        <p class="mt-1 text-sm text-stone-500">{{ $homestay->alamat }}</p>
                    </div>
                    <x-badge :status="$homestay->status_verifikasi->value" />
                </div>

                <p class="mt-4 whitespace-pre-line text-sm leading-relaxed text-stone-600">{{ $homestay->deskripsi }}</p>

                <dl class="mt-6 grid gap-4 border-t border-stone-200/80 pt-5 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Pemilik Homestay</dt>
                        <dd class="mt-1 font-semibold text-stone-900">{{ $homestay->pemilik->nama_pemilik }}</dd>
                        <dd class="text-xs text-stone-500">HP / WhatsApp: {{ $homestay->pemilik->no_hp }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Akun Email Login</dt>
                        <dd class="mt-1 text-stone-700 font-mono text-xs">{{ $homestay->pemilik->user->email }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Alamat Tempat Tinggal Pemilik</dt>
                        <dd class="mt-1 text-stone-600">{{ $homestay->pemilik->alamat }}</dd>
                    </div>
                </dl>
            </x-card>

            <x-card>
                <h2 class="font-display text-lg font-bold text-stone-900">Fasilitas yang Tersedia</h2>
                @if ($homestay->fasilitas->isEmpty())
                    <p class="mt-2 text-sm text-stone-500">Belum ada fasilitas yang ditautkan ke homestay ini.</p>
                @else
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($homestay->fasilitas as $facility)
                            <span class="rounded-full bg-stone-100 border border-stone-200 px-3 py-1.5 text-xs font-semibold text-stone-800 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-[#1b4d24]">check_circle</span>
                                <span>{{ $facility->nama_fasilitas }}</span>
                            </span>
                        @endforeach
                    </div>
                @endif
            </x-card>

            <x-card padding="p-0">
                <div class="border-b border-stone-200 p-5 flex items-center justify-between">
                    <h2 class="font-display text-lg font-bold text-stone-900">Daftar Kamar ({{ $homestay->kamar->count() }})</h2>
                    <span class="text-xs font-semibold text-stone-500">Terkoneksi Kalender Pintar</span>
                </div>
                @if ($homestay->kamar->isEmpty())
                    <div class="p-5">
                        <p class="text-sm text-stone-500">Belum ada kamar yang didaftarkan pada homestay ini.</p>
                    </div>
                @else
                    <ul class="divide-y divide-stone-100">
                        @foreach ($homestay->kamar as $room)
                            <li class="flex items-center justify-between gap-3 p-5 text-sm hover:bg-stone-50/50 transition-colors">
                                <div>
                                    <p class="font-bold text-stone-900">{{ $room->nama_kamar }}</p>
                                    <p class="text-xs text-stone-500 mt-0.5">Kapasitas: {{ $room->kapasitas }} tamu</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-[#13391b]">{{ rupiah($room->harga_per_malam) }} <span class="text-[11px] font-normal text-stone-400">/malam</span></p>
                                    <x-badge :status="$room->status->value" class="mt-1" />
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>

            <x-card padding="p-0">
                <div class="border-b border-stone-200 p-5">
                    <h2 class="font-display text-lg font-bold text-stone-900">Paket Wisata & Kearifan Lokal ({{ $homestay->paket->count() }})</h2>
                </div>
                @if ($homestay->paket->isEmpty())
                    <div class="p-5">
                        <p class="text-sm text-stone-500">Belum ada paket wisata yang ditautkan ke homestay ini.</p>
                    </div>
                @else
                    <ul class="divide-y divide-stone-100">
                        @foreach ($homestay->paket as $package)
                            <li class="flex items-start justify-between gap-3 p-5 text-sm">
                                <div>
                                    <p class="font-bold text-stone-900">{{ $package->nama_paket }}</p>
                                    <p class="mt-0.5 text-xs text-stone-500">Durasi: {{ $package->durasi }}</p>
                                </div>
                                <p class="font-bold text-[#ea580c]">{{ rupiah($package->harga) }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>
        </div>

        <!-- Sidebar Monitoring & Tindakan Cepat -->
        <aside class="space-y-4 lg:sticky lg:top-28">
            <!-- Ringkasan Statistik Reservasi -->
            <x-card>
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#13391b] border-b border-stone-200 pb-2 mb-3">
                    Statistik & Dampak Ekonomi
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-stone-500">Total Reservasi Masuk</span>
                        <span class="font-bold text-stone-900">{{ $bookingCount ?? 0 }} Booking</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-stone-500">Perputaran Dana Warga</span>
                        <span class="font-bold text-emerald-700">{{ rupiah($totalPendapatan ?? 0) }}</span>
                    </div>
                </div>
            </x-card>

            <!-- Status Publikasi & Opsi Cepat -->
            <x-card>
                <div class="flex items-center justify-between border-b border-stone-200 pb-2 mb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-stone-600">Status Verifikasi</h3>
                    <x-badge :status="$homestay->status_verifikasi->value" />
                </div>
                <p class="text-xs text-stone-500 leading-relaxed">
                    Status "Disetujui" membuat homestay tampil di katalog utama dan dapat dipesan oleh wisatawan nusantara.
                </p>

                <div class="mt-4 space-y-2">
                    @if ($homestay->status_verifikasi->value !== 'disetujui')
                        <x-confirm-button
                            :action="route('admin.homestay.approve', $homestay)"
                            method="PATCH"
                            label="Setujui Homestay"
                            title="Setujui homestay ini?"
                            message="Homestay akan langsung aktif di katalog publik dan siap menerima reservasi."
                            confirm-label="Ya, setujui"
                            variant="primary"
                        />
                    @endif

                    @if ($homestay->status_verifikasi->value !== 'ditolak')
                        <x-confirm-button
                            :action="route('admin.homestay.reject', $homestay)"
                            method="PATCH"
                            label="Tolak / Nonaktifkan"
                            title="Tolak atau nonaktifkan homestay?"
                            message="Homestay tidak akan tampil di katalog publik."
                            confirm-label="Ya, nonaktifkan"
                            variant="danger"
                        />
                    @endif

                    <a
                        href="{{ route('admin.homestay.edit', $homestay) }}"
                        class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-full border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 text-xs font-bold transition-all"
                    >
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <span>Ubah Rincian Data</span>
                    </a>
                </div>
            </x-card>

            <!-- Hapus Homestay -->
            <x-card>
                <h3 class="text-xs font-bold uppercase tracking-wider text-red-600 mb-1">Zona Bahaya</h3>
                <p class="text-[11px] text-stone-500 leading-relaxed mb-3">
                    Hapus homestay jika data dibuat ganda atau tidak lagi beroperasi di desa wisata.
                </p>
                <form method="POST" action="{{ route('admin.homestay.destroy', $homestay) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus homestay ini? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-full border border-red-300 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold transition-all cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-sm">delete_forever</span>
                        <span>Hapus Homestay Ini</span>
                    </button>
                </form>
            </x-card>
        </aside>
    </div>
@endsection
