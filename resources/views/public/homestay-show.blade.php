@extends('layouts.app')

@section('title', $homestay->nama_homestay)
@section('meta_description', 'Detail kamar, fasilitas, paket wisata, dan ulasan '.$homestay->nama_homestay.' binaan Pokdarwis.')

@section('content')
    @php
        $curatedPhotos = [
            'Homestay Rumah Kayu' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1600&q=85',
            'Homestay Rumah Kayu Asri' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1600&q=85',
            'Griya Sawah Hijau' => 'https://images.unsplash.com/photo-1587061949409-02df41d5e562?auto=format&fit=crop&w=1600&q=85',
            'Joglo Lembah Serayu' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1600&q=85',
            'Omah Jati' => 'https://images.unsplash.com/photo-1512100356356-de1b84283e18?auto=format&fit=crop&w=1600&q=85',
            'Omah Jati Penglipuran' => 'https://images.unsplash.com/photo-1512100356356-de1b84283e18?auto=format&fit=crop&w=1600&q=85',
            'Rumah Bambu Lestari' => 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?auto=format&fit=crop&w=1600&q=85',
            'Homestay Baturraden Asri' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=85',
            'Homestay Bukit Menoreh' => 'https://images.unsplash.com/photo-1510798831971-661eb04b3739?auto=format&fit=crop&w=1600&q=85',
            'Pondok Kopi Priangan' => 'https://images.unsplash.com/photo-1518780664697-55e3ad937233?auto=format&fit=crop&w=1600&q=85',
        ];
        $fallbackPhotos = [
            'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1600&q=85',
            'https://images.unsplash.com/photo-1587061949409-02df41d5e562?auto=format&fit=crop&w=1600&q=85',
            'https://images.unsplash.com/photo-1512100356356-de1b84283e18?auto=format&fit=crop&w=1600&q=85',
            'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?auto=format&fit=crop&w=1600&q=85',
        ];
        $photoUrl = $curatedPhotos[$homestay->nama_homestay] ?? $fallbackPhotos[$homestay->id_homestay % count($fallbackPhotos)];

        $facilityIcons = [
            'wifi' => 'wifi',
            'internet' => 'wifi',
            'sarapan' => 'restaurant',
            'air panas' => 'water_heater',
            'parkir' => 'local_parking',
            'kamar mandi dalam' => 'bathtub',
            'dapur' => 'kitchen',
            'pemandangan' => 'landscape',
            'tur' => 'explore',
            'pemandu' => 'badge',
            'santai' => 'deck',
        ];
    @endphp

    <!-- Cinematic Hero Cover Section -->
    <section class="relative min-h-[380px] sm:min-h-[440px] flex items-end bg-stone-950 text-white overflow-hidden">
        <!-- Background Photo with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ $photoUrl }}" alt="{{ $homestay->nama_homestay }}" class="h-full w-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-950/60 to-stone-950/30"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl w-full px-4 py-8 sm:px-6 lg:px-8 sm:py-12">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-stone-300 font-medium mb-4" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-emerald-400 transition-colors">Beranda</a>
                <span class="text-stone-500">/</span>
                <a href="{{ route('homestay.index') }}" class="hover:text-emerald-400 transition-colors">Homestay</a>
                <span class="text-stone-500">/</span>
                <span class="text-stone-100 font-bold truncate max-w-xs">{{ $homestay->nama_homestay }}</span>
            </nav>

            <div class="max-w-3xl">
                <!-- Badges -->
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white text-stone-900 text-xs font-bold shadow-md">
                        <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
                        <span>Terverifikasi Pokdarwis</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/20 text-white text-xs font-semibold">
                        <span class="material-symbols-outlined text-sm text-amber-300">eco</span>
                        <span>Eco-Hospitality</span>
                    </span>
                </div>

                <!-- Nama Homestay -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-white drop-shadow">
                    {{ $homestay->nama_homestay }}
                </h1>

                <!-- Lokasi Alamat -->
                <p class="mt-2.5 flex items-center gap-1.5 text-sm sm:text-base text-stone-200">
                    <span class="material-symbols-outlined text-emerald-400 text-lg shrink-0">location_on</span>
                    <span>{{ $homestay->alamat }}</span>
                </p>

                <!-- Rating & Pengelola -->
                <div class="mt-4 flex flex-wrap items-center gap-4 text-xs sm:text-sm">
                    @if ($reviewCount > 0)
                        <div class="flex items-center gap-1.5 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-amber-400 text-base">star</span>
                            <span class="font-bold text-white">{{ number_format($averageRating, 1, ',', '.') }}</span>
                            <span class="text-stone-300">({{ $reviewCount }} ulasan wisatawan)</span>
                        </div>
                    @else
                        <span class="bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-full text-stone-300 text-xs">
                            ★ 4.9 (Desa Mitra Baru)
                        </span>
                    @endif

                    <span class="text-stone-300 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm text-stone-400">person</span>
                        <span>Dikelola oleh <strong>{{ $homestay->pemilik->nama_pemilik }}</strong></span>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Layout (Kiri: Detail, Kamar, Paket, Ulasan; Kanan: Sidebar Ketersediaan & Host) -->
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
        <div class="grid gap-8 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
            
            <!-- Left Column Content -->
            <div class="space-y-8">
                
                <!-- Tentang Homestay -->
                <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#ea580c]">Suasana &amp; Keasrian</p>
                    <h2 class="mt-1 text-2xl font-extrabold text-stone-900">Tentang Homestay Ini</h2>
                    <p class="mt-3 whitespace-pre-line text-sm sm:text-base leading-relaxed text-stone-600">
                        {{ $homestay->deskripsi }}
                    </p>
                </div>

                <!-- Fasilitas Homestay -->
                <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
                    <h2 class="text-2xl font-extrabold text-stone-900">Fasilitas Tersedia</h2>
                    <p class="mt-1 text-xs text-stone-500">Fasilitas bersama yang dapat dinikmati selama menginap.</p>

                    @if ($homestay->fasilitas->isEmpty())
                        <p class="mt-4 text-sm text-stone-500">Pemilik belum menambahkan fasilitas terperinci.</p>
                    @else
                        <ul class="mt-5 grid gap-3 sm:grid-cols-2">
                            @foreach ($homestay->fasilitas as $facility)
                                @php
                                    $fIcon = 'check_circle';
                                    $fLow = strtolower($facility->nama_fasilitas);
                                    foreach ($facilityIcons as $k => $v) {
                                        if (str_contains($fLow, $k)) {
                                            $fIcon = $v;
                                            break;
                                        }
                                    }
                                @endphp
                                <li class="flex items-center gap-3 rounded-2xl bg-[#faf9f5] border border-stone-200/80 px-4 py-3 text-sm font-semibold text-stone-800">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-[#13391b]">
                                        <span class="material-symbols-outlined text-lg">{{ $fIcon }}</span>
                                    </span>
                                    <span>{{ $facility->nama_fasilitas }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Pilihan Kamar -->
                <div class="rounded-3xl border border-stone-200 bg-white overflow-hidden shadow-xs">
                    <div class="border-b border-stone-200/80 p-6 sm:p-8 bg-[#faf9f5]/50">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-extrabold text-stone-900">Pilihan Kamar</h2>
                                <p class="mt-1 text-xs text-stone-500">Harga per malam sudah termasuk sarapan khas desa dan fasilitas dasar.</p>
                            </div>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-100 text-[#13391b] text-xs font-bold">
                                <span class="material-symbols-outlined text-sm">bed</span>
                                <span>{{ $homestay->kamar->count() }} Tipe Kamar</span>
                            </span>
                        </div>
                    </div>

                    @if ($homestay->kamar->isEmpty())
                        <div class="p-8 text-center">
                            <span class="material-symbols-outlined text-4xl text-stone-300">hotel</span>
                            <p class="mt-2 text-sm text-stone-500">Belum ada kamar aktif yang dapat dipesan.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-stone-100">
                            @foreach ($homestay->kamar as $room)
                                <li class="p-6 sm:p-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between hover:bg-stone-50/50 transition-colors">
                                    <div>
                                        <div class="flex items-center gap-2.5">
                                            <h3 class="text-lg font-bold text-stone-900">{{ $room->nama_kamar }}</h3>
                                            @if ($room->status->value === 'tersedia')
                                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-[#13391b] text-xs font-bold">Tersedia</span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full bg-stone-100 text-stone-500 text-xs font-semibold">Nonaktif</span>
                                            @endif
                                        </div>
                                        <p class="mt-1 text-xs text-stone-500 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm text-stone-400">group</span>
                                            <span>Kapasitas maksimal {{ $room->kapasitas }} tamu</span>
                                        </p>
                                        <p class="mt-2 text-lg font-extrabold text-[#13391b]">
                                            {{ rupiah($room->harga_per_malam) }}
                                            <span class="text-xs font-normal text-stone-500">/malam</span>
                                        </p>
                                    </div>

                                    <div class="sm:text-right shrink-0">
                                        @auth
                                            @if (auth()->user()->role->value === 'wisatawan' && Route::has('wisatawan.booking.create'))
                                                <a
                                                    href="{{ route('wisatawan.booking.create', $room) }}"
                                                    class="inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs hover:shadow-sm transition-all cursor-pointer"
                                                >
                                                    <span class="material-symbols-outlined text-base">calendar_month</span>
                                                    <span>Pesan Kamar</span>
                                                </a>
                                            @else
                                                <span class="text-xs text-stone-400 block max-w-xs">Pemesanan hanya untuk akun wisatawan.</span>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('login') }}"
                                                class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-full bg-stone-900 hover:bg-black text-white text-xs font-bold shadow-xs transition-colors cursor-pointer"
                                            >
                                                <span>Masuk untuk Memesan</span>
                                            </a>
                                        @endauth
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Paket Wisata Desa Mitra -->
                @if ($homestay->paket->isNotEmpty())
                    <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
                        <div class="flex items-center justify-between gap-4 mb-2">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-[#ea580c]">Pengalaman Budaya &amp; Alam</p>
                                <h2 class="text-2xl font-extrabold text-stone-900">Paket Wisata Desa</h2>
                            </div>
                            <span class="material-symbols-outlined text-3xl text-emerald-700">tour</span>
                        </div>
                        <p class="text-xs text-stone-500 mb-6">Tambahkan aktivitas berkesan ini langsung saat mengisi formulir pemesanan kamar.</p>

                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach ($homestay->paket as $package)
                                <div class="rounded-2xl border border-stone-200 bg-[#faf9f5] p-5 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between gap-2">
                                            <h3 class="font-bold text-stone-900 text-base">{{ $package->nama_paket }}</h3>
                                            <span class="px-2 py-0.5 rounded-md bg-stone-200/80 text-stone-700 text-[10px] font-semibold">{{ $package->durasi }}</span>
                                        </div>
                                        <p class="mt-2 text-xs text-stone-600 leading-relaxed">{{ $package->deskripsi }}</p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-stone-200 flex items-center justify-between">
                                        <span class="text-[11px] text-stone-400 font-medium">Biaya per peserta</span>
                                        <span class="text-sm font-extrabold text-[#13391b]">{{ rupiah($package->harga) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Ulasan Wisatawan -->
                <div class="rounded-3xl border border-stone-200 bg-white overflow-hidden shadow-xs">
                    <div class="p-6 sm:p-8 border-b border-stone-200/80 bg-[#faf9f5]/50 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-extrabold text-stone-900">Ulasan Wisatawan</h2>
                            <p class="mt-1 text-xs text-stone-500">Pengalaman nyata dari tamu yang telah menginap.</p>
                        </div>
                        @if ($reviewCount > 0)
                            <div class="flex items-center gap-2 px-4 py-2 rounded-2xl bg-white border border-stone-200 shadow-xs">
                                <span class="text-2xl font-extrabold text-[#13391b]">{{ number_format($averageRating, 1, ',', '.') }}</span>
                                <div class="text-xs">
                                    <div class="flex text-amber-500">
                                        @for ($i = 0; $i < 5; $i++)
                                            <span class="material-symbols-outlined text-sm">star</span>
                                        @endfor
                                    </div>
                                    <span class="text-stone-500 font-medium">{{ $reviewCount }} total ulasan</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($reviews->isEmpty())
                        <div class="p-8 text-center text-stone-500">
                            <span class="material-symbols-outlined text-4xl text-stone-300">rate_review</span>
                            <p class="mt-2 text-sm font-semibold text-stone-700">Belum ada ulasan untuk homestay ini.</p>
                            <p class="text-xs text-stone-400 mt-0.5">Jadilah tamu pertama yang memberikan pengalaman menginap setelah liburanmu selesai.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-stone-100">
                            @foreach ($reviews as $review)
                                <li class="p-6 sm:p-8">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-emerald-100 text-[#13391b] font-bold text-sm flex items-center justify-center">
                                                {{ strtoupper(substr($review->booking->wisatawan->nama_wisatawan ?? 'W', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-stone-900 text-sm">{{ $review->booking->wisatawan->nama_wisatawan }}</p>
                                                <p class="text-[11px] text-stone-400">{{ tanggal_id($review->tgl_ulasan) }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1 text-amber-500">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="material-symbols-outlined text-sm {{ $i <= $review->rating ? 'text-amber-500' : 'text-stone-300' }}">star</span>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mt-3 text-sm text-stone-600 leading-relaxed">{{ $review->komentar }}</p>
                                </li>
                            @endforeach
                        </ul>

                        <div class="p-6 border-t border-stone-100">
                            <x-pagination :paginator="$reviews" />
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Sidebar: Cek Ketersediaan & Info Pengelola -->
            <aside class="space-y-6 lg:sticky lg:top-28">
                
                <!-- Card Cek Ketersediaan Kalender Interaktif -->
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
                    class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs"
                >
                    <div class="flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-emerald-700 text-xl">event_available</span>
                        <h2 class="text-lg font-bold text-stone-900">Cek ketersediaan</h2>
                    </div>
                    <p class="text-xs text-stone-500">Pilih rentang tanggal menginap untuk melihat kamar yang kosong.</p>

                    <form class="mt-4 space-y-3.5" @submit.prevent="check">
                        <div>
                            <label for="availability-checkin" class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Check-in</label>
                            <input
                                id="availability-checkin"
                                type="date"
                                x-model="checkin"
                                class="w-full rounded-2xl border border-stone-200 bg-[#faf9f5] px-4 py-2.5 text-sm font-semibold text-stone-900 focus:border-[#13391b] focus:outline-none focus:ring-2 focus:ring-emerald-200"
                                :min="'{{ now()->toDateString() }}'"
                            />
                        </div>

                        <div>
                            <label for="availability-checkout" class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Check-out</label>
                            <input
                                id="availability-checkout"
                                type="date"
                                x-model="checkout"
                                class="w-full rounded-2xl border border-stone-200 bg-[#faf9f5] px-4 py-2.5 text-sm font-semibold text-stone-900 focus:border-[#13391b] focus:outline-none focus:ring-2 focus:ring-emerald-200"
                                :min="checkin"
                            />
                        </div>

                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all cursor-pointer disabled:opacity-60"
                            :disabled="loading"
                        >
                            <span class="material-symbols-outlined text-base" x-show="!loading">search_check</span>
                            <span x-show="!loading">Periksa Kalender</span>
                            <span x-show="loading" x-cloak>Memeriksa ketersediaan...</span>
                        </button>
                    </form>

                    <p x-show="error" x-cloak class="mt-3 rounded-2xl bg-red-50 border border-red-200 p-3 text-xs text-red-700" x-text="error"></p>

                    <div x-show="result" x-cloak class="mt-4 border-t border-stone-100 pt-4">
                        <p class="text-xs font-bold text-stone-600" x-text="'Durasi: ' + result?.nights + ' malam'"></p>
                        <ul class="mt-2.5 space-y-2">
                            <template x-for="room in result?.rooms ?? []" :key="room.id_kamar">
                                <li class="flex items-center justify-between gap-3 rounded-2xl bg-[#faf9f5] border border-stone-200/80 p-3">
                                    <div>
                                        <p class="text-xs font-bold text-stone-900" x-text="room.nama_kamar"></p>
                                        <p class="text-[11px] text-stone-500" x-text="'Kapasitas ' + room.kapasitas + ' orang'"></p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="inline-block rounded-full px-2 py-0.5 text-[10px] font-bold"
                                            :class="room.tersedia ? 'bg-emerald-100 text-[#13391b]' : 'bg-red-50 text-red-700'"
                                            x-text="room.tersedia ? 'Tersedia' : 'Penuh'"
                                        ></span>
                                        <p class="mt-1 text-xs font-extrabold text-[#13391b]" x-text="formatRupiah(room.total)"></p>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <!-- Card Info Pengelola Homestay -->
                <div class="rounded-3xl border border-stone-800 bg-stone-950 text-white p-6 shadow-xs">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-emerald-400 text-xl">store</span>
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-400">Pengelola Resmi</p>
                    </div>
                    <h3 class="text-xl font-extrabold text-white">{{ $homestay->pemilik->nama_pemilik }}</h3>
                    <p class="mt-2 text-xs text-stone-400 leading-relaxed">{{ $homestay->pemilik->alamat }}</p>

                    <div class="mt-4 pt-4 border-t border-stone-800/80 text-xs text-stone-400 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-amber-400">verified_user</span>
                        <span>Didampingi Pokdarwis setempat &amp; sistem garansi HomeStay Go.</span>
                    </div>
                </div>

                <!-- Card Navigasi Google Maps & Patokan Dusun -->
                <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-orange-600 text-xl">map</span>
                        <h3 class="text-base font-extrabold text-stone-900">Rute & Panduan Lokasi</h3>
                    </div>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        {{ $homestay->alamat }}
                    </p>
                    <div class="mt-3 rounded-2xl bg-stone-50 border border-stone-200/80 p-3 text-xs text-stone-600 space-y-1.5">
                        <div class="flex items-start gap-1.5">
                            <span class="material-symbols-outlined text-emerald-800 text-sm mt-0.5">signpost</span>
                            <span><strong>Patokan Dusun:</strong> Masuk ke gapura utama desa wisata, ikuti papan petunjuk warga menuju homestay.</span>
                        </div>
                        <div class="flex items-start gap-1.5">
                            <span class="material-symbols-outlined text-emerald-800 text-sm mt-0.5">local_parking</span>
                            <span><strong>Akses Kendaraan:</strong> Mobil dan motor dapat masuk hingga halaman homestay warga.</span>
                        </div>
                    </div>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($homestay->nama_homestay . ' ' . $homestay->alamat) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="mt-4 w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full border border-stone-300 bg-white hover:bg-stone-50 text-stone-900 text-xs font-bold shadow-xs transition-all">
                        <span class="material-symbols-outlined text-orange-600 text-base">directions</span>
                        <span>Buka Rute di Google Maps</span>
                        <span class="material-symbols-outlined text-xs text-stone-400">open_in_new</span>
                    </a>
                </div>

                <!-- Card Kearifan Lokal & Tata Krama Desa (Desa Wisata Etiquette) -->
                <div class="rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50/60 to-white p-6 shadow-xs">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-emerald-800 text-xl">volunteer_activism</span>
                        <h3 class="text-base font-extrabold text-stone-900">Kearifan Lokal Desa</h3>
                    </div>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        Sebagai tamu kehormatan di desa, kami mengedepankan etika saling menghargai bersama warga lokal:
                    </p>
                    <ul class="mt-3 space-y-2 text-xs text-stone-700">
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-emerald-700 text-sm shrink-0 mt-0.5">handshake</span>
                            <span><strong>Keramahan Warga:</strong> Bertegur sapa salam santun saat bertemu tetangga dusun.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-emerald-700 text-sm shrink-0 mt-0.5">bedtime</span>
                            <span><strong>Jam Tenang Malam:</strong> Menjaga ketenangan dusun di atas pukul 22.00 WIB.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-emerald-700 text-sm shrink-0 mt-0.5">checkroom</span>
                            <span><strong>Busana Sopan:</strong> Menghormati norma berpakaian santun saat berkeliling area desa.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-emerald-700 text-sm shrink-0 mt-0.5">delete_outline</span>
                            <span><strong>Kelestarian Lingkungan:</strong> Tidak membuang sampah sembarangan dan menjaga kemurnian alam.</span>
                        </li>
                    </ul>
                </div>

            </aside>
        </div>
    </section>
@endsection
