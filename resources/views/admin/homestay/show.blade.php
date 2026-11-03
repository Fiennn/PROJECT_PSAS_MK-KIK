@extends('layouts.dashboard')

@section('title', 'Tinjau Homestay')
@section('page_title', $homestay->nama_homestay)
@section('page_description', 'Periksa kelengkapan data sebelum menyetujui atau menolak pengajuan homestay.')

@section('page_actions')
    <x-button :href="route('admin.homestay.index')" variant="secondary">Kembali</x-button>
@endsection

@section('dashboard')
    <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <div class="space-y-6">
            <x-card>
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-clay-600">Pengajuan homestay</p>
                        <h2 class="mt-1 font-display text-2xl font-semibold text-forest-950">{{ $homestay->nama_homestay }}</h2>
                        <p class="mt-1 text-sm text-stone-500">{{ $homestay->alamat }}</p>
                    </div>
                    <x-badge :status="$homestay->status_verifikasi->value" />
                </div>

                <p class="mt-4 whitespace-pre-line text-sm leading-relaxed text-stone-600">{{ $homestay->deskripsi }}</p>

                <dl class="mt-6 grid gap-4 border-t border-sand-100 pt-5 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Pemilik</dt>
                        <dd class="mt-1 font-semibold text-forest-950">{{ $homestay->pemilik->nama_pemilik }}</dd>
                        <dd class="text-xs text-stone-500">{{ $homestay->pemilik->no_hp }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Kontak pemilik</dt>
                        <dd class="mt-1 text-stone-600">{{ $homestay->pemilik->user->email }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-stone-400">Alamat pemilik</dt>
                        <dd class="mt-1 text-stone-600">{{ $homestay->pemilik->alamat }}</dd>
                    </div>
                </dl>
            </x-card>

            <x-card>
                <h2 class="font-display text-xl font-semibold text-forest-950">Fasilitas</h2>
                @if ($homestay->fasilitas->isEmpty())
                    <p class="mt-2 text-sm text-stone-500">Pemilik belum menambahkan fasilitas.</p>
                @else
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($homestay->fasilitas as $facility)
                            <span class="rounded-full bg-sand-100 px-3 py-1.5 text-xs font-semibold text-sand-800">{{ $facility->nama_fasilitas }}</span>
                        @endforeach
                    </div>
                @endif
            </x-card>

            <x-card padding="p-0">
                <div class="border-b border-sand-200 p-5">
                    <h2 class="font-display text-xl font-semibold text-forest-950">Kamar ({{ $homestay->kamar->count() }})</h2>
                </div>
                @if ($homestay->kamar->isEmpty())
                    <div class="p-5">
                        <p class="text-sm text-stone-500">Belum ada kamar. Homestay tanpa kamar tidak dapat dipesan.</p>
                    </div>
                @else
                    <ul class="divide-y divide-sand-100">
                        @foreach ($homestay->kamar as $room)
                            <li class="flex items-center justify-between gap-3 p-5 text-sm">
                                <div>
                                    <p class="font-semibold text-forest-950">{{ $room->nama_kamar }}</p>
                                    <p class="text-xs text-stone-500">Kapasitas {{ $room->kapasitas }} tamu</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-forest-900">{{ rupiah($room->harga_per_malam) }}</p>
                                    <x-badge :status="$room->status->value" class="mt-1" />
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>

            <x-card padding="p-0">
                <div class="border-b border-sand-200 p-5">
                    <h2 class="font-display text-xl font-semibold text-forest-950">Paket wisata ({{ $homestay->paket->count() }})</h2>
                </div>
                @if ($homestay->paket->isEmpty())
                    <div class="p-5">
                        <p class="text-sm text-stone-500">Pemilik belum menambahkan paket wisata.</p>
                    </div>
                @else
                    <ul class="divide-y divide-sand-100">
                        @foreach ($homestay->paket as $package)
                            <li class="flex items-start justify-between gap-3 p-5 text-sm">
                                <div>
                                    <p class="font-semibold text-forest-950">{{ $package->nama_paket }}</p>
                                    <p class="mt-0.5 text-xs text-stone-500">{{ $package->durasi }}</p>
                                </div>
                                <p class="font-semibold text-forest-900">{{ rupiah($package->harga) }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>
        </div>

        <aside class="space-y-4 lg:sticky lg:top-28">
            <x-card>
                <h2 class="font-display text-xl font-semibold text-forest-950">Keputusan verifikasi</h2>
                <p class="mt-1 text-sm text-stone-500">
                    Menyetujui homestay akan menampilkannya di katalog publik dan memungkinkan pemesanan kamar.
                </p>

                <div class="mt-4 space-y-3">
                    @if ($homestay->status_verifikasi->value !== 'disetujui')
                        <x-confirm-button
                            :action="route('admin.homestay.approve', $homestay)"
                            method="PATCH"
                            label="Setujui Homestay"
                            title="Setujui homestay ini?"
                            message="Homestay akan langsung tampil di katalog publik dan dapat dipesan wisatawan."
                            confirm-label="Ya, setujui"
                            variant="primary"
                        />
                    @endif

                    @if ($homestay->status_verifikasi->value !== 'ditolak')
                        <x-confirm-button
                            :action="route('admin.homestay.reject', $homestay)"
                            method="PATCH"
                            label="Tolak Homestay"
                            title="Tolak pengajuan homestay?"
                            message="Homestay tidak akan tampil di katalog. Pemilik dapat memperbaiki data lalu mengajukan ulang."
                            confirm-label="Ya, tolak"
                            variant="danger"
                        />
                    @endif
                </div>
            </x-card>

            <x-card>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-clay-600">Status saat ini</p>
                <div class="mt-3">
                    <x-badge :status="$homestay->status_verifikasi->value" />
                </div>
            </x-card>
        </aside>
    </div>
@endsection
