@extends('layouts.app')

@section('title', 'Beranda')
@section('meta_description', 'Temukan homestay warga desa wisata, pesan kamar, dan tambahkan paket wisata lokal dalam satu perjalanan.')

@section('content')
    <section class="relative overflow-hidden bg-forest-950 text-sand-50">
        <div class="absolute inset-0 bg-grain opacity-50" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-20 top-10 h-80 w-80 rounded-full bg-clay-500/20 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-24 bottom-0 h-96 w-96 rounded-full bg-forest-500/20 blur-3xl" aria-hidden="true"></div>

        <div class="relative mx-auto grid max-w-7xl gap-12 px-4 pb-20 pt-14 sm:px-6 lg:grid-cols-[1.08fr_0.92fr] lg:items-center lg:px-8 lg:pb-28 lg:pt-20">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold text-sand-100 backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-sand-400" aria-hidden="true"></span>
                    Homestay warga, pengalaman yang nyata
                </span>

                <h1 class="mt-6 max-w-2xl font-display text-4xl font-semibold leading-[1.08] tracking-tight sm:text-5xl lg:text-6xl">
                    Menginap di rumah warga,
                    <span class="text-sand-300">merasakan desa wisata.</span>
                </h1>

                <p class="mt-6 max-w-xl text-base leading-relaxed text-sand-100/80 sm:text-lg">
                    Dari sawah di Bali sampai kebun kopi di Jawa Barat. Pesan homestay yang dikelola langsung
                    oleh warga, lengkap dengan paket wisata dan cerita yang tidak ada di buku panduan.
                </p>

                <form method="GET" action="{{ route('homestay.index') }}" class="mt-8 rounded-3xl bg-white p-3 shadow-lift sm:flex sm:items-end sm:gap-2">
                    <div class="flex-1 px-2 py-2">
                        <label for="hero-search" class="text-[10px] font-bold uppercase tracking-[0.18em] text-stone-400">Mau menginap di mana?</label>
                        <input
                            id="hero-search"
                            type="text"
                            name="q"
                            placeholder="Cari nama homestay..."
                            class="mt-1 w-full border-0 bg-transparent p-0 text-sm font-semibold text-stone-800 placeholder:font-normal placeholder:text-stone-400 focus:ring-0"
                        >
                    </div>
                    <div class="border-t border-sand-200 px-2 py-2 sm:w-40 sm:border-l sm:border-t-0">
                        <label for="hero-capacity" class="text-[10px] font-bold uppercase tracking-[0.18em] text-stone-400">Jumlah tamu</label>
                        <select id="hero-capacity" name="kapasitas" class="mt-1 w-full border-0 bg-transparent p-0 text-sm font-semibold text-stone-800 focus:ring-0">
                            <option value="">Semua</option>
                            <option value="1">1 tamu</option>
                            <option value="2">2 tamu</option>
                            <option value="3">3 tamu</option>
                            <option value="4">4 tamu</option>
                        </select>
                    </div>
                    <x-button type="submit" size="lg" class="mt-2 w-full sm:mt-0 sm:w-auto">Cari Homestay</x-button>
                </form>

                <dl class="mt-8 grid max-w-lg grid-cols-3 gap-4 border-t border-white/10 pt-6">
                    <div>
                        <dt class="text-xs text-sand-200/70">Homestay</dt>
                        <dd class="mt-1 font-display text-2xl font-semibold text-white">{{ $stats['homestay'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-sand-200/70">Kamar siap inap</dt>
                        <dd class="mt-1 font-display text-2xl font-semibold text-white">{{ $stats['kamar'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-sand-200/70">Rating wisatawan</dt>
                        <dd class="mt-1 font-display text-2xl font-semibold text-white">
                            {{ $stats['rating'] > 0 ? number_format($stats['rating'], 1, ',', '.') : 'Baru' }}
                            <span class="font-sans text-xs font-normal text-sand-200/70">/5</span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="relative mx-auto w-full max-w-lg lg:max-w-none">
                <div class="relative aspect-[4/5] overflow-hidden rounded-[2.5rem] border border-white/10 bg-gradient-to-br from-sand-200 via-sand-300 to-forest-500 shadow-lift">
                    <svg viewBox="0 0 500 620" class="h-full w-full" role="img" aria-label="Ilustrasi rumah warga di tengah sawah dan perbukitan desa">
                        <defs>
                            <linearGradient id="sky" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#f6e8c8" />
                                <stop offset="55%" stop-color="#e8d3a9" />
                                <stop offset="100%" stop-color="#77a46e" />
                            </linearGradient>
                            <linearGradient id="hill" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#416d3a" />
                                <stop offset="100%" stop-color="#263b23" />
                            </linearGradient>
                        </defs>
                        <rect width="500" height="620" fill="url(#sky)" />
                        <circle cx="365" cy="135" r="58" fill="#f2e6ce" opacity="0.9" />
                        <path d="M0 330c75-45 130-20 190-55 60-34 110-15 175 10 50 20 95 10 135-8v343H0Z" fill="#55884c" />
                        <path d="M0 395c90-35 145-8 215-35 78-30 140-5 285 28v232H0Z" fill="url(#hill)" />
                        <path d="M0 465c120-30 185 12 270-8 85-19 160-2 230 16v147H0Z" fill="#a26f3a" opacity="0.55" />
                        <g transform="translate(120 300)">
                            <path d="M0 60 90 0l90 60v100H0Z" fill="#faf4e8" />
                            <path d="M-12 62 90-12l102 74" fill="none" stroke="#703022" stroke-width="14" stroke-linecap="round" />
                            <rect x="28" y="92" width="38" height="68" rx="4" fill="#835a34" />
                            <rect x="92" y="78" width="42" height="36" rx="4" fill="#35572f" />
                            <rect x="148" y="92" width="26" height="68" rx="4" fill="#835a34" />
                        </g>
                        <g fill="#f2e6ce" opacity="0.9">
                            <circle cx="80" cy="475" r="4" />
                            <circle cx="110" cy="500" r="3" />
                            <circle cx="60" cy="520" r="3.5" />
                            <circle cx="420" cy="470" r="4" />
                            <circle cx="450" cy="505" r="3" />
                        </g>
                        <path d="M40 590c70-40 120-30 180-65 60-34 135-30 240 5" fill="none" stroke="#f2e6ce" stroke-width="5" stroke-linecap="round" stroke-dasharray="2 16" opacity="0.8" />
                    </svg>

                    <div class="absolute bottom-5 left-5 right-5 rounded-3xl border border-white/20 bg-forest-950/70 p-4 text-white backdrop-blur-md">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-sand-300">Sedang ramai dikunjungi</p>
                        <p class="mt-1 font-display text-lg font-semibold">Penglipuran, Bangli</p>
                        <p class="mt-1 text-xs text-sand-100/70">Desa adat dengan rumah kayu dan jalan batu yang bersih.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <x-section-title
            eyebrow="Kenapa HomeStay Go"
            title="Perjalanan yang memberi kembali"
            description="Setiap pemesanan langsung mendukung warga desa wisata, bukan perusahaan besar di kota."
        />

        <div class="mt-9 grid gap-4 md:grid-cols-3">
            <div class="rounded-3xl border border-sand-200 bg-white p-6 shadow-soft">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-forest-100 text-forest-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75 12 3l8.25 6.75v9a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-9Z" />
                        <path stroke-linecap="round" d="M9 20.25v-6h6v6" />
                    </svg>
                </span>
                <h3 class="mt-5 font-display text-xl font-semibold text-forest-950">Dikelola warga lokal</h3>
                <p class="mt-2 text-sm leading-relaxed text-stone-600">Homestay dimiliki dan dirawat langsung oleh keluarga di desa wisata. Anda datang sebagai tamu, pulang sebagai keluarga.</p>
            </div>

            <div class="rounded-3xl border border-sand-200 bg-white p-6 shadow-soft">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-clay-100 text-clay-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m4.5-14.25c0-1.24-1.68-2.25-3.75-2.25s-3.75 1.01-3.75 2.25S10.68 9 12 9s4.5 1.01 4.5 2.25S14.82 13.5 12 13.5s-4.5 1.01-4.5 2.25S9.18 18 12 18s4.5-1.01 4.5-2.25" />
                    </svg>
                </span>
                <h3 class="mt-5 font-display text-xl font-semibold text-forest-950">Harga jujur, tanpa kejutan</h3>
                <p class="mt-2 text-sm leading-relaxed text-stone-600">Total menginap dan paket wisata dihitung transparan sejak awal. Pembayaran diverifikasi admin desa sebelum diproses.</p>
            </div>

            <div class="rounded-3xl border border-sand-200 bg-white p-6 shadow-soft">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sand-100 text-sand-800">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                </span>
                <h3 class="mt-5 font-display text-xl font-semibold text-forest-950">Paket wisata desa</h3>
                <p class="mt-2 text-sm leading-relaxed text-stone-600">Trekking sawah, kelas memasak, menenun, sampai keliling desa adat. Semua dipandu warga yang mengenal tempatnya.</p>
            </div>
        </div>
    </section>

    <section class="bg-sand-100/70 py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-title
                eyebrow="Pilihan desa wisata"
                title="Homestay yang sedang disiapkan untuk Anda"
                description="Semua homestay di bawah ini sudah melewati verifikasi pengelola desa wisata."
            >
                <x-button :href="route('homestay.index')" variant="secondary">Lihat semua homestay</x-button>
            </x-section-title>

            @if ($featured->isEmpty())
                <div class="mt-8">
                    <x-empty-state title="Belum ada homestay terverifikasi" description="Pengelola desa wisata sedang menyiapkan homestay pertama." />
                </div>
            @else
                <div class="mt-9 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($featured as $homestay)
                        <x-homestay-card :homestay="$homestay" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-clay-600">Cara kerja</p>
                <h2 class="mt-2 font-display text-3xl font-semibold leading-tight text-forest-950 sm:text-4xl">Tiga langkah menuju desa.</h2>
                <p class="mt-4 text-sm leading-relaxed text-stone-600">Tidak perlu aplikasi tambahan. Semua bisa dilakukan dari ponsel Anda.</p>
                <div class="mt-8 rounded-3xl bg-forest-900 p-6 text-sand-50">
                    <p class="font-display text-xl font-semibold leading-snug">“Desa wisata terbaik bukan yang paling ramai, tapi yang membuat kita ingin kembali.”</p>
                    <p class="mt-3 text-xs text-sand-200/70">Catatan tim HomeStay Go</p>
                </div>
            </div>

            <ol class="space-y-4">
                @foreach ([
                    ['01', 'Cari dan bandingkan', 'Gunakan filter harga, kapasitas, dan fasilitas. Cek ketersediaan kamar untuk tanggal perjalanan Anda.'],
                    ['02', 'Pesan dan bayar', 'Isi tanggal, jumlah tamu, dan paket wisata. Unggah bukti transfer, lalu admin desa memverifikasinya.'],
                    ['03', 'Tinggal dan bercerita', 'Setelah pemilik mengonfirmasi, Anda siap berangkat. Setelah selesai, bagikan ulasan untuk travelers lain.'],
                ] as [$number, $title, $description])
                    <li class="flex gap-5 rounded-3xl border border-sand-200 bg-white p-5 shadow-soft sm:p-6">
                        <span class="font-display text-2xl font-semibold text-clay-500">{{ $number }}</span>
                        <div>
                            <h3 class="font-display text-xl font-semibold text-forest-950">{{ $title }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-stone-600">{{ $description }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    @if ($testimonials->isNotEmpty())
        <section class="bg-forest-950 py-16 text-sand-50 lg:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-section-title
                    eyebrow="Cerita wisatawan"
                    title="Mereka sudah pulang membawa cerita"
                    class="[&_p]:text-sand-300 [&_h2]:text-white"
                />

                <div class="mt-9 grid gap-4 md:grid-cols-3">
                    @foreach ($testimonials as $review)
                        <figure class="flex h-full flex-col rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                            <x-rating-stars :rating="$review->rating" />
                            <blockquote class="mt-4 flex-1 text-sm leading-relaxed text-sand-100/85">
                                “{{ \Illuminate\Support\Str::limit($review->komentar, 180) }}”
                            </blockquote>
                            <figcaption class="mt-5 border-t border-white/10 pt-4">
                                <p class="text-sm font-bold text-white">{{ $review->booking->wisatawan->nama_wisatawan }}</p>
                                <p class="mt-1 text-xs text-sand-300">{{ $review->booking->kamar->homestay->nama_homestay }}</p>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="relative overflow-hidden rounded-4xl bg-gradient-to-br from-clay-700 via-clay-600 to-sand-500 p-8 text-white shadow-lift sm:p-12">
            <div class="absolute inset-0 bg-grain opacity-40" aria-hidden="true"></div>
            <div class="relative grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-sand-100">Untuk pemilik homestay</p>
                    <h2 class="mt-2 max-w-2xl font-display text-3xl font-semibold leading-tight sm:text-4xl">Punya kamar kosong? Jadikan pintu desa wisata.</h2>
                    <p class="mt-3 max-w-xl text-sm leading-relaxed text-white/80">Daftarkan homestay, atur kamar dan paket wisata, lalu terima tamu dari seluruh Indonesia. Pendaftaran gratis, verifikasi dibantu admin desa.</p>
                </div>
                <div class="flex flex-wrap gap-3 lg:justify-end">
                    <x-button :href="route('register')" size="lg" class="!bg-white !text-clay-800 hover:!bg-sand-100">Daftar sebagai pemilik</x-button>
                </div>
            </div>
        </div>
    </section>
@endsection
