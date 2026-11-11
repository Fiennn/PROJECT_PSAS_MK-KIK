@extends('layouts.app')

@section('title', 'Beranda')
@section('meta_description', 'HomeStay Go — Reservasi homestay resmi desa wisata binaan Pokdarwis Nusantara. Bebas double booking, pembayaran aman, 100% mendukung ekonomi warga lokal.')

@section('content')
  <!-- HERO SECTION (FULL SCREEN LAPTOP VIEWPORT DENGAN BACKGROUND SLIDESHOW SINEMATIK) -->
  <section class="relative h-[calc(100vh-80px)] min-h-[580px] max-h-[900px] flex items-center justify-center bg-stone-950 overflow-hidden">
    
    <!-- Background Slideshow (Clean Crossfade) -->
    <div class="absolute inset-0 z-0">
      <img id="slide-0" src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=2000&q=85" alt="Lanskap Alam Pegunungan Banyumas" class="hero-bg active" />
      <img id="slide-1" src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=2000&q=85" alt="Homestay Tradisional Kayu" class="hero-bg" />
      <img id="slide-2" src="https://images.unsplash.com/photo-1587061949409-02df41d5e562?auto=format&fit=crop&w=2000&q=85" alt="Perbukitan Sawah Hijau" class="hero-bg" />
      <img id="slide-3" src="https://images.unsplash.com/photo-1512100356356-de1b84283e18?auto=format&fit=crop&w=2000&q=85" alt="Desa Adat Penglipuran" class="hero-bg" />
    </div>

    <!-- Clean Dark Scrim Overlay for Contrast -->
    <div class="absolute inset-0 z-10 bg-gradient-to-t from-stone-950/90 via-stone-950/40 to-stone-950/60 pointer-events-none"></div>

    <!-- Center Content -->
    <div class="relative z-20 max-w-5xl mx-auto px-6 text-center flex flex-col items-center">
      
      <!-- Badge Platform Resmi -->
      <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-stone-900 text-xs font-bold shadow-lg mb-6">
        <span class="w-2 h-2 rounded-full bg-[#1b4d24]"></span>
        <span>Platform Resmi Desa Wisata • Binaan Pokdarwis Nusantara</span>
      </div>

      <!-- Headline Sesuai Desain Asli -->
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.15] max-w-4xl drop-shadow-md">
        Temukan Kedamaian di <br/>
        <span class="text-emerald-400">Desa</span> <span class="text-white">Wisata</span> <span class="text-orange-500">Indonesia</span>
      </h1>

      <!-- Deskripsi Natural Sesuai Referensi, Bebas Layout Shift -->
      <p class="mt-4 text-base sm:text-lg text-stone-200 font-normal leading-relaxed max-w-[760px] mx-auto drop-shadow">
        Reservasi homestay langsung dari warga lokal. Sistem kalender otomatis bebas <br class="hidden sm:inline" />
        <span class="inline-flex items-center justify-center align-middle mx-1.5 px-3.5 rounded-lg bg-white/20 font-bold text-white shadow-sm min-w-[165px] h-9 overflow-hidden"><span id="word-rotator-text" class="inline-flex items-center justify-center leading-none text-center whitespace-nowrap h-full w-full">double booking</span></span>, pembayaran aman, dan 100% mendukung ekonomi desa.
      </p>

      <!-- FLOATING SEARCH BAR DENGAN ICON VECTOR RAPI -->
      <div class="mt-8 w-full max-w-4xl bg-white rounded-2xl shadow-2xl p-2 sm:p-2.5 text-left border border-stone-200">
        <form method="GET" action="{{ route('homestay.index') }}" class="grid grid-cols-1 md:grid-cols-[1.2fr_1fr_1fr_auto] gap-2 items-center">
          
          <!-- Destinasi (Dengan Icon location_on) -->
          <div class="px-4 py-2 rounded-xl hover:bg-stone-50 transition-colors">
            <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider">Destinasi / Kota</label>
            <div class="flex items-center gap-2 mt-1">
              <span class="material-symbols-outlined text-[#1b4d24] text-xl">location_on</span>
              <select name="q" id="select-kota" class="w-full bg-transparent font-bold text-stone-900 text-sm focus:outline-none cursor-pointer">
                <option value="">Semua Destinasi</option>
                <option value="Banyumas">Banyumas (Baturraden)</option>
                <option value="Bali">Bali (Penglipuran &amp; Jatiluwih)</option>
                <option value="Yogyakarta">Yogyakarta (Nglanggeran)</option>
                <option value="Dieng">Dieng, Wonosobo</option>
                <option value="Bandung">Bandung (Pangalengan)</option>
                <option value="Lombok">Lombok (Desa Sade &amp; Sembalun)</option>
              </select>
            </div>
          </div>

          <!-- Jadwal Menginap Interaktif (Bisa Di-set Tanggalnya) -->
          <div class="relative px-4 py-2 rounded-xl hover:bg-stone-50 transition-colors border-t md:border-t-0 md:border-l border-stone-200 cursor-pointer group" id="date-picker-box">
            <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider cursor-pointer">Jadwal Menginap</label>
            <div class="flex items-center justify-between gap-2 mt-1">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#1b4d24] text-xl group-hover:scale-110 transition-transform">calendar_month</span>
                <span id="date-display-text" class="text-sm font-bold text-stone-900">14 Nov — 16 Nov</span>
              </div>
              <span class="material-symbols-outlined text-stone-400 text-base">expand_more</span>
            </div>

            <!-- Popover Kalender Tanggal Interaktif -->
            <div id="date-picker-popover" class="hidden absolute top-[calc(100%+12px)] left-0 md:-left-8 bg-white rounded-2xl shadow-2xl border border-stone-200 p-5 z-50 w-[320px] sm:w-[350px] transition-all text-stone-900 cursor-default" onclick="event.stopPropagation()">
              <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-[#1b4d24] text-lg">event_available</span>
                  <span class="text-xs font-bold text-stone-900">Atur Jadwal Menginap</span>
                </div>
                <button type="button" id="close-date-popover" class="text-stone-400 hover:text-stone-700 w-6 h-6 flex items-center justify-center rounded-full hover:bg-stone-100 text-xs">✕</button>
              </div>

              <!-- Input Tanggal Check-in & Check-out -->
              <div class="grid grid-cols-2 gap-3 mt-4">
                <div class="bg-stone-50 p-2.5 rounded-xl border border-stone-200 focus-within:border-[#1b4d24] focus-within:bg-white transition-colors">
                  <label for="input-checkin" class="block text-[10px] font-bold text-stone-500 uppercase tracking-wider mb-1">Check-in</label>
                  <input type="date" name="checkin" id="input-checkin" value="{{ request('checkin', now()->addDays(2)->format('Y-m-d')) }}" class="w-full bg-transparent text-xs font-bold text-stone-900 focus:outline-none cursor-pointer" />
                </div>
                <div class="bg-stone-50 p-2.5 rounded-xl border border-stone-200 focus-within:border-[#1b4d24] focus-within:bg-white transition-colors">
                  <label for="input-checkout" class="block text-[10px] font-bold text-stone-500 uppercase tracking-wider mb-1">Check-out</label>
                  <input type="date" name="checkout" id="input-checkout" value="{{ request('checkout', now()->addDays(4)->format('Y-m-d')) }}" class="w-full bg-transparent text-xs font-bold text-stone-900 focus:outline-none cursor-pointer" />
                </div>
              </div>

              <!-- Pilihan Cepat (Quick Presets) -->
              <div class="mt-3.5">
                <span class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider mb-1.5">Pilihan Cepat:</span>
                <div class="flex flex-wrap gap-1.5 text-[11px]">
                  <button type="button" onclick="setPresetDate(2)" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-emerald-50 hover:text-[#1b4d24] font-semibold text-stone-600 transition-colors">2 Malam (Weekend)</button>
                  <button type="button" onclick="setPresetDate(3)" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-emerald-50 hover:text-[#1b4d24] font-semibold text-stone-600 transition-colors">3 Hari 2 Malam</button>
                  <button type="button" onclick="setPresetDate(7)" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-emerald-50 hover:text-[#1b4d24] font-semibold text-stone-600 transition-colors">1 Minggu</button>
                </div>
              </div>

              <!-- Footer Durasi & Tombol Terapkan -->
              <div class="mt-4 pt-3.5 border-t border-stone-100 flex items-center justify-between">
                <div>
                  <span class="block text-[10px] text-stone-400 uppercase font-semibold">Total Durasi</span>
                  <span id="durasi-label" class="text-xs font-extrabold text-[#1b4d24]">2 Malam</span>
                </div>
                <button type="button" id="btn-apply-date" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-sm active:scale-95">
                  Terapkan Jadwal
                </button>
              </div>
            </div>
          </div>

          <!-- Tamu (Dengan Icon group) -->
          <div class="px-4 py-2 rounded-xl hover:bg-stone-50 transition-colors border-t md:border-t-0 md:border-l border-stone-200">
            <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-wider">Kapasitas</label>
            <div class="flex items-center gap-2 mt-1">
              <span class="material-symbols-outlined text-[#1b4d24] text-xl">group</span>
              <select name="kapasitas" class="w-full bg-transparent font-bold text-stone-900 text-sm focus:outline-none cursor-pointer">
                <option value="">Semua Kapasitas</option>
                <option value="2">2 Tamu, 1 Kamar</option>
                <option value="4">4 Tamu (Keluarga)</option>
                <option value="1">1 Tamu (Solo)</option>
              </select>
            </div>
          </div>

          <!-- Tombol Cari (Sesuai Gambar Referensi Hero) -->
          <div class="p-1">
            <button type="submit" class="w-full md:w-auto bg-[#ea580c] hover:bg-[#c2410c] text-white text-sm font-bold px-7 py-3.5 rounded-xl transition-all shadow-md flex items-center justify-center gap-2 active:scale-95 cursor-pointer">
              <span class="material-symbols-outlined text-lg">search</span>
              <span>Cari Homestay</span>
            </button>
          </div>

        </form>
      </div>

      <!-- Kota Populer Quick Chips -->
      <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs text-stone-200">
        <span class="text-stone-300 font-semibold">Kota Populer:</span>
        <button type="button" onclick="selectQuickCity('Banyumas')" class="bg-white/15 hover:bg-white/25 px-3.5 py-1.5 rounded-full text-white transition-colors cursor-pointer active:scale-95">Banyumas / Purwokerto</button>
        <button type="button" onclick="selectQuickCity('Bali')" class="bg-white/15 hover:bg-white/25 px-3.5 py-1.5 rounded-full text-white transition-colors cursor-pointer active:scale-95">Bali (Penglipuran)</button>
        <button type="button" onclick="selectQuickCity('Yogyakarta')" class="bg-white/15 hover:bg-white/25 px-3.5 py-1.5 rounded-full text-white transition-colors cursor-pointer active:scale-95">Yogyakarta</button>
        <button type="button" onclick="selectQuickCity('Dieng')" class="bg-white/15 hover:bg-white/25 px-3.5 py-1.5 rounded-full text-white transition-colors cursor-pointer active:scale-95">Dieng Wonosobo</button>
      </div>

      <!-- Slide Indicator Dots -->
      <div class="mt-6 flex items-center gap-2">
        <button onclick="setSlide(0)" class="slide-indicator w-6 h-1.5 rounded-full bg-white transition-all cursor-pointer" aria-label="Slide 1"></button>
        <button onclick="setSlide(1)" class="slide-indicator w-2 h-1.5 rounded-full bg-white/40 transition-all cursor-pointer" aria-label="Slide 2"></button>
        <button onclick="setSlide(2)" class="slide-indicator w-2 h-1.5 rounded-full bg-white/40 transition-all cursor-pointer" aria-label="Slide 3"></button>
        <button onclick="setSlide(3)" class="slide-indicator w-2 h-1.5 rounded-full bg-white/40 transition-all cursor-pointer" aria-label="Slide 4"></button>
      </div>

    </div>
  </section>

  <!-- TEXT TICKER (DENGAN ICON VECTOR RESMI MATERIAL SYMBOLS WARNA HIJAU LOGO #1b4d24) -->
  <div class="relative overflow-hidden bg-[#ea580c] text-white border-y border-orange-700/80 py-3.5 text-xs font-bold tracking-wider uppercase select-none shadow-inner">
    <div class="flex w-[200%] animate-marquee whitespace-nowrap">
      
      <!-- Sequence 1 -->
      <div class="flex items-center gap-8 shrink-0 px-6">
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">verified</span>
          <span>100% Bebas Double Booking</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">star</span>
          <span>Terverifikasi Pokdarwis</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">handshake</span>
          <span>Pemberdayaan Ekonomi Warga Lokal</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">eco</span>
          <span>Eco-Hospitality Nusantara</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">qr_code_2</span>
          <span>E-Tiket &amp; QR Check-in Instan</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">location_on</span>
          <span>Desa Wisata Penglipuran Bali</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">reviews</span>
          <span>Ratusan Ulasan Wisatawan Asli</span>
        </span>
      </div>

      <!-- Sequence 2 -->
      <div class="flex items-center gap-8 shrink-0 px-6" aria-hidden="true">
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">verified</span>
          <span>100% Bebas Double Booking</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">star</span>
          <span>Terverifikasi Pokdarwis</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">handshake</span>
          <span>Pemberdayaan Ekonomi Warga Lokal</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">eco</span>
          <span>Eco-Hospitality Nusantara</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">qr_code_2</span>
          <span>E-Tiket &amp; QR Check-in Instan</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">location_on</span>
          <span>Desa Wisata Penglipuran Bali</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[#1b4d24] text-[18px]">reviews</span>
          <span>Ratusan Ulasan Wisatawan Asli</span>
        </span>
      </div>

    </div>
  </div>

  <!-- KATALOG HOMESTAY: 1 BARIS HORIZONTAL SCROLL / CAROUSEL DENGAN KATEGORI & NAVIGASI -->
  <section id="katalog" class="relative overflow-hidden py-20">
    <!-- Static Parallax Background Image Layer (Desa & Terasering Sawah Asri) -->
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
      <div
        id="katalog-bg-img"
        class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-fixed opacity-0 transition-opacity duration-700 ease-out filter contrast-110 saturate-105"
        style="background-image: url('https://images.unsplash.com/photo-1587061949409-02df41d5e562?auto=format&fit=crop&w=2000&q=80');"
      ></div>
      <!-- Soft vignette & page background blend -->
      <div class="absolute inset-0 bg-gradient-to-b from-[#faf9f5]/90 via-[#faf9f5]/25 to-[#faf9f5]/90"></div>
    </div>

    <!-- Content Container -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
      
      <!-- Header & Category Brainstorming Info -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-100 text-[#ea580c] text-xs font-bold uppercase tracking-wider mb-2">
          <span class="material-symbols-outlined text-sm">explore</span>
          <span>Shortcut Pilihan Cepat Homestay</span>
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-stone-900 tracking-tight">Koleksi Homestay Desa Wisata</h2>
        <p class="text-stone-500 text-sm mt-1 max-w-xl">Geser ke samping untuk menjelajahi keasrian desa binaan Pokdarwis berdasarkan suasana favoritmu.</p>
      </div>

      <!-- Carousel Navigation Controls & Total Counter -->
      <div class="flex items-center gap-3">
        <span id="carousel-counter" class="text-xs font-bold text-stone-600 bg-stone-100 px-3.5 py-1.5 rounded-full border border-stone-200">
          12 Homestay Tersedia
        </span>
        <div class="flex items-center gap-2">
          <button type="button" id="btn-carousel-prev" onclick="scrollHomestayCarousel(-1)" class="w-10 h-10 rounded-full border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 flex items-center justify-center shadow-xs transition-all active:scale-95 hover:border-[#13391b] hover:text-[#13391b] cursor-pointer" aria-label="Geser Kiri">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
          </button>
          <button type="button" id="btn-carousel-next" onclick="scrollHomestayCarousel(1)" class="w-10 h-10 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white flex items-center justify-center shadow-xs transition-all active:scale-95 cursor-pointer hover:shadow-md" aria-label="Geser Kanan">
            <span class="material-symbols-outlined text-xl">arrow_forward</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Category Filter Tabs (Brainstorming Kategori Lengkap) -->
    <div class="flex items-center gap-2.5 overflow-x-auto pb-3 mb-6 no-scrollbar">
      <button type="button" onclick="filterHomestayCategory(event, 'all')" class="cat-pill active px-4 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap bg-[#13391b] text-white shadow-xs">
        Semua Suasana (12)
      </button>
      <button type="button" onclick="filterHomestayCategory(event, 'gunung')" class="cat-pill px-4 py-2 rounded-full text-xs font-semibold transition-all whitespace-nowrap bg-white border border-stone-200 text-stone-700 hover:border-[#13391b] hover:text-[#13391b] flex items-center gap-1.5">
        <span class="material-symbols-outlined text-emerald-600 text-base">landscape</span>
        <span>Lereng Gunung &amp; Pinus (3)</span>
      </button>
      <button type="button" onclick="filterHomestayCategory(event, 'sawah')" class="cat-pill px-4 py-2 rounded-full text-xs font-semibold transition-all whitespace-nowrap bg-white border border-stone-200 text-stone-700 hover:border-[#13391b] hover:text-[#13391b] flex items-center gap-1.5">
        <span class="material-symbols-outlined text-amber-600 text-base">grass</span>
        <span>Terasering Sawah Asri (3)</span>
      </button>
      <button type="button" onclick="filterHomestayCategory(event, 'adat')" class="cat-pill px-4 py-2 rounded-full text-xs font-semibold transition-all whitespace-nowrap bg-white border border-stone-200 text-stone-700 hover:border-[#13391b] hover:text-[#13391b] flex items-center gap-1.5">
        <span class="material-symbols-outlined text-orange-600 text-base">holiday_village</span>
        <span>Rumah Adat &amp; Budaya (3)</span>
      </button>
      <button type="button" onclick="filterHomestayCategory(event, 'kebun')" class="cat-pill px-4 py-2 rounded-full text-xs font-semibold transition-all whitespace-nowrap bg-white border border-stone-200 text-stone-700 hover:border-[#13391b] hover:text-[#13391b] flex items-center gap-1.5">
        <span class="material-symbols-outlined text-teal-600 text-base">eco</span>
        <span>Kebun Teh &amp; Lembah (3)</span>
      </button>
    </div>

    <!-- 1 Baris Horizontal Scroll Track (Smooth Snap Carousel) -->
    <div id="homestay-carousel" class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-6 pt-2 no-scrollbar -mx-2 px-2">
      
      @if(isset($featured) && $featured->isNotEmpty())
        @foreach($featured as $homestay)
          @php
            $photoMap = [
              'Homestay Rumah Kayu' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
              'Homestay Rumah Kayu Asri' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
              'Griya Sawah Hijau' => 'https://images.unsplash.com/photo-1587061949409-02df41d5e562?auto=format&fit=crop&w=800&q=80',
              'Omah Jati' => 'https://images.unsplash.com/photo-1512100356356-de1b84283e18?auto=format&fit=crop&w=800&q=80',
              'Rumah Bambu Lestari' => 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?auto=format&fit=crop&w=800&q=80',
              'Homestay Baturraden Asri' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
            ];
            $hPhoto = $photoMap[$homestay->nama_homestay] ?? 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80';
            $hPrice = $homestay->harga_min ?? $homestay->kamar->min('harga_per_malam') ?? 250000;
            $hRating = $homestay->ulasan_avg_rating ? number_format((float) $homestay->ulasan_avg_rating, 1, ',', '.') : '4.9';
            $hReviews = $homestay->ulasan_count ?: (80 + ($homestay->id_homestay * 13) % 50);
          @endphp
          <article data-category="gunung" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
            <div>
              <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
                <img src="{{ $hPhoto }}" alt="{{ $homestay->nama_homestay }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy"/>
                <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
                  <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
                  <span>Terverifikasi</span>
                </span>
                <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200 shadow-xs">
                  Desa Mitra
                </span>
                <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
                  <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
                  <span>{{ $hRating }}</span>
                  <span class="text-stone-400 font-normal">({{ $hReviews }})</span>
                </div>
              </div>

              <div class="p-5">
                <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
                  <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
                  <span class="truncate">{{ \Illuminate\Support\Str::limit($homestay->alamat, 38) }}</span>
                </div>
                <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors truncate">
                  <a href="{{ route('homestay.show', $homestay) }}">{{ $homestay->nama_homestay }}</a>
                </h3>
                <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
                  {{ $homestay->deskripsi }}
                </p>

                <div class="flex flex-wrap gap-1.5 mt-4">
                  @foreach($homestay->fasilitas->take(3) as $fas)
                    <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                      <span class="material-symbols-outlined text-[12px] text-[#1b4d24]">check_circle</span> {{ $fas->nama_fasilitas }}
                    </span>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
              <div>
                <span class="text-base font-extrabold text-[#13391b]">{{ rupiah($hPrice) }}</span>
                <span class="text-xs text-stone-500">/ malam</span>
              </div>
              <a href="{{ route('homestay.show', $homestay) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
                Lihat Kamar
              </a>
            </div>
          </article>
        @endforeach
      @endif

      <!-- Card 1: Baturraden Banyumas (Gunung) -->
      <article data-category="gunung" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1587061949409-02df41d5e562?auto=format&fit=crop&w=800&q=80" alt="Homestay Rumah Kayu Asri" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200 shadow-xs">
              Lereng Gunung
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.9</span>
              <span class="text-stone-400 font-normal">(128)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Baturraden, Banyumas</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Homestay Rumah Kayu Asri</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Rumah kayu tradisional di lereng Gunung Slamet. Menghadap sawah bertingkat dengan udara pegunungan yang sejuk.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">wifi</span> WiFi
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">restaurant</span> Sarapan
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">hot_tub</span> Air Panas
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 250.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Banyumas']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

      <!-- Card 2: Serayu Banyumas (Kebun) -->
      <article data-category="kebun" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80" alt="Joglo Lembah Serayu" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-teal-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-teal-200 shadow-xs">
              Lembah &amp; Sungai
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.8</span>
              <span class="text-stone-400 font-normal">(84)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Banyumas</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Joglo Lembah Serayu</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Arsitektur joglo jati asli dengan pekarangan asri. Berada dekat rute susur sungai dan curug alami.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">shower</span> Kamar Mandi Dalam
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">kitchen</span> Dapur Bersama
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 310.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Banyumas']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

      <!-- Card 3: Penglipuran Bali (Adat) -->
      <article data-category="adat" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?auto=format&fit=crop&w=800&q=80" alt="Omah Jati Penglipuran" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-orange-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-orange-200 shadow-xs">
              Desa Adat
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.95</span>
              <span class="text-stone-400 font-normal">(210)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Bangli, Bali</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Omah Jati Penglipuran</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Menginap di kawasan desa adat dengan tata ruang bersih dan tenang. Termasuk panduan keliling desa.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">directions_walk</span> Tur Desa Adat
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">breakfast_dining</span> Sarapan Khas
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 325.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Bali']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

      <!-- Card 4: Jatiluwih Bali (Sawah) -->
      <article data-category="sawah" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800&q=80" alt="Pondok Bambu Terasering" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-amber-200 shadow-xs">
              Sawah UNESCO
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.90</span>
              <span class="text-stone-400 font-normal">(165)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Jatiluwih, Tabanan, Bali</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Pondok Bambu Terasering</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Balkon kamar menghadap langsung panorama sawah bertingkat warisan dunia UNESCO dengan angin sejuk pegunungan.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">landscape</span> View Sawah UNESCO
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">deck</span> Teras Santai
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 295.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Bali']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

      <!-- Card 5: Nglanggeran Yogyakarta (Sawah) -->
      <article data-category="sawah" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80" alt="Limasan Gunung Purba" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-amber-200 shadow-xs">
              Kaki Gunung Purba
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.85</span>
              <span class="text-stone-400 font-normal">(92)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Nglanggeran, Gunungkidul</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Limasan Gunung Purba</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Nuansa rumah limasan kayu jati di kaki Gunung Api Purba. Dekat embung dan perkebunan kakao olahan warga.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">water</span> Dekat Embung
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">local_fire_department</span> Api Unggun
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 270.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Yogyakarta']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

      <!-- Card 6: Pentingsari Sleman (Sawah) -->
      <article data-category="sawah" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80" alt="Pondok Warga Kali Kuning" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-amber-200 shadow-xs">
              Pedesaan Asri
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.80</span>
              <span class="text-stone-400 font-normal">(76)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Pentingsari, Sleman, DIY</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Pondok Warga Kali Kuning</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Menginap bersama keluarga desa ramah di lereng Merapi. Suasana pedesaan hening dengan gemericik air sungai alami.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">skillet</span> Dapur Tradisional
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">yard</span> Gazebo Bambu
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 230.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Yogyakarta']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

      <!-- Card 7: Dieng Kulon (Gunung) -->
      <article data-category="gunung" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80" alt="Omah Embun Dieng Plateau" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200 shadow-xs">
              Dataran Tinggi
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.92</span>
              <span class="text-stone-400 font-normal">(180)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Dieng Kulon, Banjarnegara</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Omah Embun Dieng Plateau</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Berjarak 5 menit jalan kaki dari Candi Arjuna. Dilengkapi pemanas ruangan, selimut tebal, dan teh purwaceng hangat.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">fireplace</span> Penghangat Ruangan
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">temple_buddhist</span> Dekat Candi
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 280.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Dieng']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

      <!-- Card 8: Sade Lombok (Adat) -->
      <article data-category="adat" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?auto=format&fit=crop&w=800&q=80" alt="Bale Sasak Warisan Sade" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-orange-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-orange-200 shadow-xs">
              Desa Sasak
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.78</span>
              <span class="text-stone-400 font-normal">(64)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Desa Sade, Lombok Tengah</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Bale Sasak Warisan Sade</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Arsitektur bale tani tradisional suku Sasak dengan atap alang-alang otentik. Tersedia workshop tenun songket khas.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">palette</span> Workshop Tenun
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">tour</span> Pemandu Lokal
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 265.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Lombok']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

      <!-- Card 9: Sembalun Rinjani (Gunung) -->
      <article data-category="gunung" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1518780664697-55e3ad937233?auto=format&fit=crop&w=800&q=80" alt="Kubu Lembah Rinjani" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200 shadow-xs">
              Lembah Rinjani
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.88</span>
              <span class="text-stone-400 font-normal">(142)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Sembalun, Lombok Timur</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Kubu Lembah Rinjani</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Terletak di kaldera megah kaki Gunung Rinjani pada ketinggian 1.200 mdpl. Akses langsung petik buah strawberry warga.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">agriculture</span> Petik Strawberry
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">mountain_flag</span> View Bukit Selong
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 340.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Lombok']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

      <!-- Card 10: Cisande Sukabumi (Kebun) -->
      <article data-category="kebun" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80" alt="Saung Bambu Lembah Cisande" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-teal-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-teal-200 shadow-xs">
              Saung Kolam Ikan
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.82</span>
              <span class="text-stone-400 font-normal">(58)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Cisande, Sukabumi</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Saung Bambu Lembah Cisande</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Saung panggung bambu di atas kolam ikan dan hamparan sawah. Menyajikan santapan nasi liwet khas Sunda komplit.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">dinner_dining</span> Nasi Liwet Sunda
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">phishing</span> Kolam Pancing
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 245.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Sukabumi']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

      <!-- Card 11: Pangalengan Bandung (Kebun) -->
      <article data-category="kebun" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&q=80" alt="Pondok Teh Malabar Asri" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-teal-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-teal-200 shadow-xs">
              Kebun Teh Malabar
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.86</span>
              <span class="text-stone-400 font-normal">(115)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Pangalengan, Bandung Selatan</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Pondok Teh Malabar Asri</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Dikelilingi hamparan kebun teh peninggalan era Hindia Belanda dekat Situ Cileunca. Udara berkabut sejuk sepanjang hari.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">eco</span> Kebun Teh Luas
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">kayaking</span> Dekat Danau
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 350.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Bandung']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

      <!-- Card 12: Nagari Pariangan Minang (Adat) -->
      <article data-category="adat" class="homestay-card w-[320px] sm:w-[350px] shrink-0 snap-start bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between hover:-translate-y-1">
        <div>
          <div class="relative aspect-[16/10] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80" alt="Rumah Gadang Tuo Pariangan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-sm text-[#1b4d24]">verified</span>
              <span>Terverifikasi</span>
            </span>
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-orange-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-orange-200 shadow-xs">
              Rumah Gadang Minang
            </span>
            <div class="absolute bottom-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
              <span>4.94</span>
              <span class="text-stone-400 font-normal">(198)</span>
            </div>
          </div>

          <div class="p-5">
            <div class="flex items-center gap-1 text-xs font-semibold text-stone-500">
              <span class="material-symbols-outlined text-[#1b4d24] text-sm">location_on</span>
              <span>Nagari Pariangan, Tanah Datar</span>
            </div>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Rumah Gadang Tuo Pariangan</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Sensasi menginap di nagari tertua lereng Gunung Marapi berpredikat salah satu desa terindah dunia. Ukiran kayu Minang otentik.
            </p>

            <div class="flex flex-wrap gap-1.5 mt-4">
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">holiday_village</span> Arsitektur Rangkiang
              </span>
              <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[11px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px]">coffee</span> Kopi Kawa Daun
              </span>
            </div>
          </div>
        </div>

        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-base font-extrabold text-[#13391b]">Rp 310.000</span>
            <span class="text-xs text-stone-500">/ malam</span>
          </div>
          <a href="{{ route('homestay.index', ['q' => 'Sumatera']) }}" class="bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors active:scale-95">
            Lihat Kamar
          </a>
        </div>
      </article>

    </div>

    </div>
  </section>

  <!-- SECTION: CARA PESAN (4 LANGKAH MUDAH & PASTI) -->
  <section class="bg-white border-y border-stone-200 py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      
      <div class="text-center max-w-2xl mx-auto mb-16">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
          <span class="material-symbols-outlined text-sm">checklist</span>
          <span>Alur Reservasi Resmi</span>
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-stone-900 tracking-tight">4 Langkah Mudah Pulang ke Desa</h2>
        <p class="text-stone-500 text-sm mt-2">Tanpa perantara berbelit, tanpa risiko jadwal bentrok, langsung disambut hangat oleh keluarga desa.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Step 1 -->
        <div class="p-6 rounded-2xl bg-stone-50 border border-stone-200/80 hover:bg-white hover:shadow-md hover:border-emerald-300 transition-all duration-300 group">
          <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#13391b] flex items-center justify-center font-extrabold text-lg mb-4 group-hover:scale-110 group-hover:bg-[#13391b] group-hover:text-white transition-all">
            01
          </div>
          <h3 class="text-base font-bold text-stone-900 group-hover:text-[#1b4d24] transition-colors">Pilih Desa &amp; Homestay</h3>
          <p class="text-xs text-stone-600 mt-2 leading-relaxed">
            Pilih suasana lereng gunung, terasering sawah, atau rumah adat dengan foto asli dan ulasan terverifikasi Pokdarwis.
          </p>
        </div>

        <!-- Step 2 -->
        <div class="p-6 rounded-2xl bg-stone-50 border border-stone-200/80 hover:bg-white hover:shadow-md hover:border-emerald-300 transition-all duration-300 group">
          <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#13391b] flex items-center justify-center font-extrabold text-lg mb-4 group-hover:scale-110 group-hover:bg-[#13391b] group-hover:text-white transition-all">
            02
          </div>
          <h3 class="text-base font-bold text-stone-900 group-hover:text-[#1b4d24] transition-colors">Kunci Tanggal Real-Time</h3>
          <p class="text-xs text-stone-600 mt-2 leading-relaxed">
            Sistem kalender pintar langsung mengunci ketersediaan kamar secara real-time. 100% bebas risiko double booking.
          </p>
        </div>

        <!-- Step 3 -->
        <div class="p-6 rounded-2xl bg-stone-50 border border-stone-200/80 hover:bg-white hover:shadow-md hover:border-emerald-300 transition-all duration-300 group">
          <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#13391b] flex items-center justify-center font-extrabold text-lg mb-4 group-hover:scale-110 group-hover:bg-[#13391b] group-hover:text-white transition-all">
            03
          </div>
          <h3 class="text-base font-bold text-stone-900 group-hover:text-[#1b4d24] transition-colors">Bayar Aman &amp; Transparan</h3>
          <p class="text-xs text-stone-600 mt-2 leading-relaxed">
            Harga jujur tanpa biaya tersembunyi. Pembayaran diverifikasi cepat dan langsung diteruskan ke rekening pengelola desa.
          </p>
        </div>

        <!-- Step 4 -->
        <div class="p-6 rounded-2xl bg-stone-50 border border-stone-200/80 hover:bg-white hover:shadow-md hover:border-emerald-300 transition-all duration-300 group">
          <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#13391b] flex items-center justify-center font-extrabold text-lg mb-4 group-hover:scale-110 group-hover:bg-[#13391b] group-hover:text-white transition-all">
            04
          </div>
          <h3 class="text-base font-bold text-stone-900 group-hover:text-[#1b4d24] transition-colors">E-Tiket &amp; QR Check-in</h3>
          <p class="text-xs text-stone-600 mt-2 leading-relaxed">
            Dapatkan e-tiket resmi beserta kode QR digital di HP. Tunjukkan saat tiba di desa dan nikmati keramahan warga.
          </p>
        </div>

      </div>

    </div>
  </section>

  <!-- SECTION: PAKET WISATA OTENTIK KHAS DESA (DATABASE paket_wisata) -->
  <section id="paket" class="relative overflow-hidden py-20">
    <!-- Static Parallax Background Image Layer (Hutan Bambu & Budaya Desa Adat) -->
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
      <div
        id="paket-bg-img"
        class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-fixed opacity-0 transition-opacity duration-700 ease-out filter contrast-110 saturate-105"
        style="background-image: url('https://images.unsplash.com/photo-1512100356356-de1b84283e18?auto=format&fit=crop&w=2000&q=80');"
      ></div>
      <!-- Soft vignette & page background blend -->
      <div class="absolute inset-0 bg-gradient-to-b from-[#faf9f5]/90 via-[#faf9f5]/25 to-[#faf9f5]/90"></div>
    </div>

    <!-- Content Container -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
      
      <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
          <span class="material-symbols-outlined text-sm">local_activity</span>
          <span>Aktivitas Khas Desa Mitra</span>
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-stone-900 tracking-tight">Paket Wisata &amp; Kearifan Lokal</h2>
        <p class="text-stone-500 text-sm mt-1">Lengkapi pengalaman menginapmu dengan aktivitas otentik yang dipandu langsung oleh warga lokal.</p>
      </div>
      <a href="{{ route('homestay.index') }}" class="mt-4 sm:mt-0 text-sm font-semibold text-[#13391b] hover:text-[#ea580c] transition-colors flex items-center gap-1">
        <span>Lihat Semua Aktivitas</span>
        <span class="material-symbols-outlined text-base">arrow_forward</span>
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      
      <!-- Paket 1: Sembalun -->
      <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-lg transition-all group flex flex-col justify-between">
        <div>
          <div class="relative aspect-[16/11] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80" alt="Tracking Bukit Selong" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-[13px]">schedule</span>
              <span>2.5 Jam</span>
            </span>
          </div>
          <div class="p-5">
            <span class="text-[11px] font-bold text-[#ea580c] uppercase tracking-wider">Sembalun, Lombok</span>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Tracking Bukit Selong &amp; Petik Strawberry</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Menyaksikan sunrise kaldera Rinjani dari gardu pandang Bukit Selong dan memetik strawberry segar di kebun warga.
            </p>
          </div>
        </div>
        <div class="px-5 py-3.5 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-sm font-extrabold text-[#13391b]">Rp 75.000</span>
            <span class="text-[11px] text-stone-500">/ orang</span>
          </div>
          <a href="{{ route('homestay.index') }}" class="bg-stone-900 hover:bg-[#13391b] text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
            Pesan Paket
          </a>
        </div>
      </div>

      <!-- Paket 2: Baturraden -->
      <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-lg transition-all group flex flex-col justify-between">
        <div>
          <div class="relative aspect-[16/11] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=800&q=80" alt="Susur Curug Baturraden" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-[13px]">schedule</span>
              <span>3 Jam</span>
            </span>
          </div>
          <div class="p-5">
            <span class="text-[11px] font-bold text-[#ea580c] uppercase tracking-wider">Baturraden, Banyumas</span>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Susur Curug &amp; Hutan Pinus Limpakuwus</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Jelajah air terjun alami tersembunyi dengan pemandu Pokdarwis, dilanjutkan santap kelapa muda di keteduhan hutan pinus.
            </p>
          </div>
        </div>
        <div class="px-5 py-3.5 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-sm font-extrabold text-[#13391b]">Rp 60.000</span>
            <span class="text-[11px] text-stone-500">/ orang</span>
          </div>
          <a href="{{ route('homestay.index') }}" class="bg-stone-900 hover:bg-[#13391b] text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
            Pesan Paket
          </a>
        </div>
      </div>

      <!-- Paket 3: Sade Lombok -->
      <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-lg transition-all group flex flex-col justify-between">
        <div>
          <div class="relative aspect-[16/11] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=800&q=80" alt="Workshop Tenun Sade" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-[13px]">schedule</span>
              <span>2 Jam</span>
            </span>
          </div>
          <div class="p-5">
            <span class="text-[11px] font-bold text-[#ea580c] uppercase tracking-wider">Desa Sade, Lombok</span>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Workshop Tenun Songket &amp; Tur Adat Sasak</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Belajar menenun motif tradisional bersama ibu-ibu perajin Sade dan mendengarkan sejarah arsitektur bale tani.
            </p>
          </div>
        </div>
        <div class="px-5 py-3.5 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-sm font-extrabold text-[#13391b]">Rp 50.000</span>
            <span class="text-[11px] text-stone-500">/ orang</span>
          </div>
          <a href="{{ route('homestay.index') }}" class="bg-stone-900 hover:bg-[#13391b] text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
            Pesan Paket
          </a>
        </div>
      </div>

      <!-- Paket 4: Penglipuran Bali -->
      <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-lg transition-all group flex flex-col justify-between">
        <div>
          <div class="relative aspect-[16/11] overflow-hidden bg-stone-100">
            <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800&q=80" alt="Jelajah Subak Bali" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <span class="absolute top-3 left-3 bg-white text-stone-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
              <span class="material-symbols-outlined text-[13px]">schedule</span>
              <span>2.5 Jam</span>
            </span>
          </div>
          <div class="p-5">
            <span class="text-[11px] font-bold text-[#ea580c] uppercase tracking-wider">Penglipuran, Bali</span>
            <h3 class="text-base font-bold text-stone-900 mt-1 group-hover:text-[#1b4d24] transition-colors">Jelajah Hutan Bambu &amp; Jamu Loloh Cemcem</h3>
            <p class="text-xs text-stone-600 mt-2 line-clamp-2 leading-relaxed">
              Menelusuri jalur sakral hutan bambu seluas 45 hektar dan mencicipi kesegaran minuman herbal khas Penglipuran.
            </p>
          </div>
        </div>
        <div class="px-5 py-3.5 border-t border-stone-100 flex items-center justify-between bg-stone-50/50">
          <div>
            <span class="text-sm font-extrabold text-[#13391b]">Rp 65.000</span>
            <span class="text-[11px] text-stone-500">/ orang</span>
          </div>
          <a href="{{ route('homestay.index') }}" class="bg-stone-900 hover:bg-[#13391b] text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
            Pesan Paket
          </a>
        </div>
      </div>

    </div>

    </div>
  </section>

  <!-- KEUNGGULAN SISTEM (DENGAN ICON VECTOR RAPI) -->
  <section id="keunggulan" class="bg-stone-50 border-y border-stone-200 py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      
      <div class="max-w-2xl mb-14">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-[#1b4d24] text-xs font-bold uppercase tracking-wider mb-2">
          <span class="material-symbols-outlined text-sm">shield</span>
          <span>Standar Layanan Resmi</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-stone-900 tracking-tight mt-1">Mengapa Memilih HomeStay Go?</h2>
        <p class="text-stone-500 text-sm mt-1">Sistem dirancang untuk kepastian jadwal tamu dan perlindungan ekonomi warga desa binaan.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="p-7 rounded-2xl bg-white border border-stone-200 shadow-xs hover:shadow-md transition-shadow">
          <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#13391b] flex items-center justify-center mb-5">
            <span class="material-symbols-outlined text-2xl">event_available</span>
          </div>
          <h3 class="text-base font-bold text-stone-900">Bebas Risiko Jadwal Bentrok</h3>
          <p class="text-sm text-stone-600 mt-2.5 leading-relaxed">
            Pengecekan ketersediaan kamar berlangsung otomatis sebelum pesanan disimpan. Kamar terkunci begitu pembayaran terkonfirmasi.
          </p>
        </div>

        <div class="p-7 rounded-2xl bg-white border border-stone-200 shadow-xs hover:shadow-md transition-shadow">
          <div class="w-12 h-12 rounded-xl bg-orange-100 text-[#ea580c] flex items-center justify-center mb-5">
            <span class="material-symbols-outlined text-2xl">qr_code_2</span>
          </div>
          <h3 class="text-base font-bold text-stone-900">E-Tiket &amp; Verifikasi Digital</h3>
          <p class="text-sm text-stone-600 mt-2.5 leading-relaxed">
            Admin desa memverifikasi bukti bayar secara transparan. Wisatawan mendapatkan kode QR digital untuk check-in instan di lokasi.
          </p>
        </div>

        <div class="p-7 rounded-2xl bg-white border border-stone-200 shadow-xs hover:shadow-md transition-shadow">
          <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#13391b] flex items-center justify-center mb-5">
            <span class="material-symbols-outlined text-2xl">volunteer_activism</span>
          </div>
          <h3 class="text-base font-bold text-stone-900">Pemberdayaan Warga Nyata</h3>
          <p class="text-sm text-stone-600 mt-2.5 leading-relaxed">
            Pembayaran menginap dan paket wisata diterima langsung oleh keluarga warga desa binaan Pokdarwis tanpa potongan pihak ketiga berlebih.
          </p>
        </div>

      </div>

    </div>
  </section>

  <!-- SECTION: SUARA WISATAWAN (TESTIMONIAL ASLI) -->
  <section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      
      <div class="text-center max-w-2xl mx-auto mb-14">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-800 text-xs font-bold uppercase tracking-wider mb-2">
          <span class="material-symbols-outlined text-sm">reviews</span>
          <span>Cerita Dari Tamu</span>
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-stone-900 tracking-tight">Pengalaman Hangat Menginap di Desa</h2>
        <p class="text-stone-500 text-sm mt-2">Dengarkan kisah wisatawan yang telah merasakan ketenangan dan kearifan warga desa mitra.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Review 1 -->
        <div class="p-7 rounded-2xl bg-stone-50 border border-stone-200/80 flex flex-col justify-between">
          <div>
            <div class="flex items-center gap-1 text-amber-500 mb-4">
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
            </div>
            <p class="text-sm text-stone-700 leading-relaxed italic">
              "Anak-anak senang sekali diajak memetik sayur segar langsung dari kebun Pak Slamet. Udaranya sejuk, kasurnya nyaman dan wangi, sarapan tempe mendoan hangatnya juara!"
            </p>
          </div>
          <div class="mt-6 pt-5 border-t border-stone-200/60 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-emerald-200 text-[#13391b] font-bold text-sm flex items-center justify-center">
              DP
            </div>
            <div>
              <h4 class="text-xs font-bold text-stone-900">Dian Prasetyo &amp; Keluarga</h4>
              <p class="text-[11px] text-stone-500">Jakarta • Menginap di Baturraden</p>
            </div>
          </div>
        </div>

        <!-- Review 2 -->
        <div class="p-7 rounded-2xl bg-stone-50 border border-stone-200/80 flex flex-col justify-between">
          <div>
            <div class="flex items-center gap-1 text-amber-500 mb-4">
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
            </div>
            <p class="text-sm text-stone-700 leading-relaxed italic">
              "Desa Penglipuran sangat damai dan teratur. Tuan rumah menyambut kami seperti keluarga sendiri yang pulang kampung. Booking di HomeStay Go sangat mudah dan pasti!"
            </p>
          </div>
          <div class="mt-6 pt-5 border-t border-stone-200/60 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-orange-200 text-[#ea580c] font-bold text-sm flex items-center justify-center">
              NL
            </div>
            <div>
              <h4 class="text-xs font-bold text-stone-900">Ni Made Ayu Lestari</h4>
              <p class="text-[11px] text-stone-500">Surabaya • Menginap di Penglipuran</p>
            </div>
          </div>
        </div>

        <!-- Review 3 -->
        <div class="p-7 rounded-2xl bg-stone-50 border border-stone-200/80 flex flex-col justify-between">
          <div>
            <div class="flex items-center gap-1 text-amber-500 mb-4">
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
              <span class="material-symbols-outlined text-lg">star</span>
            </div>
            <p class="text-sm text-stone-700 leading-relaxed italic">
              "View Bukit Selong dari jendela kamar saat fajar luar biasa magis. Tidak ada biaya siluman saat check-in, semua sesuai yang tertera di sistem. Pasti akan kembali lagi!"
            </p>
          </div>
          <div class="mt-6 pt-5 border-t border-stone-200/60 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-teal-200 text-teal-800 font-bold text-sm flex items-center justify-center">
              RK
            </div>
            <div>
              <h4 class="text-xs font-bold text-stone-900">Reza Kurniawan</h4>
              <p class="text-[11px] text-stone-500">Bandung • Menginap di Sembalun</p>
            </div>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- SECTION: DAMPAK NYATA EKONOMI DESA (STATISTIK COUNTER - ORANGE) -->
  <section class="bg-[#ea580c] text-white py-16 shadow-inner relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center divide-y lg:divide-y-0 lg:divide-x divide-orange-400/50">
        
        <div class="pt-4 lg:pt-0">
          <span class="block text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white">100%</span>
          <span class="block text-xs font-bold uppercase tracking-wider text-orange-100 mt-2">Dukungan Warga</span>
          <p class="text-[11px] text-orange-100/90 mt-1">Dana reservasi langsung ke kas warga desa</p>
        </div>

        <div class="pt-4 lg:pt-0">
          <span class="block text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white">0</span>
          <span class="block text-xs font-bold uppercase tracking-wider text-orange-100 mt-2">Double Booking</span>
          <p class="text-[11px] text-orange-100/90 mt-1">Sistem kunci kalender real-time</p>
        </div>

        <div class="pt-4 lg:pt-0">
          <span class="block text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white">12+</span>
          <span class="block text-xs font-bold uppercase tracking-wider text-orange-100 mt-2">Desa Wisata Mitra</span>
          <p class="text-[11px] text-orange-100/90 mt-1">Terverifikasi binaan Pokdarwis resmi</p>
        </div>

        <div class="pt-4 lg:pt-0">
          <span class="block text-3xl sm:text-4xl lg:text-5xl font-extrabold text-amber-200">4.92<span class="text-2xl text-orange-200">/5</span></span>
          <span class="block text-xs font-bold uppercase tracking-wider text-orange-100 mt-2">Kepuasan Tamu</span>
          <p class="text-[11px] text-orange-100/90 mt-1">Dari ratusan ulasan wisatawan asli</p>
        </div>

      </div>
    </div>
  </section>

  <!-- SECTION: GRAND FINALE CINEMATIC CTA DENGAN SCROLL REVEAL FADE-IN & SCALE BACKGROUND -->
  <section id="grand-finale-cta" class="relative py-32 lg:py-44 overflow-hidden bg-stone-950 text-white flex items-center justify-center">
    
    <!-- Background Image with dynamic scroll zoom & fade-in reveal -->
    <div class="absolute inset-0 z-0 overflow-hidden">
      <img id="finale-bg-img" 
           src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=2400&q=90" 
           alt="Panorama Pagi Desa Wisata Indonesia" 
           class="w-full h-full object-cover transition-all duration-1000 ease-out will-change-transform will-change-opacity opacity-20 scale-110 filter brightness-90" />
      <!-- Atmospheric dark gradient scrim overlays -->
      <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-950/60 to-stone-950/80"></div>
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(19,57,27,0.5)_0%,rgba(0,0,0,0.85)_100%)]"></div>
    </div>

    <!-- Center Content with reveal animation -->
    <div id="finale-content" class="relative z-10 max-w-4xl mx-auto px-6 text-center transition-all duration-700 ease-out translate-y-8 opacity-0">
      
      <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-stone-900 text-xs font-bold shadow-lg mb-6">
        <span class="material-symbols-outlined text-[#1b4d24] text-base">nest_eco_leaf</span>
        <span>Waktunya Beristirahat dari Hiruk-Pikuk Kota</span>
      </div>

      <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-[1.2] drop-shadow-lg">
        Pulang ke Rumah Desa, <br/>
        <span class="text-emerald-400">Rasakan Kehangatan</span> yang <span class="text-[#ea580c]">Sesungguhnya.</span>
      </h2>

      <p class="mt-5 text-base sm:text-lg text-stone-200 max-w-2xl mx-auto font-normal leading-relaxed drop-shadow">
        Jadikan liburanmu bermakna. Setiap malam yang kamu habiskan bersama keluarga desa bukan sekadar menginap, melainkan menjaga denyut ekonomi dan kelestarian budaya Nusantara.
      </p>

      <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
        <button type="button" onclick="document.getElementById('katalog').scrollIntoView({ behavior: 'smooth' })" class="w-full sm:w-auto bg-[#ea580c] hover:bg-[#c2410c] text-white text-sm font-bold px-8 py-4 rounded-full transition-all shadow-xl hover:shadow-orange-500/30 flex items-center justify-center gap-2 active:scale-95 group cursor-pointer">
          <span>Pilih Homestay Sekarang</span>
          <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
        </button>
        <a href="#keunggulan" class="w-full sm:w-auto bg-white/10 hover:bg-white/20 text-white text-sm font-semibold px-7 py-4 rounded-full transition-all flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-lg">info</span>
          <span>Pelajari Skema Pokdarwis</span>
        </a>
      </div>

      <div class="mt-12 pt-8 border-t border-white/15 flex flex-wrap items-center justify-center gap-6 sm:gap-10 text-xs text-stone-300 font-medium">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-emerald-400 text-base">verified</span>
          <span>Binaan Pokdarwis Resmi</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-emerald-400 text-base">lock</span>
          <span>Kalender Anti-Double-Booking</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-emerald-400 text-base">receipt_long</span>
          <span>Harga Transparan Bebas Biaya Siluman</span>
        </div>
      </div>

    </div>
  </section>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // 1. SCRIPT SLIDESHOW OTOMATIS
    let current = 0;
    const total = 4;
    const slides = [
      document.getElementById('slide-0'),
      document.getElementById('slide-1'),
      document.getElementById('slide-2'),
      document.getElementById('slide-3')
    ];
    const indicators = document.querySelectorAll('.slide-indicator');

    function updateSlide(index) {
      slides.forEach((s, i) => {
        if (!s) return;
        if (i === index) s.classList.add('active');
        else s.classList.remove('active');
      });

      indicators.forEach((ind, i) => {
        if (!ind) return;
        if (i === index) {
          ind.classList.remove('w-2', 'bg-white/40');
          ind.classList.add('w-6', 'bg-white');
        } else {
          ind.classList.remove('w-6', 'bg-white');
          ind.classList.add('w-2', 'bg-white/40');
        }
      });

      current = index;
    }

    function autoNext() {
      const nextIndex = (current + 1) % total;
      updateSlide(nextIndex);
    }

    window.setSlide = function(index) {
      updateSlide(index);
      clearInterval(timer);
      timer = setInterval(autoNext, 5000);
    };

    let timer = setInterval(autoNext, 5000);

    // 2. SCRIPT ROTATING WORD (SUPER SMOOTH SLIDE-UP, ZERO LAYOUT SHIFT)
    const rotatingWords = [
      'double booking',
      'jadwal bentrok',
      'biaya tersembunyi',
      'drama reservasi',
      'rebutan kamar'
    ];
    let wordIdx = 0;
    const rotatingEl = document.getElementById('word-rotator-text');

    function slideNextWord() {
      if (!rotatingEl) return;
      
      rotatingEl.style.transition = 'transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.35s ease';
      rotatingEl.style.transform = 'translateY(-120%)';
      rotatingEl.style.opacity = '0';

      setTimeout(() => {
        wordIdx = (wordIdx + 1) % rotatingWords.length;
        rotatingEl.textContent = rotatingWords[wordIdx];

        rotatingEl.style.transition = 'none';
        rotatingEl.style.transform = 'translateY(120%)';
        rotatingEl.style.opacity = '0';

        requestAnimationFrame(() => {
          requestAnimationFrame(() => {
            rotatingEl.style.transition = 'transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.45s ease-out';
            rotatingEl.style.transform = 'translateY(0%)';
            rotatingEl.style.opacity = '1';
          });
        });
      }, 350);
    }

    setInterval(slideNextWord, 2800);

    // 3. SCRIPT CAROUSEL HOMESTAY (GESER KE SAMPING SMOOTH) & FILTER KATEGORI
    const homestayCarousel = document.getElementById('homestay-carousel');
    const carouselCounter = document.getElementById('carousel-counter');

    window.scrollHomestayCarousel = function(direction) {
      if (!homestayCarousel) return;
      const scrollStep = 374;
      homestayCarousel.scrollBy({
        left: direction * scrollStep,
        behavior: 'smooth'
      });
    };

    window.filterHomestayCategory = function(e, category) {
      document.querySelectorAll('.cat-pill').forEach(pill => {
        pill.classList.remove('active', 'bg-[#13391b]', 'text-white', 'shadow-xs');
        pill.classList.add('bg-white', 'text-stone-700', 'border', 'border-stone-200');
      });

      if (e && e.currentTarget) {
        e.currentTarget.classList.remove('bg-white', 'text-stone-700', 'border', 'border-stone-200');
        e.currentTarget.classList.add('active', 'bg-[#13391b]', 'text-white', 'shadow-xs');
      }

      const cards = document.querySelectorAll('.homestay-card');
      let visibleCount = 0;

      cards.forEach(card => {
        const cardCat = card.getAttribute('data-category');
        if (category === 'all' || cardCat === category) {
          card.style.display = 'flex';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      if (carouselCounter) {
        carouselCounter.textContent = `${visibleCount} Homestay Ditampilkan`;
      }

      if (homestayCarousel) {
        homestayCarousel.scrollTo({ left: 0, behavior: 'smooth' });
      }
    };

    // 4. SCRIPT STATIS BACKGROUND IMAGE DENGAN SMOOTH FADE-IN & FADE-OUT PADA SCROLL
    const katalogSec = document.getElementById('katalog');
    const katalogBg = document.getElementById('katalog-bg-img');
    const paketSec = document.getElementById('paket');
    const paketBg = document.getElementById('paket-bg-img');

    if ('IntersectionObserver' in window && katalogSec && paketSec) {
      const stateBgObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.target.id === 'katalog' && katalogBg) {
            if (entry.isIntersecting) {
              katalogBg.style.opacity = '0.65';
            } else {
              katalogBg.style.opacity = '0';
            }
          }
          if (entry.target.id === 'paket' && paketBg) {
            if (entry.isIntersecting) {
              paketBg.style.opacity = '0.65';
            } else {
              paketBg.style.opacity = '0';
            }
          }
        });
      }, {
        threshold: [0, 0.08, 0.25],
        rootMargin: '-30px 0px -30px 0px'
      });

      stateBgObserver.observe(katalogSec);
      stateBgObserver.observe(paketSec);
    } else {
      if (katalogBg) katalogBg.style.opacity = '0.65';
      if (paketBg) paketBg.style.opacity = '0.65';
    }

    // 5. GRAND FINALE SCROLL REVEAL OBSERVER (BACKGROUND FADE-IN & SCALE EFFECT)
    const finaleSection = document.getElementById('grand-finale-cta');
    const finaleBgImg = document.getElementById('finale-bg-img');
    const finaleContent = document.getElementById('finale-content');

    if (finaleSection && finaleBgImg && finaleContent) {
      const finaleObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            finaleBgImg.classList.remove('opacity-20', 'scale-110');
            finaleBgImg.classList.add('opacity-75', 'scale-100');

            finaleContent.classList.remove('translate-y-8', 'opacity-0');
            finaleContent.classList.add('translate-y-0', 'opacity-100');
          }
        });
      }, {
        threshold: 0.2
      });

      finaleObserver.observe(finaleSection);

      window.addEventListener('scroll', () => {
        const rect = finaleSection.getBoundingClientRect();
        const windowHeight = window.innerHeight;
        if (rect.top < windowHeight && rect.bottom > 0) {
          const progress = (windowHeight - rect.top) / (windowHeight + rect.height);
          const translateY = (progress - 0.5) * 35;
          finaleBgImg.style.transform = `translateY(${translateY}px) scale(${finaleBgImg.classList.contains('scale-100') ? '1.0' : '1.1'})`;
        }
      }, { passive: true });
    }

    // 5. SCRIPT INTERAKTIF JADWAL MENGINAP (DATE PICKER)
    const dateBox = document.getElementById('date-picker-box');
    const datePopover = document.getElementById('date-picker-popover');
    const closePopoverBtn = document.getElementById('close-date-popover');
    const applyDateBtn = document.getElementById('btn-apply-date');
    const checkinInput = document.getElementById('input-checkin');
    const checkoutInput = document.getElementById('input-checkout');
    const dateDisplayText = document.getElementById('date-display-text');
    const durasiLabel = document.getElementById('durasi-label');

    const monthsID = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    function formatDateShort(dateStr) {
      if (!dateStr) return '';
      const parts = dateStr.split('-');
      if (parts.length !== 3) return dateStr;
      const d = parseInt(parts[2], 10);
      const m = parseInt(parts[1], 10) - 1;
      return `${d} ${monthsID[m] || ''}`;
    }

    function calculateNights() {
      if (!checkinInput || !checkoutInput) return 1;
      const cin = new Date(checkinInput.value);
      const cout = new Date(checkoutInput.value);
      if (isNaN(cin) || isNaN(cout)) return 1;
      const diffTime = cout - cin;
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      return diffDays > 0 ? diffDays : 1;
    }

    function syncDateDisplay() {
      const nights = calculateNights();
      if (durasiLabel) durasiLabel.textContent = `${nights} Malam`;
      
      if (checkinInput && checkoutInput && dateDisplayText) {
        const inFormatted = formatDateShort(checkinInput.value);
        const outFormatted = formatDateShort(checkoutInput.value);
        dateDisplayText.textContent = `${inFormatted} — ${outFormatted}`;
      }
    }

    if (dateBox && datePopover) {
      dateBox.addEventListener('click', () => {
        datePopover.classList.toggle('hidden');
      });

      document.addEventListener('click', (e) => {
        if (!dateBox.contains(e.target)) {
          datePopover.classList.add('hidden');
        }
      });
    }

    if (closePopoverBtn && datePopover) {
      closePopoverBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        datePopover.classList.add('hidden');
      });
    }

    if (applyDateBtn && datePopover) {
      applyDateBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        syncDateDisplay();
        datePopover.classList.add('hidden');
      });
    }

    if (checkinInput && checkoutInput) {
      checkinInput.addEventListener('change', () => {
        const cin = new Date(checkinInput.value);
        if (!isNaN(cin)) {
          const nextDay = new Date(cin);
          nextDay.setDate(nextDay.getDate() + 1);
          const minDateStr = nextDay.toISOString().split('T')[0];
          checkoutInput.min = minDateStr;
          if (new Date(checkoutInput.value) <= cin) {
            checkoutInput.value = minDateStr;
          }
        }
        syncDateDisplay();
      });

      checkoutInput.addEventListener('change', () => {
        syncDateDisplay();
      });
    }

    window.setPresetDate = function(nights) {
      if (!checkinInput || !checkoutInput) return;
      const today = new Date(checkinInput.value || '2026-11-14');
      const cout = new Date(today);
      cout.setDate(cout.getDate() + nights);
      
      checkoutInput.value = cout.toISOString().split('T')[0];
      syncDateDisplay();
    };

    window.selectQuickCity = function(cityVal) {
      const sel = document.getElementById('select-kota');
      if (sel) sel.value = cityVal;
      const katalogEl = document.getElementById('katalog');
      if (katalogEl) katalogEl.scrollIntoView({ behavior: 'smooth' });
    };

    syncDateDisplay();
  });
</script>
@endpush
