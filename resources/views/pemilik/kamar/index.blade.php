@extends('layouts.dashboard')

@section('title', 'Kamar '.$homestay->nama_homestay)
@section('page_title', 'Kamar & Harga')
@section('page_description', 'Atur kamar, kapasitas, dan harga per malam untuk '.$homestay->nama_homestay.'.')

@section('page_actions')
    <x-button :href="route('pemilik.homestay.paket.index', $homestay)" variant="secondary">Paket Wisata</x-button>
    <x-button :href="route('pemilik.homestay.edit', $homestay)" variant="ghost">Edit Homestay</x-button>
@endsection

@section('dashboard')
    <div class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr] lg:items-start">
        <div class="space-y-4">
            @if ($kamar->isEmpty())
                <x-empty-state
                    title="Belum ada kamar"
                    description="Tambahkan kamar agar wisatawan dapat memesan homestay Anda."
                />
            @else
                @foreach ($kamar as $room)
                    <x-card padding="p-0">
                        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="font-display text-lg font-semibold text-forest-950">{{ $room->nama_kamar }}</h2>
                                    <x-badge :status="$room->status->value" />
                                </div>
                                <p class="mt-1 text-sm text-stone-500">Kapasitas maksimal {{ $room->kapasitas }} tamu</p>
                                <p class="mt-1 font-display text-xl font-semibold text-forest-900">
                                    {{ rupiah($room->harga_per_malam) }}
                                    <span class="font-sans text-xs font-normal text-stone-500">/malam</span>
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <x-button :href="route('pemilik.kamar.edit', $room)" variant="secondary" size="sm">Edit</x-button>
                                @can('delete', $room)
                                    <x-confirm-button
                                        :action="route('pemilik.kamar.destroy', $room)"
                                        method="DELETE"
                                        label="Hapus"
                                        title="Hapus kamar ini?"
                                        message="Kamar hanya dapat dihapus jika belum memiliki pemesanan aktif. Tindakan ini tidak dapat dibatalkan."
                                        confirm-label="Ya, hapus kamar"
                                    />
                                @endcan
                            </div>
                        </div>
                    </x-card>
                @endforeach
            @endif
        </div>

        <x-card class="lg:sticky lg:top-28">
            <h2 class="font-display text-xl font-semibold text-forest-950">Tambah kamar</h2>
            <p class="mt-1 text-sm text-stone-500">Kamar baru langsung dapat dipesan jika statusnya tersedia.</p>
            <div class="mt-5">
                @include('pemilik.kamar._form', ['kamar' => null, 'homestay' => $homestay])
            </div>
        </x-card>
    </div>
@endsection
