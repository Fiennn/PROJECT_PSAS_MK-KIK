@extends('layouts.app')

@section('title', 'Katalog Homestay')
@section('meta_description', 'Cari homestay terverifikasi di desa wisata berdasarkan nama, harga, kapasitas, dan fasilitas.')

@section('content')
    <section class="border-b border-sand-200 bg-gradient-to-b from-sand-100 to-sand-50">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <x-page-heading
                eyebrow="Jelajahi desa wisata"
                title="Temukan tempat menginap yang terasa seperti pulang"
                description="Semua homestay sudah diverifikasi pengelola desa wisata. Gunakan filter untuk menemukan kamar yang sesuai kebutuhan Anda."
            />
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <form method="GET" action="{{ route('homestay.index') }}" class="rounded-3xl border border-sand-200 bg-white p-5 shadow-soft sm:p-6">
            <div class="grid gap-4 lg:grid-cols-[1.2fr_0.8fr_0.8fr_auto] lg:items-end">
                <div>
                    <label for="q" class="label">Nama homestay</label>
                    <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama homestay..." class="field">
                </div>

                <div>
                    <label for="harga_max" class="label">Harga maksimal / malam</label>
                    <input id="harga_max" type="number" min="0" step="50000" name="harga_max" value="{{ $filters['harga_max'] ?? '' }}" placeholder="Contoh: 300000" class="field">
                </div>

                <div>
                    <label for="kapasitas" class="label">Kapasitas tamu</label>
                    <select id="kapasitas" name="kapasitas" class="field">
                        <option value="">Semua kapasitas</option>
                        @foreach ([1, 2, 3, 4] as $capacity)
                            <option value="{{ $capacity }}" @selected((int) ($filters['kapasitas'] ?? 0) === $capacity)>{{ $capacity }} tamu atau lebih</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 lg:pb-0.5">
                    <x-button type="submit" class="flex-1 lg:flex-none">Terapkan</x-button>
                    <x-button :href="route('homestay.index')" variant="secondary" class="flex-1 lg:flex-none">Reset</x-button>
                </div>
            </div>

            <fieldset class="mt-5 border-t border-sand-100 pt-5">
                <legend class="label">Fasilitas yang dibutuhkan</legend>
                <div class="mt-2 flex flex-wrap gap-x-5 gap-y-3">
                    @foreach ($facilities as $facility)
                        <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-stone-700">
                            <input
                                type="checkbox"
                                name="fasilitas[]"
                                value="{{ $facility->id_fasilitas }}"
                                @checked(in_array($facility->id_fasilitas, $selectedFacilities, true))
                                class="h-4 w-4 rounded border-sand-300 text-forest-700 focus:ring-forest-400"
                            >
                            {{ $facility->nama_fasilitas }}
                        </label>
                    @endforeach
                </div>
                <p class="hint">Homestay harus memiliki semua fasilitas yang dipilih.</p>
            </fieldset>
        </form>

        <div class="mt-8 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-clay-600">Hasil pencarian</p>
                <h2 class="mt-1 font-display text-2xl font-semibold text-forest-950">
                    {{ $homestays->total() }} homestay ditemukan
                </h2>
            </div>
            @if ($homestays->total() > 0)
                <p class="text-sm text-stone-500">Halaman {{ $homestays->currentPage() }} dari {{ $homestays->lastPage() }}</p>
            @endif
        </div>

        @if ($homestays->isEmpty())
            <div class="mt-6">
                <x-empty-state
                    title="Belum ada homestay yang cocok"
                    description="Coba longgarkan filter harga, kapasitas, atau fasilitas. Anda juga bisa mencari dengan kata kunci lain."
                >
                    <x-button :href="route('homestay.index')" variant="secondary">Hapus semua filter</x-button>
                </x-empty-state>
            </div>
        @else
            <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($homestays as $homestay)
                    <x-homestay-card :homestay="$homestay" />
                @endforeach
            </div>

            <x-pagination :paginator="$homestays" />
        @endif
    </section>
@endsection
