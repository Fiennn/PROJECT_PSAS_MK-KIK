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

    <!-- Header Section -->
    <section class="border-b border-stone-200/80 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
            <nav class="flex items-center gap-2 text-xs text-stone-500 font-medium mb-3" aria-label="Breadcrumb">
                <a href="{{ route('homestay.index') }}" class="hover:text-[#13391b] transition-colors">Homestay</a>
                <span class="text-stone-300">/</span>
                <a href="{{ route('homestay.show', $homestay) }}" class="hover:text-[#13391b] transition-colors">{{ $homestay->nama_homestay }}</a>
                <span class="text-stone-300">/</span>
                <span class="text-stone-900 font-bold">Pemesanan Kamar</span>
            </nav>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-100 text-[#13391b] text-xs font-semibold mb-2">
                        <span class="material-symbols-outlined text-sm text-[#ea580c]">event_seat</span>
                        <span>Formulir Reservasi Resmi Pokdarwis</span>
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900">Atur Jadwal Menginap Anda</h1>
                    <p class="mt-1 text-sm text-stone-500 max-w-xl">
                        Pilih tanggal check-in, tentukan jumlah tamu, dan tambahkan paket wisata desa untuk liburan yang tak terlupakan.
                    </p>
                </div>

                <div class="rounded-2xl border border-stone-200 bg-[#faf9f5] px-5 py-3.5 shadow-xs shrink-0">
                    <p class="text-xs text-stone-500 font-medium">{{ $homestay->nama_homestay }}</p>
                    <p class="text-base font-extrabold text-stone-900">{{ $kamar->nama_kamar }}</p>
                    <p class="text-xs font-semibold text-[#13391b]">Maks. {{ $kamar->kapasitas }} tamu · {{ rupiah($kamar->harga_per_malam) }}/malam</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Booking Form -->
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
                <!-- Detail Tanggal & Tamu -->
                <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-emerald-700 text-xl">date_range</span>
                        <h2 class="text-lg font-bold text-stone-900">Detail Menginap</h2>
                    </div>
                    <p class="text-xs text-stone-500 mb-6">Kalender otomatis memvalidasi ketersediaan tanpa double booking.</p>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.input
                            name="tgl_checkin"
                            type="date"
                            label="Tanggal Check-in"
                            :value="$defaultCheckin"
                            min="{{ now()->toDateString() }}"
                            x-model="checkin"
                            required
                        />

                        <x-form.input
                            name="tgl_checkout"
                            type="date"
                            label="Tanggal Check-out"
                            :value="$defaultCheckout"
                            min="{{ now()->addDay()->toDateString() }}"
                            x-model="checkout"
                            required
                        />

                        <x-form.input
                            name="jumlah_tamu"
                            type="number"
                            label="Jumlah Tamu"
                            :value="old('jumlah_tamu', 1)"
                            min="1"
                            max="{{ $kamar->kapasitas }}"
                            hint="Kapasitas maksimal kamar ini {{ $kamar->kapasitas }} tamu."
                            x-model.number="jumlahTamu"
                            required
                        />

                        <div class="flex items-end">
                            <div class="w-full rounded-2xl bg-[#faf9f5] border border-stone-200/80 px-4 py-3">
                                <p class="text-xs text-stone-500 font-medium">Durasi Menginap Terpilih</p>
                                <p class="mt-0.5 text-2xl font-extrabold text-[#13391b]">
                                    <span x-text="malam"></span> <span class="text-sm font-semibold text-stone-600">malam</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tambahan Paket Wisata -->
                @if ($homestay->paket->isNotEmpty())
                    <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
                        <div class="flex items-center justify-between gap-4 mb-1">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-emerald-700 text-xl">hiking</span>
                                <h2 class="text-lg font-bold text-stone-900">Paket Wisata Desa (Opsional)</h2>
                            </div>
                            <span class="text-[11px] font-bold text-[#ea580c] uppercase">Pengalaman Lokal</span>
                        </div>
                        <p class="text-xs text-stone-500 mb-6">Isi jumlah peserta jika ingin menambahkan aktivitas tur bersama warga.</p>

                        <div class="space-y-3.5">
                            @foreach ($homestay->paket as $package)
                                <div class="rounded-2xl border border-stone-200 bg-[#faf9f5] p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-bold text-stone-900 text-sm">{{ $package->nama_paket }}</h3>
                                            <span class="px-2 py-0.5 rounded-md bg-stone-200/80 text-[10px] font-bold text-stone-700">{{ $package->durasi }}</span>
                                        </div>
                                        <p class="mt-1 text-xs text-stone-500 leading-relaxed">{{ $package->deskripsi }}</p>
                                        <p class="mt-1.5 text-xs font-extrabold text-[#13391b]">
                                            {{ rupiah($package->harga) }} <span class="text-[11px] font-normal text-stone-500">/ peserta</span>
                                        </p>
                                    </div>
                                    <div class="w-full sm:w-32 shrink-0">
                                        <label for="paket-{{ $package->id_paket }}" class="block text-xs font-bold text-stone-600 uppercase mb-1">Peserta</label>
                                        <input
                                            id="paket-{{ $package->id_paket }}"
                                            type="number"
                                            name="paket[{{ $package->id_paket }}]"
                                            min="0"
                                            max="20"
                                            value="{{ $selectedPackages[$package->id_paket] ?? 0 }}"
                                            x-model.number="paket[{{ $package->id_paket }}]"
                                            class="w-full rounded-xl border border-stone-300 bg-white px-3 py-2 text-sm font-bold text-stone-900 focus:border-[#13391b] focus:outline-none focus:ring-2 focus:ring-emerald-200"
                                        />
                                    </div>
                                </div>
                                @error('paket.'.$package->id_paket)
                                    <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Fasilitas Homestay -->
                <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
                    <h2 class="text-sm font-bold text-stone-900 uppercase tracking-wider mb-3">Fasilitas Termasuk</h2>
                    @if ($homestay->fasilitas->isEmpty())
                        <p class="text-xs text-stone-500">Fasilitas standar desa wisata telah disediakan.</p>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach ($homestay->fasilitas as $facility)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-[#faf9f5] border border-stone-200 text-xs font-semibold text-stone-700">
                                    <span class="material-symbols-outlined text-xs text-[#1b4d24]">check_circle</span>
                                    <span>{{ $facility->nama_fasilitas }}</span>
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ringkasan Biaya Sidebar -->
            <aside class="space-y-4 lg:sticky lg:top-28">
                <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-7 shadow-xs">
                    <h2 class="text-lg font-bold text-stone-900 mb-1">Ringkasan Biaya</h2>
                    <p class="text-xs text-stone-500 mb-5">Rincian transparan tanpa biaya tersembunyi.</p>

                    <dl class="space-y-3.5 text-sm">
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-stone-600 text-xs">
                                Kamar (<span x-text="malam"></span> malam)<br>
                                <span class="text-[11px] text-stone-400"><span x-text="formatRupiah(hargaKamar)"></span> / malam</span>
                            </dt>
                            <dd class="font-bold text-stone-900" x-text="formatRupiah(totalKamar)"></dd>
                        </div>

                        <div class="flex items-start justify-between gap-3 border-t border-stone-100 pt-3" x-show="totalPaket > 0" x-cloak>
                            <dt class="text-stone-600 text-xs">Total Paket Wisata</dt>
                            <dd class="font-bold text-stone-900" x-text="formatRupiah(totalPaket)"></dd>
                        </div>

                        <div class="border-t-2 border-stone-100 pt-4">
                            <div class="flex items-end justify-between gap-3">
                                <dt class="font-extrabold text-stone-900">Total Pembayaran</dt>
                                <dd class="text-2xl font-extrabold text-[#13391b]" x-text="formatRupiah(grandTotal)"></dd>
                            </div>
                        </div>
                    </dl>

                    <div class="mt-5 rounded-2xl bg-emerald-50 border border-emerald-500/20 p-3.5 text-xs text-emerald-900 flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-lg text-[#13391b] shrink-0 mt-0.5">verified_user</span>
                        <p class="leading-relaxed">
                            <strong>Garansi 100% Pokdarwis:</strong> Tanggal kamar langsung terkunci saat reservasi dikirim untuk mencegah booking bentrok.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="w-full mt-6 inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-sm font-bold shadow-xs hover:shadow-sm transition-all cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        <span>Konfirmasi &amp; Lanjut Pembayaran</span>
                    </button>

                    <a
                        href="{{ route('homestay.show', $homestay) }}"
                        class="w-full mt-2.5 inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-white border border-stone-200 text-stone-600 hover:text-stone-900 text-xs font-semibold hover:bg-stone-50 transition-colors"
                    >
                        Kembali ke Detail Homestay
                    </a>
                </div>
            </aside>
        </form>
    </section>
@endsection
