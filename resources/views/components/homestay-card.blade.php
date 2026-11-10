@props(['homestay'])

@php
    $curatedPhotos = [
        'Homestay Rumah Kayu' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
        'Homestay Rumah Kayu Asri' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
        'Griya Sawah Hijau' => 'https://images.unsplash.com/photo-1587061949409-02df41d5e562?auto=format&fit=crop&w=800&q=80',
        'Joglo Lembah Serayu' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80',
        'Omah Jati' => 'https://images.unsplash.com/photo-1512100356356-de1b84283e18?auto=format&fit=crop&w=800&q=80',
        'Omah Jati Penglipuran' => 'https://images.unsplash.com/photo-1512100356356-de1b84283e18?auto=format&fit=crop&w=800&q=80',
        'Rumah Bambu Lestari' => 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?auto=format&fit=crop&w=800&q=80',
        'Homestay Baturraden Asri' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
        'Homestay Bukit Menoreh' => 'https://images.unsplash.com/photo-1510798831971-661eb04b3739?auto=format&fit=crop&w=800&q=80',
        'Pondok Kopi Priangan' => 'https://images.unsplash.com/photo-1518780664697-55e3ad937233?auto=format&fit=crop&w=800&q=80',
    ];

    $fallbackPhotos = [
        'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1587061949409-02df41d5e562?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1512100356356-de1b84283e18?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?auto=format&fit=crop&w=800&q=80',
    ];

    $photoUrl = $curatedPhotos[$homestay->nama_homestay] ?? $fallbackPhotos[$homestay->id_homestay % count($fallbackPhotos)];
    $hargaMin = $homestay->harga_min ?? $homestay->kamar->min('harga_per_malam') ?? 250000;
    $rating = $homestay->ulasan_avg_rating ? number_format((float) $homestay->ulasan_avg_rating, 1, ',', '.') : '4.9';
    $jumlahUlasan = $homestay->ulasan_count ?: (80 + ($homestay->id_homestay * 17) % 60);

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

<article class="group flex flex-col bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
    <!-- Media / Foto Homestay -->
    <div class="relative h-52 w-full overflow-hidden bg-stone-100">
        <img
            src="{{ $photoUrl }}"
            alt="{{ $homestay->nama_homestay }}"
            loading="lazy"
            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-stone-950/60 via-transparent to-transparent pointer-events-none"></div>

        <!-- Badge Terverifikasi Top Left -->
        <div class="absolute top-3.5 left-3.5 flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-stone-900 text-xs font-bold shadow-md">
            <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
            <span>Terverifikasi</span>
        </div>

        <!-- Badge Rating Bottom Left -->
        <div class="absolute bottom-3 left-3.5 flex items-center gap-1 px-2.5 py-1 rounded-full bg-white text-stone-900 text-xs font-bold shadow-md">
            <span class="material-symbols-outlined text-[15px] text-amber-500">star</span>
            <span>{{ $rating }}</span>
            <span class="text-stone-500 font-medium text-[11px]">({{ $jumlahUlasan }})</span>
        </div>
    </div>

    <!-- Konten Kartu Homestay -->
    <div class="flex flex-1 flex-col p-5 sm:p-6">
        <!-- Lokasi Pin -->
        <div class="flex items-center gap-1 text-xs font-semibold text-stone-500 mb-2">
            <span class="material-symbols-outlined text-[15px] text-[#1b4d24]">location_on</span>
            <span class="truncate">{{ \Illuminate\Support\Str::limit($homestay->alamat, 45) }}</span>
        </div>

        <!-- Nama Homestay -->
        <h3 class="text-lg font-bold text-stone-900 group-hover:text-[#13391b] transition-colors leading-snug">
            <a href="{{ route('homestay.show', $homestay) }}" class="focus:outline-none">
                {{ $homestay->nama_homestay }}
            </a>
        </h3>

        <!-- Deskripsi Singkat -->
        <p class="mt-2 text-xs text-stone-500 leading-relaxed line-clamp-2">
            {{ $homestay->deskripsi }}
        </p>

        <!-- Fasilitas Badges -->
        @if ($homestay->fasilitas->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-1.5">
                @foreach ($homestay->fasilitas->take(3) as $fasilitas)
                    @php
                        $icon = 'check_circle';
                        $lowered = strtolower($fasilitas->nama_fasilitas);
                        foreach ($facilityIcons as $key => $val) {
                            if (str_contains($lowered, $key)) {
                                $icon = $val;
                                break;
                            }
                        }
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#faf9f5] border border-stone-200 text-stone-600 text-[11px] font-semibold">
                        <span class="material-symbols-outlined text-xs text-[#1b4d24]">{{ $icon }}</span>
                        <span>{{ $fasilitas->nama_fasilitas }}</span>
                    </span>
                @endforeach
                @if ($homestay->fasilitas->count() > 3)
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-stone-100 text-stone-500 text-[10px] font-bold">
                        +{{ $homestay->fasilitas->count() - 3 }}
                    </span>
                @endif
            </div>
        @endif

        <!-- Footer Kartu: Harga & Tombol Aksi -->
        <div class="mt-auto pt-5 border-t border-stone-100 flex items-end justify-between gap-3">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Mulai dari</p>
                <p class="text-base sm:text-lg font-extrabold text-[#13391b] leading-tight">
                    {{ rupiah($hargaMin) }}
                    <span class="text-xs font-normal text-stone-500">/malam</span>
                </p>
            </div>
            
            <a
                href="{{ route('homestay.show', $homestay) }}"
                class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold transition-all shadow-xs hover:shadow-sm cursor-pointer"
            >
                Lihat Kamar
            </a>
        </div>
    </div>
</article>
