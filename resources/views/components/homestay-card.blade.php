@props(['homestay'])

@php
    $gradients = [
        'from-forest-700 via-forest-500 to-sand-400',
        'from-clay-700 via-clay-500 to-sand-300',
        'from-forest-900 via-forest-700 to-clay-500',
        'from-sand-600 via-sand-400 to-forest-300',
    ];
    $gradient = $gradients[$homestay->id_homestay % count($gradients)];
    $hargaMin = $homestay->harga_min ?? $homestay->kamar->min('harga_per_malam');
    $rating = $homestay->ulasan_avg_rating ?? null;
    $jumlahUlasan = $homestay->ulasan_count ?? 0;
@endphp

<article class="group flex h-full flex-col overflow-hidden rounded-3xl border border-sand-200 bg-white shadow-soft transition duration-300 hover:-translate-y-1 hover:shadow-lift">
    <div class="relative h-40 overflow-hidden bg-gradient-to-br {{ $gradient }}">
        <div class="absolute inset-0 bg-grain opacity-70" aria-hidden="true"></div>
        <svg class="absolute -bottom-8 -right-4 h-36 w-36 text-white/20" viewBox="0 0 100 100" fill="currentColor" aria-hidden="true">
            <path d="M50 92c18-3 31-15 36-32-17 4-31 14-36 32Zm0 0C32 89 19 77 14 60c17 4 31 14 36 32Zm0-10C33 74 25 58 30 39c11 12 19 25 20 43Zm0 0c17-8 25-24 20-43-11 12-19 25-20 43Z" />
        </svg>
        <div class="absolute left-4 top-4 flex flex-wrap gap-2">
            <span class="rounded-full bg-white/90 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-forest-900 backdrop-blur">
                Desa Wisata
            </span>
        </div>
        @if ($rating)
            <div class="absolute bottom-4 right-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-bold text-forest-900 shadow-sm">
                ★ {{ number_format((float) $rating, 1, ',', '.') }}
                <span class="font-medium text-stone-500">({{ $jumlahUlasan }})</span>
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        <h3 class="font-display text-xl font-semibold leading-snug text-forest-950">
            <a href="{{ route('homestay.show', $homestay) }}" class="focus-ring rounded-lg transition group-hover:text-forest-700">
                {{ $homestay->nama_homestay }}
            </a>
        </h3>
        <p class="mt-1.5 flex items-start gap-1.5 text-xs leading-relaxed text-stone-500">
            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.25 7-11a7 7 0 1 0-14 0c0 5.75 7 11 7 11Z" />
                <circle cx="12" cy="10" r="2.2" />
            </svg>
            {{ \Illuminate\Support\Str::limit($homestay->alamat, 70) }}
        </p>

        @if ($homestay->fasilitas->isNotEmpty())
            <div class="mt-3 flex flex-wrap gap-1.5">
                @foreach ($homestay->fasilitas->take(3) as $fasilitas)
                    <span class="rounded-full bg-sand-100 px-2.5 py-1 text-[10px] font-semibold text-sand-800">{{ $fasilitas->nama_fasilitas }}</span>
                @endforeach
                @if ($homestay->fasilitas->count() > 3)
                    <span class="rounded-full bg-sand-100 px-2.5 py-1 text-[10px] font-semibold text-sand-800">+{{ $homestay->fasilitas->count() - 3 }}</span>
                @endif
            </div>
        @endif

        <div class="mt-auto flex items-end justify-between gap-3 pt-5">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">Mulai dari</p>
                <p class="font-display text-lg font-semibold text-forest-900">
                    {{ $hargaMin ? rupiah($hargaMin) : 'Belum tersedia' }}
                    @if ($hargaMin)<span class="font-sans text-xs font-normal text-stone-500">/malam</span>@endif
                </p>
            </div>
            <a href="{{ route('homestay.show', $homestay) }}" class="focus-ring inline-flex items-center gap-1 rounded-full bg-forest-50 px-3.5 py-2 text-xs font-bold text-forest-800 transition hover:bg-forest-700 hover:text-white">
                Lihat
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</article>
