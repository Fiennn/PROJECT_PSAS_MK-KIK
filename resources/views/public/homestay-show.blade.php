@extends('layouts.app')

@section('title', $homestay->nama_homestay)
@section('meta_description', 'Detail kamar, fasilitas, paket wisata, dan ulasan '.$homestay->nama_homestay.'.')

@section('content')
    @php
        $gradients = [
            'from-forest-800 via-forest-600 to-sand-400',
            'from-clay-800 via-clay-500 to-sand-300',
            'from-forest-950 via-forest-700 to-clay-500',
        ];
        $gradient = $gradients[$homestay->id_homestay % count($gradients)];
    @endphp

    <section class="relative overflow-hidden bg-forest-950 text-sand-50">
        <div class="absolute inset-0 bg-grain opacity-50" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-br {{ $gradient }} opacity-80" aria-hidden="true"></div>
        <svg class="absolute -bottom-16 right-0 h-72 w-72 text-white/10" viewBox="0 0 100 100" fill="currentColor" aria-hidden="true">
            <path d="M50 92c18-3 31-15 36-32-17 4-31 14-36 32Zm0 0C32 89 19 77 14 60c17 4 31 14 36 32Zm0-10C33 74 25 58 30 39c11 12 19 25 20 43Zm0 0c17-8 25-24 20-43-11 12-19 25-20 43Z" />
        </svg>

        <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-16">
            <nav class="text-xs text-sand-100/70" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <a href="{{ route('homestay.index') }}" class="hover:text-white">Homestay</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-sand-100">{{ $homestay->nama_homestay }}</span>
            </nav>

            <div class="mt-6 max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.16em] text-sand-100 backdrop-blur">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    Homestay terverifikasi
                </span>
                <h1 class="mt-4 font-display text-4xl font-semibold leading-tight sm:text-5xl">{{ $homestay->nama_homestay }}</h1>
                <p class="mt-3 flex items-start gap-2 text-sm text-sand-100/80">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.25 7-11a7 7 0 1 0-14 0c0 5.75 7 11 7 11Z" />
                        <circle cx="12" cy="10" r="2.2" />
                    </svg>
                    {{ $homestay->alamat }}
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-4">
                    @if ($reviewCount > 0)
                        <div class="flex items-center gap-2">
                            <x-rating-stars :rating="$averageRating" />
                            <span class="text-sm font-semibold text-white">{{ number_format($averageRating, 1, ',', '.') }}</span>
                            <span class="text-xs text-sand-100/70">({{ $reviewCount }} ulasan)</span>
                        </div>
                    @else
                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs text-sand-100">Belum ada ulasan</span>
                    @endif
                    <span class="text-xs text-sand-100/70">Dikelola oleh {{ $homestay->pemilik->nama_pemilik }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
        <div class="grid gap-8 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
            <div class="space-y-6">
                <x-card>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-clay-600">Tentang homestay</p>
                    <h2 class="mt-2 font-display text-2xl font-semibold text-forest-950">Rumah kedua di desa wisata</h2>
                    <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-stone-600">{{ $homestay->deskripsi }}</p>
                </x-card>

                <x-card>
                    <h2 class="font-display text-2xl font-semibold text-forest-950">Fasilitas</h2>
                    @if ($homestay->fasilitas->isEmpty())
                        <p class="mt-3 text-sm text-stone-500">Pemilik belum menambahkan fasilitas.</p>
                    @else
                        <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($homestay->fasilitas as $facility)
                                <li class="flex items-center gap-3 rounded-2xl bg-sand-50 px-4 py-3 text-sm font-medium text-stone-700">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-forest-100 text-forest-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                    </span>
                                    {{ $facility->nama_fasilitas }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-card>

                <x-card padding="p-0">
                    <div class="border-b border-sand-200 p-5 sm:p-6">
                        <h2 class="font-display text-2xl font-semibold text-forest-950">Pilih kamar</h2>
                        <p class="mt-1 text-sm text-stone-500">Harga per malam sudah termasuk fasilitas homestay.</p>
                    </div>

                    @if ($homestay->kamar->isEmpty())
                        <div class="p-5 sm:p-6">
                            <x-empty-state title="Belum ada kamar tersedia" description="Pemilik sedang menyiapkan kamar untuk tamu." />
                        </div>
                    @else
                        <ul class="divide-y divide-sand-100">
                            @foreach ($homestay->kamar as $room)
                                <li class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="font-semibold text-forest-950">{{ $room->nama_kamar }}</h3>
                                            <x-badge :status="$room->status->value" />
                                        </div>
                                        <p class="mt-1.5 text-sm text-stone-500">Kapasitas maksimal {{ $room->kapasitas }} tamu</p>
                                        <p class="mt-1 font-display text-xl font-semibold text-forest-900">
                                            {{ rupiah($room->harga_per_malam) }}
                                            <span class="font-sans text-xs font-normal text-stone-500">/malam</span>
                                        </p>
                                    </div>

                                    <div class="sm:text-right">
                                        @auth
                                            @if (auth()->user()->role->value === 'wisatawan' && Route::has('wisatawan.booking.create'))
                                                <x-button :href="route('wisatawan.booking.create', $room)">Pesan Kamar</x-button>
                                            @else
                                                <span class="text-xs text-stone-400">Pemesanan hanya untuk akun wisatawan.</span>
                                            @endif
                                        @else
                                            <x-button :href="route('login')">Masuk untuk memesan</x-button>
                                        @endauth
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-card>

                @if ($homestay->paket->isNotEmpty())
                    <x-card>
                        <h2 class="font-display text-2xl font-semibold text-forest-950">Paket wisata desa</h2>
                        <p class="mt-1 text-sm text-stone-500">Tambahkan pengalaman lokal saat mengisi formulir pemesanan.</p>
                        <ul class="mt-4 space-y-3">
                            @foreach ($homestay->paket as $package)
                                <li class="flex flex-col gap-3 rounded-2xl border border-sand-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h3 class="font-semibold text-forest-950">{{ $package->nama_paket }}</h3>
                                        <p class="mt-1 text-sm leading-relaxed text-stone-500">{{ $package->deskripsi }}</p>
                                        <p class="mt-1.5 text-xs font-semibold text-clay-700">Durasi {{ $package->durasi }}</p>
                                    </div>
                                    <p class="shrink-0 font-display text-lg font-semibold text-forest-900">
                                        {{ rupiah($package->harga) }}
                                        <span class="font-sans text-xs font-normal text-stone-500">/peserta</span>
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    </x-card>
                @endif

                <x-card padding="p-0">
                    <div class="border-b border-sand-200 p-5 sm:p-6">
                        <div class="flex flex-wrap items-end justify-between gap-3">
                            <div>
                                <h2 class="font-display text-2xl font-semibold text-forest-950">Ulasan wisatawan</h2>
                                <p class="mt-1 text-sm text-stone-500">Cerita dari tamu yang sudah menginap.</p>
                            </div>
                            @if ($reviewCount > 0)
                                <div class="flex items-center gap-2 rounded-2xl bg-sand-50 px-4 py-2">
                                    <span class="font-display text-2xl font-semibold text-forest-900">{{ number_format($averageRating, 1, ',', '.') }}</span>
                                    <x-rating-stars :rating="$averageRating" />
                                    <span class="text-xs text-stone-500">({{ $reviewCount }})</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($reviews->isEmpty())
                        <div class="p-5 sm:p-6">
                            <x-empty-state title="Belum ada ulasan" description="Jadilah tamu pertama dan bagikan pengalaman Anda setelah menginap." />
                        </div>
                    @else
                        <ul class="divide-y divide-sand-100">
                            @foreach ($reviews as $review)
                                <li class="p-5 sm:p-6">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div>
                                            <p class="font-semibold text-forest-950">{{ $review->booking->wisatawan->nama_wisatawan }}</p>
                                            <p class="mt-0.5 text-xs text-stone-400">{{ tanggal_id($review->tgl_ulasan) }}</p>
                                        </div>
                                        <x-rating-stars :rating="$review->rating" />
                                    </div>
                                    <p class="mt-3 text-sm leading-relaxed text-stone-600">{{ $review->komentar }}</p>
                                </li>
                            @endforeach
                        </ul>
                        <div class="px-5 pb-5 sm:px-6">
                            <x-pagination :paginator="$reviews" />
                        </div>
                    @endif
                </x-card>
            </div>

            <aside class="space-y-4 lg:sticky lg:top-28">
                <x-card>
                    <h2 class="font-display text-xl font-semibold text-forest-950">Cek ketersediaan</h2>
                    <p class="mt-1 text-sm text-stone-500">Pilih tanggal menginap untuk melihat kamar yang masih kosong.</p>

                    <div
                        x-data="{
                            checkin: '{{ now()->toDateString() }}',
                            checkout: '{{ now()->addDay()->toDateString() }}',
                            loading: false,
                            error: '',
                            result: null,
                            formatRupiah(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            },
                            async check() {
                                this.loading = true;
                                this.error = '';
                                this.result = null;
                                try {
                                    const params = new URLSearchParams({ checkin: this.checkin, checkout: this.checkout });
                                    const response = await fetch('{{ route('homestay.availability', $homestay) }}?' + params.toString(), {
                                        headers: { 'Accept': 'application/json' }
                                    });
                                    const data = await response.json();
                                    if (! response.ok) {
                                        this.error = data.message || 'Tanggal belum valid.';
                                        if (data.errors) {
                                            this.error = Object.values(data.errors).flat().join(' ');
                                        }
                                        return;
                                    }
                                    this.result = data;
                                } catch (exception) {
                                    this.error = 'Tidak dapat memeriksa ketersediaan. Silakan coba lagi.';
                                } finally {
                                    this.loading = false;
                                }
                            }
                        }"
                    >
                        <form class="mt-4 space-y-3" @submit.prevent="check">
                            <div>
                                <label for="availability-checkin" class="label">Tanggal check-in</label>
                                <input id="availability-checkin" type="date" x-model="checkin" class="field" :min="'{{ now()->toDateString() }}'">
                            </div>
                            <div>
                                <label for="availability-checkout" class="label">Tanggal check-out</label>
                                <input id="availability-checkout" type="date" x-model="checkout" class="field" :min="checkin">
                            </div>
                            <button type="submit" class="focus-ring inline-flex w-full items-center justify-center rounded-full bg-forest-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-forest-800 disabled:opacity-60" :disabled="loading">
                                <span x-show="! loading">Cek Ketersediaan</span>
                                <span x-show="loading" x-cloak>Memeriksa...</span>
                            </button>
                        </form>

                        <p x-show="error" x-cloak class="mt-3 rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700" x-text="error" role="alert"></p>

                        <div x-show="result" x-cloak class="mt-4 border-t border-sand-100 pt-4">
                            <p class="text-xs text-stone-500" x-text="'Menginap ' + result?.nights + ' malam'"></p>
                            <ul class="mt-2 space-y-2">
                                <template x-for="room in result?.rooms ?? []" :key="room.id_kamar">
                                    <li class="flex items-center justify-between gap-3 rounded-2xl bg-sand-50 px-3 py-2.5">
                                        <div>
                                            <p class="text-sm font-semibold text-forest-950" x-text="room.nama_kamar"></p>
                                            <p class="text-xs text-stone-500" x-text="'Maks. ' + room.kapasitas + ' tamu'"></p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="rounded-full px-2 py-0.5 text-[10px] font-bold"
                                                :class="room.tersedia ? 'bg-forest-100 text-forest-800' : 'bg-red-50 text-red-700'"
                                                x-text="room.tersedia ? 'Tersedia' : 'Penuh'"
                                            ></span>
                                            <p class="mt-1 text-xs font-semibold text-forest-900" x-text="formatRupiah(room.total)"></p>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                            <p x-show="result && result.rooms.length === 0" class="text-sm text-stone-500">Belum ada kamar aktif di homestay ini.</p>
                        </div>
                    </div>
                </x-card>

                <x-card class="bg-forest-900 !border-forest-800 text-sand-50">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-sand-300">Dikelola oleh</p>
                    <h2 class="mt-2 font-display text-xl font-semibold text-white">{{ $homestay->pemilik->nama_pemilik }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-sand-100/75">{{ $homestay->pemilik->alamat }}</p>
                    <div class="mt-4 border-t border-white/10 pt-4">
                        <p class="text-xs text-sand-200/70">Butuh bantuan sebelum memesan? Hubungi pengelola desa wisata melalui admin HomeStay Go.</p>
                    </div>
                </x-card>
            </aside>
        </div>
    </section>
@endsection
