@extends('layouts.dashboard')

@section('title', 'Homestay Saya')
@section('page_title', 'Homestay Saya')
@section('page_description', 'Kelola data homestay, kamar, dan paket wisata yang Anda miliki.')

@section('page_actions')
    <x-button :href="route('pemilik.homestay.create')">Tambah Homestay</x-button>
@endsection

@section('dashboard')
    @if ($homestays->isEmpty())
        <x-empty-state
            title="Belum ada homestay"
            description="Daftarkan homestay pertama Anda. Setelah disetujui admin desa, homestay akan tampil di katalog publik."
        >
            <x-button :href="route('pemilik.homestay.create')">Tambah Homestay</x-button>
        </x-empty-state>
    @else
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($homestays as $homestay)
                @php
                    $gradients = [
                        'from-forest-700 via-forest-500 to-sand-400',
                        'from-clay-700 via-clay-500 to-sand-300',
                        'from-forest-900 via-forest-700 to-clay-500',
                    ];
                    $gradient = $gradients[$homestay->id_homestay % count($gradients)];
                @endphp

                <article class="flex h-full flex-col overflow-hidden rounded-3xl border border-sand-200 bg-white shadow-soft">
                    <div class="relative h-24 bg-gradient-to-br {{ $gradient }}">
                        <div class="absolute inset-0 bg-grain opacity-60" aria-hidden="true"></div>
                        <div class="absolute left-4 top-4">
                            <x-badge :status="$homestay->status_verifikasi->value" />
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col p-5">
                        <h2 class="font-display text-xl font-semibold leading-snug text-forest-950">{{ $homestay->nama_homestay }}</h2>
                        <p class="mt-1 line-clamp-2 text-sm leading-relaxed text-stone-500">{{ $homestay->alamat }}</p>

                        <div class="mt-4 grid grid-cols-2 gap-2 text-center">
                            <div class="rounded-2xl bg-sand-50 px-3 py-2.5">
                                <p class="font-display text-lg font-semibold text-forest-900">{{ $homestay->kamar_count }}</p>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">Kamar</p>
                            </div>
                            <div class="rounded-2xl bg-sand-50 px-3 py-2.5">
                                <p class="font-display text-lg font-semibold text-forest-900">{{ $homestay->paket_count }}</p>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">Paket</p>
                            </div>
                        </div>

                        @if ($homestay->fasilitas->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach ($homestay->fasilitas->take(3) as $facility)
                                    <span class="rounded-full bg-sand-100 px-2.5 py-1 text-[10px] font-semibold text-sand-800">{{ $facility->nama_fasilitas }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-auto flex flex-wrap gap-2 pt-5">
                            <x-button :href="route('pemilik.homestay.edit', $homestay)" size="sm">Edit</x-button>
                            <x-button :href="route('pemilik.homestay.kamar.index', $homestay)" variant="secondary" size="sm">Kamar</x-button>
                            <x-button :href="route('pemilik.homestay.paket.index', $homestay)" variant="secondary" size="sm">Paket</x-button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <x-pagination :paginator="$homestays" />
    @endif
@endsection
