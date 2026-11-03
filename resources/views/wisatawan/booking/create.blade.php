@extends('layouts.app')

@section('title', 'Pesan '.$kamar->nama_kamar)

@section('content')
    @php
        $homestay = $kamar->homestay;
        $paketHarga = $homestay->paket->mapWithKeys(fn ($package) => [$package->id_paket => (float) $package->harga])->all();
        $paketConfig = $homestay->paket->mapWithKeys(fn ($package) => [$package->id_paket => (int) ($selectedPackages[$package->id_paket] ?? 0)])->all();
        $defaultCheckin = old('tgl_checkin', now()->addDay()->toDateString());
        $defaultCheckout = old('tgl_checkout', now()->addDays(3)->toDateString());
    @endphp

    <section class="border-b border-sand-200 bg-gradient-to-b from-sand-100 to-sand-50">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
            <nav class="text-xs text-stone-500" aria-label="Breadcrumb">
                <a href="{{ route('homestay.index') }}" class="hover:text-forest-700">Homestay</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <a href="{{ route('homestay.show', $homestay) }}" class="hover:text-forest-700">{{ $homestay->nama_homestay }}</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-stone-700">Pemesanan</span>
            </nav>

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <x-page-heading
                    eyebrow="Formulir pemesanan"
                    title="Atur tanggal menginap Anda"
                    description="Lengkapi detail perjalanan, tambahkan paket wisata jika ingin, lalu lanjutkan ke pembayaran."
                />
                <div class="rounded-3xl border border-sand-200 bg-white px-5 py-4 shadow-soft">
                    <p class="text-xs text-stone-500">{{ $homestay->nama_homestay }}</p>
                    <p class="mt-0.5 font-display text-lg font-semibold text-forest-950">{{ $kamar->nama_kamar }}</p>
                    <p class="mt-1 text-xs text-stone-500">Maks. {{ $kamar->kapasitas }} tamu · {{ rupiah($kamar->harga_per_malam) }}/malam</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
        <form
            method="POST"
            action="{{ route('wisatawan.booking.store') }}"
            x-data="bookingForm({
                checkin: @js($defaultCheckin),
                checkout: @js($defaultCheckout),
                tamu: @js((int) old('jumlah_tamu', 1)),
                harga: @js((float) $kamar->harga_per_malam),
                paket: @js($paketConfig),
                paketHarga: @js($paketHarga),
            })"
            class="grid gap-8 lg:grid-cols-[1.35fr_0.65fr] lg:items-start"
        >
            @csrf
            <input type="hidden" name="id_kamar" value="{{ $kamar->id_kamar }}">

            <div class="space-y-6">
                <x-card>
                    <h2 class="font-display text-xl font-semibold text-forest-950">Detail menginap</h2>
                    <p class="mt-1 text-sm text-stone-500">Tanggal check-in dan check-out menentukan jumlah malam.</p>

                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <x-form.input
                            name="tgl_checkin"
                            type="date"
                            label="Tanggal check-in"
                            :value="$defaultCheckin"
                            min="{{ now()->toDateString() }}"
                            x-model="checkin"
                            required
                        />

                        <x-form.input
                            name="tgl_checkout"
                            type="date"
                            label="Tanggal check-out"
                            :value="$defaultCheckout"
                            min="{{ now()->addDay()->toDateString() }}"
                            x-model="checkout"
                            required
                        />

                        <x-form.input
                            name="jumlah_tamu"
                            type="number"
                            label="Jumlah tamu"
                            :value="old('jumlah_tamu', 1)"
                            min="1"
                            max="{{ $kamar->kapasitas }}"
                            hint="Kapasitas kamar maksimal {{ $kamar->kapasitas }} tamu."
                            x-model.number="jumlahTamu"
                            required
                        />

                        <div class="flex items-end">
                            <div class="w-full rounded-2xl bg-sand-50 px-4 py-3">
                                <p class="text-xs text-stone-500">Durasi menginap</p>
                                <p class="mt-1 font-display text-2xl font-semibold text-forest-900">
                                    <span x-text="malam"></span> malam
                                </p>
                            </div>
                        </div>
                    </div>
                </x-card>

                @if ($homestay->paket->isNotEmpty())
                    <x-card>
                        <h2 class="font-display text-xl font-semibold text-forest-950">Paket wisata desa</h2>
                        <p class="mt-1 text-sm text-stone-500">Opsional. Isi jumlah peserta untuk menambahkan paket ke pesanan.</p>

                        <div class="mt-5 space-y-3">
                            @foreach ($homestay->paket as $package)
                                <div class="rounded-2xl border border-sand-200 p-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <h3 class="font-semibold text-forest-950">{{ $package->nama_paket }}</h3>
                                            <p class="mt-1 text-sm leading-relaxed text-stone-500">{{ $package->deskripsi }}</p>
                                            <p class="mt-1.5 text-xs font-semibold text-clay-700">
                                                {{ rupiah($package->harga) }} / peserta · {{ $package->durasi }}
                                            </p>
                                        </div>
                                        <div class="w-full sm:w-32">
                                            <label for="paket-{{ $package->id_paket }}" class="label">Peserta</label>
                                            <input
                                                id="paket-{{ $package->id_paket }}"
                                                type="number"
                                                name="paket[{{ $package->id_paket }}]"
                                                min="0"
                                                max="20"
                                                value="{{ $selectedPackages[$package->id_paket] ?? 0 }}"
                                                x-model.number="paket[{{ $package->id_paket }}]"
                                                class="field"
                                            >
                                        </div>
                                    </div>
                                    @error('paket.'.$package->id_paket)
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                @endif

                <x-card>
                    <h2 class="font-display text-xl font-semibold text-forest-950">Fasilitas homestay</h2>
                    @if ($homestay->fasilitas->isEmpty())
                        <p class="mt-2 text-sm text-stone-500">Pemilik belum menambahkan fasilitas.</p>
                    @else
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($homestay->fasilitas as $facility)
                                <span class="rounded-full bg-sand-100 px-3 py-1.5 text-xs font-semibold text-sand-800">{{ $facility->nama_fasilitas }}</span>
                            @endforeach
                        </div>
                    @endif
                </x-card>
            </div>

            <aside class="space-y-4 lg:sticky lg:top-28">
                <x-card>
                    <h2 class="font-display text-xl font-semibold text-forest-950">Ringkasan biaya</h2>

                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-stone-600">
                                Kamar<br>
                                <span class="text-xs text-stone-400"><span x-text="formatRupiah(hargaKamar)"></span> × <span x-text="malam"></span> malam</span>
                            </dt>
                            <dd class="font-semibold text-forest-950" x-text="formatRupiah(totalKamar)"></dd>
                        </div>
                        <div class="flex items-start justify-between gap-3" x-show="totalPaket > 0" x-cloak>
                            <dt class="text-stone-600">Paket wisata</dt>
                            <dd class="font-semibold text-forest-950" x-text="formatRupiah(totalPaket)"></dd>
                        </div>
                        <div class="border-t border-sand-200 pt-3">
                            <div class="flex items-end justify-between gap-3">
                                <dt class="font-semibold text-forest-950">Total bayar</dt>
                                <dd class="font-display text-2xl font-semibold text-forest-900" x-text="formatRupiah(grandTotal)"></dd>
                            </div>
                        </div>
                    </dl>

                    <p class="mt-4 rounded-2xl bg-sand-50 px-4 py-3 text-xs leading-relaxed text-stone-500">
                        Total dihitung otomatis dan disimpan saat pesanan dibuat. Pembayaran diverifikasi admin sebelum pemilik mengonfirmasi.
                    </p>

                    <x-button type="submit" size="lg" class="mt-5 w-full">Buat Pemesanan</x-button>
                    <x-button :href="route('homestay.show', $homestay)" variant="ghost" size="sm" class="mt-2 w-full">Kembali ke homestay</x-button>
                </x-card>
            </aside>
        </form>
    </section>
@endsection
