@extends('layouts.dashboard')

@section('title', 'Paket Wisata '.$homestay->nama_homestay)
@section('page_title', 'Paket Wisata')
@section('page_description', 'Tawarkan pengalaman lokal untuk melengkapi menginap di '.$homestay->nama_homestay.'.')

@section('page_actions')
    <x-button :href="route('pemilik.homestay.kamar.index', $homestay)" variant="secondary">Kamar</x-button>
    <x-button :href="route('pemilik.homestay.edit', $homestay)" variant="ghost">Edit Homestay</x-button>
@endsection

@section('dashboard')
    <div class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr] lg:items-start">
        <div class="space-y-4">
            @if ($paket->isEmpty())
                <x-empty-state
                    title="Belum ada paket wisata"
                    description="Paket wisata bersifat opsional, tetapi dapat menambah pendapatan dan pengalaman tamu."
                />
            @else
                @foreach ($paket as $item)
                    <x-card padding="p-0">
                        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h2 class="font-display text-lg font-semibold text-forest-950">{{ $item->nama_paket }}</h2>
                                <p class="mt-1 text-sm leading-relaxed text-stone-500">{{ $item->deskripsi }}</p>
                                <p class="mt-2 text-xs font-semibold text-clay-700">Durasi {{ $item->durasi }}</p>
                                <p class="mt-1 font-display text-xl font-semibold text-forest-900">
                                    {{ rupiah($item->harga) }}
                                    <span class="font-sans text-xs font-normal text-stone-500">/peserta</span>
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <x-button :href="route('pemilik.paket.edit', $item)" variant="secondary" size="sm">Edit</x-button>
                                @can('delete', $item)
                                    <x-confirm-button
                                        :action="route('pemilik.paket.destroy', $item)"
                                        method="DELETE"
                                        label="Hapus"
                                        title="Hapus paket wisata ini?"
                                        message="Paket yang sudah pernah dipesan tidak dapat dihapus agar riwayat pesanan tetap utuh."
                                        confirm-label="Ya, hapus paket"
                                    />
                                @endcan
                            </div>
                        </div>
                    </x-card>
                @endforeach
            @endif
        </div>

        <x-card class="lg:sticky lg:top-28">
            <h2 class="font-display text-xl font-semibold text-forest-950">Tambah paket</h2>
            <p class="mt-1 text-sm text-stone-500">Paket dapat dipilih wisatawan saat mengisi formulir pemesanan.</p>
            <div class="mt-5">
                @include('pemilik.paket._form', ['paket' => null, 'homestay' => $homestay])
            </div>
        </x-card>
    </div>
@endsection
