@extends('layouts.dashboard')

@section('title', 'Edit Homestay')
@section('page_title', 'Edit '.$homestay->nama_homestay)
@section('page_description', 'Perbarui informasi homestay Anda. Perubahan langsung terlihat setelah disimpan.')

@section('page_actions')
    <x-button :href="route('pemilik.homestay.kamar.index', $homestay)" variant="secondary">Kelola Kamar</x-button>
    <x-button :href="route('pemilik.homestay.index')" variant="ghost">Kembali</x-button>
@endsection

@section('dashboard')
    @if ($homestay->status_verifikasi->value === 'ditolak')
        <div class="mb-6">
            <x-alert type="warning">
                Pengajuan homestay ini sebelumnya ditolak. Setelah Anda menyimpan perbaikan, status akan kembali menjadi Menunggu Verifikasi.
            </x-alert>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr] lg:items-start">
        <div>
            @include('pemilik.homestay._form', [
                'homestay' => $homestay,
                'fasilitas' => $fasilitas,
                'selectedFacilities' => $selectedFacilities,
            ])
        </div>

        <div class="space-y-4">
            <x-card>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-clay-600">Status verifikasi</p>
                <div class="mt-3">
                    <x-badge :status="$homestay->status_verifikasi->value" />
                </div>
                <p class="mt-3 text-sm leading-relaxed text-stone-500">
                    @if ($homestay->status_verifikasi->value === 'disetujui')
                        Homestay Anda sudah tampil di katalog publik dan dapat dipesan wisatawan.
                    @elseif ($homestay->status_verifikasi->value === 'menunggu')
                        Admin desa sedang meninjau pengajuan homestay Anda.
                    @else
                        Perbaiki data homestay lalu simpan agar dapat ditinjau kembali.
                    @endif
                </p>
            </x-card>

            <x-card>
                <h2 class="font-display text-lg font-semibold text-forest-950">Kelola konten</h2>
                <div class="mt-4 space-y-2">
                    <x-button :href="route('pemilik.homestay.kamar.index', $homestay)" variant="secondary" class="w-full justify-between">
                        <span>Kamar</span>
                        <span class="text-xs font-bold text-stone-400">{{ $homestay->kamar()->count() }} kamar</span>
                    </x-button>
                    <x-button :href="route('pemilik.homestay.paket.index', $homestay)" variant="secondary" class="w-full justify-between">
                        <span>Paket wisata</span>
                        <span class="text-xs font-bold text-stone-400">{{ $homestay->paket()->count() }} paket</span>
                    </x-button>
                </div>
            </x-card>
        </div>
    </div>
@endsection
