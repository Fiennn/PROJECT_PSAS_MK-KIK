@extends('layouts.app')

@section('title', 'Radar Penunjuk Arah Homestay (AR)')
@section('meta_description', 'Pindai dan temukan homestay, villa, cottage, dan shelter terdekat di kawasan desa wisata Banyumas menggunakan kamera Augmented Reality (WebAR) dan kompas digital.')

@push('head')
<style>
    /* Radar sweep animation */
    @keyframes radar-sweep {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-radar-sweep {
        animation: radar-sweep 4s linear infinite;
        transform-origin: center center;
    }
    /* Pulse glow */
    @keyframes radar-pulse {
        0%, 100% { opacity: 0.2; transform: scale(0.95); }
        50% { opacity: 0.7; transform: scale(1.05); }
    }
    .animate-radar-pulse {
        animation: radar-pulse 2.5s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div
    x-data="{
        allShelters: {{ Js::from($shelters) }},
        presets: {{ Js::from($presets) }},
        selectedPreset: 'baturraden',
        userLat: -7.3150000,
        userLng: 109.2280000,
        deviceHeading: 0,
        hasCompass: false,
        cameraActive: false,
        cameraError: '',
        selectedCategory: 'all',
        selectedRadius: 10, // km (0 = all)
        viewMode: 'camera', // 'camera' or 'radar'
        activeShelter: null,
        showDrawer: false,
        gpsLoading: false,
        gpsStatus: 'Simulasi: Baturraden',

        init() {
            this.setPreset('baturraden');
            this.initSensors();
        },

        initSensors() {
            // Device Orientation (Compass)
            if (window.DeviceOrientationEvent) {
                window.addEventListener('deviceorientation', (event) => {
                    let heading = null;
                    if (event.webkitCompassHeading !== undefined) {
                        // iOS Safari
                        heading = event.webkitCompassHeading;
                        this.hasCompass = true;
                    } else if (event.alpha !== null) {
                        // Android Chrome / Standard (alpha 0 is North with absolute=true, or approximate)
                        heading = (360 - event.alpha) % 360;
                        this.hasCompass = true;
                    }
                    if (heading !== null) {
                        this.deviceHeading = Math.round(heading);
                    }
                }, true);
            }
        },

        async startCamera() {
            if (! navigator.mediaDevices || ! navigator.mediaDevices.getUserMedia) {
                this.cameraError = 'Peramban ini tidak mendukung akses kamera langsung.';
                this.viewMode = 'radar';
                return;
            }
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'environment' } },
                    audio: false
                });
                const videoEl = document.getElementById('ar-video-stream');
                if (videoEl) {
                    videoEl.srcObject = stream;
                    await videoEl.play();
                    this.cameraActive = true;
                    this.cameraError = '';
                }
            } catch (err) {
                console.warn('Camera access denied or unavailable:', err);
                this.cameraActive = false;
                this.cameraError = 'Izin kamera tidak aktif. Mengalihkan ke mode Radar HUD Digital.';
                this.viewMode = 'radar';
            }
        },

        stopCamera() {
            const videoEl = document.getElementById('ar-video-stream');
            if (videoEl && videoEl.srcObject) {
                videoEl.srcObject.getTracks().forEach(track => track.stop());
                videoEl.srcObject = null;
            }
            this.cameraActive = false;
        },

        requestGps() {
            if (! navigator.geolocation) {
                alert('Geolocation tidak didukung oleh peramban ini.');
                return;
            }
            this.gpsLoading = true;
            this.gpsStatus = 'Mendeteksi koordinat GPS...';
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.userLat = pos.coords.latitude;
                    this.userLng = pos.coords.longitude;
                    this.selectedPreset = 'gps';
                    this.gpsStatus = `GPS Nyata: ${this.userLat.toFixed(4)}, ${this.userLng.toFixed(4)}`;
                    this.gpsLoading = false;
                },
                (err) => {
                    this.gpsLoading = false;
                    this.gpsStatus = 'Izin GPS ditolak, menggunakan simulasi.';
                    alert('Tidak dapat mengakses GPS (' + err.message + '). Silakan gunakan tombol simulator lokasi.');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },

        setPreset(key) {
            this.selectedPreset = key;
            const p = this.presets.find(item => item.key === key);
            if (p) {
                this.userLat = p.lat;
                this.userLng = p.lng;
                this.gpsStatus = `Simulasi: ${p.label}`;
            }
        },

        // Haversine distance in KM
        calculateDistance(lat, lng) {
            const R = 6371;
            const dLat = (lat - this.userLat) * Math.PI / 180;
            const dLng = (lng - this.userLng) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(this.userLat * Math.PI / 180) * Math.cos(lat * Math.PI / 180) *
                      Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        },

        // Compass initial bearing (0 - 360 deg)
        calculateBearing(lat, lng) {
            const lat1 = this.userLat * Math.PI / 180;
            const lat2 = lat * Math.PI / 180;
            const dLng = (lng - this.userLng) * Math.PI / 180;
            const y = Math.sin(dLng) * Math.cos(lat2);
            const x = Math.cos(lat1) * Math.sin(lat2) - Math.sin(lat1) * Math.cos(lat2) * Math.cos(dLng);
            const brng = Math.atan2(y, x) * 180 / Math.PI;
            return (brng + 360) % 360;
        },

        // Relative angle to current device heading (-180 to +180)
        getDeltaAngle(targetBearing) {
            let diff = (targetBearing - this.deviceHeading + 360) % 360;
            if (diff > 180) diff -= 360;
            return diff;
        },

        // Filtered and enriched list of shelters
        get computedShelters() {
            return this.allShelters
                .filter(s => {
                    if (this.selectedCategory !== 'all' && s.kategori !== this.selectedCategory) {
                        return false;
                    }
                    return true;
                })
                .map(s => {
                    const dist = this.calculateDistance(s.lat, s.lng);
                    const bearing = this.calculateBearing(s.lat, s.lng);
                    const deltaAngle = this.getDeltaAngle(bearing);
                    return {
                        ...s,
                        distance: dist,
                        distanceText: dist < 1 ? Math.round(dist * 1000) + ' m' : dist.toFixed(1) + ' km',
                        bearing: Math.round(bearing),
                        deltaAngle: deltaAngle,
                        // Within FOV of ~60 degrees (-30 to +30)
                        inFov: Math.abs(deltaAngle) <= 32,
                        // Screen X position percentage (10% to 90%)
                        screenX: 50 + (deltaAngle / 32) * 38
                    };
                })
                .filter(s => {
                    if (this.selectedRadius > 0 && s.distance > this.selectedRadius) {
                        return false;
                    }
                    return true;
                })
                .sort((a, b) => a.distance - b.distance);
        },

        get visibleArPins() {
            return this.computedShelters.filter(s => s.inFov).slice(0, 8);
        }
    }"
    class="relative min-h-[calc(100vh-64px)] bg-stone-950 text-white select-none overflow-hidden"
>
    {{-- Background: Video Stream for Camera AR or Stylized Military Radar Grid --}}
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        {{-- Video Element for Live Camera --}}
        <video
            id="ar-video-stream"
            x-show="viewMode === 'camera' && cameraActive"
            autoplay
            playsinline
            muted
            class="h-full w-full object-cover"
        ></video>

        {{-- Dark Radar HUD Backdrop (Visible in Radar Mode or when camera is off) --}}
        <div
            x-show="viewMode === 'radar' || !cameraActive"
            class="absolute inset-0 bg-[#07130b] flex items-center justify-center"
        >
            {{-- Radar Grid Concentric Circles --}}
            <div class="relative w-[340px] sm:w-[480px] md:w-[600px] h-[340px] sm:h-[480px] md:h-[600px] rounded-full border border-emerald-500/20 flex items-center justify-center">
                <div class="w-3/4 h-3/4 rounded-full border border-emerald-500/25 flex items-center justify-center">
                    <div class="w-1/2 h-1/2 rounded-full border border-emerald-500/30 flex items-center justify-center">
                        <div class="w-1/4 h-1/4 rounded-full border border-emerald-400/40"></div>
                    </div>
                </div>
                {{-- Crosshairs --}}
                <div class="absolute inset-x-0 top-1/2 h-px bg-emerald-500/20"></div>
                <div class="absolute inset-y-0 left-1/2 w-px bg-emerald-500/20"></div>

                {{-- Rotating Radar Sweep Beam --}}
                <div class="absolute inset-0 rounded-full overflow-hidden pointer-events-none animate-radar-sweep">
                    <div class="w-1/2 h-1/2 bg-gradient-to-br from-emerald-500/40 via-emerald-400/10 to-transparent"></div>
                </div>

                {{-- Compass Cardinal Points on Big Radar --}}
                <span class="absolute top-2 left-1/2 -translate-x-1/2 font-mono text-xs font-bold text-emerald-400 tracking-widest">UTARA (0°)</span>
                <span class="absolute bottom-2 left-1/2 -translate-x-1/2 font-mono text-xs font-bold text-emerald-500/70">SELATAN (180°)</span>
                <span class="absolute right-3 top-1/2 -translate-y-1/2 font-mono text-xs font-bold text-emerald-500/70">TIMUR (90°)</span>
                <span class="absolute left-3 top-1/2 -translate-y-1/2 font-mono text-xs font-bold text-emerald-500/70">BARAT (270°)</span>

                {{-- Center User Dot --}}
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-4 h-4 rounded-full bg-orange-500 border-2 border-white shadow-lg animate-pulse"></div>

                {{-- Radar Blips (Shelter Targets on 2D Radar Map) --}}
                <template x-for="item in computedShelters.slice(0, 20)" :key="'radar-blip-' + item.id">
                    @php
                        // Angular placement calculated via Alpine
                    @endphp
                    <div
                        @click="activeShelter = item"
                        class="absolute cursor-pointer pointer-events-auto transform -translate-x-1/2 -translate-y-1/2 transition-all hover:scale-125"
                        :style="`
                            left: ${50 + Math.sin(item.bearing * Math.PI / 180) * Math.min(45, (item.distance / Math.max(selectedRadius || 12, 1)) * 42)}%;
                            top: ${50 - Math.cos(item.bearing * Math.PI / 180) * Math.min(45, (item.distance / Math.max(selectedRadius || 12, 1)) * 42)}%;
                        `"
                        :title="`${item.nama} (${item.distanceText})`"
                    >
                        <div class="relative flex items-center justify-center">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 border border-white shadow-md"></span>
                            <span class="absolute -top-5 left-1/2 -translate-x-1/2 text-[9px] font-mono font-bold whitespace-nowrap bg-black/75 px-1.5 py-0.5 rounded text-emerald-300 border border-emerald-500/30"
                                  x-text="item.nama.substring(0, 14)"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Vignette & Dark Overlay for Camera Mode --}}
        <div x-show="viewMode === 'camera'" class="absolute inset-0 bg-gradient-to-t from-stone-950/80 via-transparent to-stone-950/70 pointer-events-none"></div>
    </div>

    {{-- Top Controls Header Bar --}}
    <header class="relative z-20 px-4 py-3 sm:px-6 bg-stone-950/80 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            {{-- Brand & Title --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="text-stone-400 hover:text-white transition" title="Kembali ke Beranda">
                        <span class="material-symbols-outlined text-xl">arrow_back</span>
                    </a>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-600 to-forest-900 flex items-center justify-center text-emerald-200 border border-emerald-500/40">
                            <span class="material-symbols-outlined text-lg">radar</span>
                        </div>
                        <div>
                            <h1 class="text-sm sm:text-base font-extrabold tracking-tight text-white flex items-center gap-1.5">
                                <span>Radar AR Homestay</span>
                                <span class="px-2 py-0.2 rounded-md bg-orange-600 text-white text-[10px] font-bold uppercase tracking-wider">WebAR</span>
                            </h1>
                            <p class="text-[11px] text-stone-400" x-text="gpsStatus"></p>
                        </div>
                    </div>
                </div>

                {{-- Mode Switcher (Kamera AR vs Radar Digital) --}}
                <div class="flex items-center rounded-2xl bg-white/10 p-1 border border-white/10 text-xs">
                    <button
                        type="button"
                        @click="viewMode = 'camera'; if(!cameraActive) startCamera()"
                        :class="viewMode === 'camera' ? 'bg-[#13391b] text-white shadow' : 'text-stone-300 hover:text-white'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-bold transition"
                    >
                        <span class="material-symbols-outlined text-sm">videocam</span>
                        <span>Kamera AR</span>
                    </button>
                    <button
                        type="button"
                        @click="viewMode = 'radar'; stopCamera()"
                        :class="viewMode === 'radar' ? 'bg-[#13391b] text-white shadow' : 'text-stone-300 hover:text-white'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-bold transition"
                    >
                        <span class="material-symbols-outlined text-sm">explore</span>
                        <span>Radar HUD</span>
                    </button>
                </div>
            </div>

            {{-- Simulator Controls & Filters --}}
            <div class="flex flex-wrap items-center gap-2">
                {{-- Preset Simulator Dropdown --}}
                <div class="flex items-center gap-1.5 bg-white/5 border border-white/10 rounded-xl px-2.5 py-1 text-xs">
                    <span class="material-symbols-outlined text-sm text-amber-400">near_me</span>
                    <label for="preset-select" class="text-[11px] text-stone-400 font-semibold">Lokasi:</label>
                    <select
                        id="preset-select"
                        x-model="selectedPreset"
                        @change="setPreset(selectedPreset)"
                        class="bg-transparent text-white font-bold text-xs focus:outline-none cursor-pointer"
                    >
                        <template x-for="p in presets" :key="p.key">
                            <option :value="p.key" x-text="p.label" class="bg-stone-900 text-white"></option>
                        </template>
                    </select>
                </div>

                {{-- Button GPS Asli --}}
                <button
                    type="button"
                    @click="requestGps()"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-white/15 bg-white/5 hover:bg-white/10 text-xs text-stone-200 font-semibold transition"
                    title="Gunakan Koordinat GPS Asli Perangkat"
                >
                    <span class="material-symbols-outlined text-sm text-emerald-400" :class="{ 'animate-spin': gpsLoading }">my_location</span>
                    <span class="hidden sm:inline">GPS Saya</span>
                </button>

                {{-- Radius Filter --}}
                <select
                    x-model.number="selectedRadius"
                    class="rounded-xl border border-white/10 bg-white/5 px-2.5 py-1.5 text-xs font-semibold text-stone-200 focus:outline-none cursor-pointer"
                >
                    <option value="3" class="bg-stone-900 text-white">Radius 3 km</option>
                    <option value="5" class="bg-stone-900 text-white">Radius 5 km</option>
                    <option value="10" class="bg-stone-900 text-white">Radius 10 km</option>
                    <option value="25" class="bg-stone-900 text-white">Radius 25 km</option>
                    <option value="0" class="bg-stone-900 text-white">Semua Jarak</option>
                </select>

                {{-- Toggle Drawer List --}}
                <button
                    type="button"
                    @click="showDrawer = !showDrawer"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold shadow transition active:scale-95"
                >
                    <span class="material-symbols-outlined text-sm">format_list_bulleted</span>
                    <span x-text="`${computedShelters.length} Shelter`"></span>
                </button>
            </div>
        </div>
    </header>

    {{-- Main AR Viewport (HUD Overlays) --}}
    <main class="relative z-10 w-full h-[calc(100vh-130px)] sm:h-[calc(100vh-125px)] flex flex-col justify-between p-4 sm:p-6 pointer-events-none">

        {{-- Top HUD Strip: Compass Heading & Horizon Banner --}}
        <div class="flex items-start justify-between gap-4 pointer-events-auto">
            {{-- Compass Tape Heading --}}
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-2xl bg-black/60 backdrop-blur border border-white/15 text-xs font-mono">
                <span class="material-symbols-outlined text-sm text-amber-400">navigation</span>
                <span class="font-bold text-emerald-400" x-text="`${deviceHeading}°`"></span>
                <span class="text-stone-400" x-text="
                    deviceHeading >= 337 || deviceHeading < 23 ? 'UTARA' :
                    deviceHeading >= 23 && deviceHeading < 68 ? 'TIMUR LAUT' :
                    deviceHeading >= 68 && deviceHeading < 113 ? 'TIMUR' :
                    deviceHeading >= 113 && deviceHeading < 158 ? 'TENGGARA' :
                    deviceHeading >= 158 && deviceHeading < 203 ? 'SELATAN' :
                    deviceHeading >= 203 && deviceHeading < 248 ? 'BARAT DAYA' :
                    deviceHeading >= 248 && deviceHeading < 293 ? 'BARAT' : 'BARAT LAUT'
                "></span>
            </div>

            {{-- Category Filter Chips --}}
            <div class="hidden md:flex flex-wrap items-center gap-1.5 bg-black/50 backdrop-blur p-1 rounded-2xl border border-white/10 text-xs">
                <button
                    type="button"
                    @click="selectedCategory = 'all'"
                    :class="selectedCategory === 'all' ? 'bg-emerald-800 text-white' : 'text-stone-300 hover:text-white'"
                    class="px-2.5 py-1 rounded-xl font-bold transition"
                >
                    Semua ({{ count($shelters) }})
                </button>
                @foreach ($categories as $cat)
                    <button
                        type="button"
                        @click="selectedCategory = '{{ $cat }}'"
                        :class="selectedCategory === '{{ $cat }}' ? 'bg-emerald-800 text-white' : 'text-stone-300 hover:text-white'"
                        class="px-2.5 py-1 rounded-xl font-semibold transition"
                    >
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            {{-- Compass Heading Stepper (For Manual Gyroscope Testing on PC) --}}
            <div class="flex items-center gap-1 bg-black/60 backdrop-blur px-2 py-1 rounded-2xl border border-white/15 text-xs">
                <button
                    type="button"
                    @click="deviceHeading = (deviceHeading - 15 + 360) % 360"
                    class="p-1 rounded-lg hover:bg-white/10 text-stone-300"
                    title="Putar Kompas ke Kiri (15°)"
                >
                    <span class="material-symbols-outlined text-sm">rotate_left</span>
                </button>
                <span class="text-[10px] text-stone-400 font-mono">Putar AR</span>
                <button
                    type="button"
                    @click="deviceHeading = (deviceHeading + 15) % 360"
                    class="p-1 rounded-lg hover:bg-white/10 text-stone-300"
                    title="Putar Kompas ke Kanan (15°)"
                >
                    <span class="material-symbols-outlined text-sm">rotate_right</span>
                </button>
            </div>
        </div>

        {{-- CAMERA AR FLOATING PINS (Rendered in FOV) --}}
        <div x-show="viewMode === 'camera'" class="relative flex-1 w-full my-4 pointer-events-none">
            {{-- Warning if camera not turned on yet --}}
            <div
                x-show="!cameraActive"
                class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center pointer-events-auto"
            >
                <div class="max-w-md p-6 rounded-3xl bg-black/70 backdrop-blur-lg border border-white/15 shadow-2xl">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-3xl">view_in_ar</span>
                    </div>
                    <h2 class="text-base font-extrabold text-white">Aktifkan Kamera AR</h2>
                    <p class="mt-1 text-xs text-stone-300 leading-relaxed">
                        Arahkan kamera ke pemandangan desa wisata Banyumas untuk melihat pin penunjuk arah homestay dan shelter melayang secara real-time.
                    </p>
                    <div class="mt-4 flex flex-col sm:flex-row items-center justify-center gap-2">
                        <button
                            type="button"
                            @click="startCamera()"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold shadow-md transition active:scale-95"
                        >
                            <span class="material-symbols-outlined text-sm">videocam</span>
                            <span>Buka Kamera Belakang</span>
                        </button>
                        <button
                            type="button"
                            @click="viewMode = 'radar'"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1 px-4 py-2.5 rounded-xl border border-white/20 bg-white/10 text-white text-xs font-semibold hover:bg-white/15 transition"
                        >
                            <span>Gunakan Mode Radar HUD</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Floating AR Markers positioned in 3D camera space --}}
            <template x-for="item in visibleArPins" :key="'ar-pin-' + item.id">
                <div
                    @click="activeShelter = item"
                    class="absolute transform -translate-x-1/2 cursor-pointer pointer-events-auto transition-all duration-300 hover:scale-110 group"
                    :style="`
                        left: ${item.screenX}%;
                        top: ${32 + (item.distance % 5) * 6}%;
                    `"
                >
                    {{-- Marker Card --}}
                    <div class="flex flex-col items-center">
                        <div class="px-3 py-1.5 rounded-2xl bg-gradient-to-r from-[#13391b]/95 via-[#1a4a23]/95 to-[#0d2813]/95 border-2 border-emerald-400/80 shadow-2xl backdrop-blur text-center min-w-[120px] max-w-[180px]">
                            <div class="flex items-center justify-center gap-1 text-[10px] font-bold text-amber-300">
                                <span class="material-symbols-outlined text-xs">cottage</span>
                                <span x-text="item.distanceText"></span>
                            </div>
                            <p class="text-xs font-extrabold text-white truncate mt-0.5" x-text="item.nama"></p>
                            <p class="text-[9px] text-emerald-200/80 truncate" x-text="item.desa"></p>
                        </div>
                        {{-- Arrow stem pointing down --}}
                        <div class="w-2 h-3 bg-emerald-400 clip-arrow shadow-md"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-orange-500 border border-white shadow-lg animate-ping"></div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Bottom Controls Strip: Mini-Radar & Quick Nearest Shelter Bar --}}
        <div class="flex flex-col sm:flex-row items-end sm:items-center justify-between gap-4 pointer-events-auto">
            {{-- Circular 360° Mini-Radar HUD (Pojok Bawah) --}}
            <div class="relative w-28 h-28 sm:w-36 sm:h-36 rounded-full bg-black/75 backdrop-blur-md border border-emerald-500/40 p-1 shadow-2xl overflow-hidden flex items-center justify-center">
                {{-- Rotating Compass Ring --}}
                <div
                    class="absolute inset-1 rounded-full border border-dashed border-emerald-500/30 transition-transform duration-300"
                    :style="`transform: rotate(${-deviceHeading}deg);`"
                >
                    <span class="absolute top-1 left-1/2 -translate-x-1/2 text-[9px] font-bold text-emerald-400">U</span>
                    <span class="absolute bottom-1 left-1/2 -translate-x-1/2 text-[9px] font-bold text-stone-500">S</span>
                    <span class="absolute right-1.5 top-1/2 -translate-y-1/2 text-[9px] font-bold text-stone-500">T</span>
                    <span class="absolute left-1.5 top-1/2 -translate-y-1/2 text-[9px] font-bold text-stone-500">B</span>
                </div>

                {{-- Center Pulse & User Pin --}}
                <div class="w-3 h-3 rounded-full bg-orange-500 border border-white shadow z-10"></div>
                <div class="absolute w-12 h-12 rounded-full border border-emerald-400/30 animate-radar-pulse"></div>

                {{-- Mini-Blips on Mini-Radar --}}
                <template x-for="item in computedShelters.slice(0, 10)" :key="'mini-blip-' + item.id">
                    <div
                        class="absolute w-2 h-2 rounded-full bg-emerald-400 border border-black shadow"
                        :style="`
                            left: ${50 + Math.sin((item.bearing - deviceHeading) * Math.PI / 180) * Math.min(42, (item.distance / Math.max(selectedRadius || 10, 1)) * 38)}%;
                            top: ${50 - Math.cos((item.bearing - deviceHeading) * Math.PI / 180) * Math.min(42, (item.distance / Math.max(selectedRadius || 10, 1)) * 38)}%;
                        `"
                    ></div>
                </template>
            </div>

            {{-- Quick Nearest Shelter Banner --}}
            <template x-if="computedShelters.length > 0">
                <div class="w-full sm:max-w-md p-3 sm:p-4 rounded-3xl bg-black/80 backdrop-blur-md border border-white/15 shadow-xl flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 text-[11px] text-amber-300 font-bold uppercase tracking-wider">
                            <span class="material-symbols-outlined text-xs">location_searching</span>
                            <span>Shelter Terdekat:</span>
                            <span class="text-white font-mono" x-text="computedShelters[0].distanceText"></span>
                        </div>
                        <h4 class="text-xs sm:text-sm font-extrabold text-white truncate mt-0.5" x-text="computedShelters[0].nama"></h4>
                        <p class="text-[11px] text-stone-400 truncate" x-text="computedShelters[0].alamat"></p>
                    </div>

                    <div class="flex items-center gap-1.5 shrink-0">
                        <button
                            type="button"
                            @click="activeShelter = computedShelters[0]"
                            class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold shadow transition"
                        >
                            Detail
                        </button>
                        <a
                            :href="computedShelters[0].maps_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-orange-400 transition"
                            title="Buka Rute Google Maps"
                        >
                            <span class="material-symbols-outlined text-sm">directions</span>
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </main>

    {{-- Detail Modal Pop-Up for Selected Shelter --}}
    <div
        x-show="activeShelter !== null"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
    >
        <div
            @click.away="activeShelter = null"
            class="w-full max-w-lg rounded-3xl bg-stone-900 border border-white/20 p-6 sm:p-7 shadow-2xl text-white relative animate-in fade-in zoom-in-95 duration-200"
        >
            {{-- Close Button --}}
            <button
                type="button"
                @click="activeShelter = null"
                class="absolute top-4 right-4 p-2 rounded-xl bg-white/10 hover:bg-white/20 text-stone-300 transition"
            >
                <span class="material-symbols-outlined text-base">close</span>
            </button>

            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">cottage</span>
                </div>
                <div class="min-w-0 pr-6">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-orange-600 text-white uppercase tracking-wider" x-text="activeShelter?.kategori"></span>
                        <span class="text-xs font-bold text-amber-300 font-mono" x-text="activeShelter?.distanceText"></span>
                    </div>
                    <h3 class="text-lg font-extrabold text-white mt-1 leading-snug" x-text="activeShelter?.nama"></h3>
                    <p class="text-xs text-stone-400 mt-0.5" x-text="`Kecamatan ${activeShelter?.kecamatan}, Desa ${activeShelter?.desa}`"></p>
                </div>
            </div>

            {{-- Address & Landmark Box --}}
            <div class="mt-4 p-3.5 rounded-2xl bg-stone-800/80 border border-stone-700/80 text-xs text-stone-300 space-y-1.5">
                <p class="flex items-start gap-1.5">
                    <span class="material-symbols-outlined text-emerald-400 text-sm mt-0.5 shrink-0">location_on</span>
                    <span x-text="activeShelter?.alamat"></span>
                </p>
                <p class="flex items-center gap-1.5 text-stone-400 text-[11px] font-mono">
                    <span class="material-symbols-outlined text-stone-500 text-xs shrink-0">explore</span>
                    <span x-text="`Koordinat: ${activeShelter?.lat.toFixed(6)}, ${activeShelter?.lng.toFixed(6)} • Arah ${activeShelter?.bearing}°`"></span>
                </p>
            </div>

            {{-- Actions: Google Maps & WhatsApp --}}
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a
                    :href="activeShelter?.maps_url"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold shadow transition active:scale-95"
                >
                    <span class="material-symbols-outlined text-base">directions</span>
                    <span>Buka Rute Google Maps</span>
                </a>

                <template x-if="activeShelter?.wa_url">
                    <a
                        :href="activeShelter?.wa_url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold shadow transition active:scale-95"
                    >
                        <span class="material-symbols-outlined text-base">chat</span>
                        <span>Hubungi via WhatsApp</span>
                    </a>
                </template>
                <template x-if="!activeShelter?.wa_url">
                    <div class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-stone-800 text-stone-400 text-xs font-semibold">
                        <span class="material-symbols-outlined text-base">phone</span>
                        <span x-text="activeShelter?.no_hp || 'Kontak Pokdarwis Desa'"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Sliding Bottom Drawer (Full List of Shelters by Distance) --}}
    <div
        x-show="showDrawer"
        x-cloak
        class="fixed inset-x-0 bottom-0 z-40 max-h-[75vh] bg-stone-900 border-t border-white/20 rounded-t-3xl shadow-2xl p-5 sm:p-6 overflow-y-auto"
    >
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-500">near_me</span>
                        <span>Direktori Shelter & Homestay Terdekat</span>
                    </h3>
                    <p class="text-xs text-stone-400" x-text="`Ditemukan ${computedShelters.length} penginapan di sekitar koordinat Anda.`"></p>
                </div>
                <button type="button" @click="showDrawer = false" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-stone-300">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <template x-for="item in computedShelters" :key="'drawer-item-' + item.id">
                    <div
                        @click="activeShelter = item; showDrawer = false"
                        class="p-4 rounded-2xl bg-stone-800/80 border border-stone-700/80 hover:border-emerald-400 transition cursor-pointer flex flex-col justify-between gap-3 group"
                    >
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/10 text-emerald-300 uppercase tracking-wider" x-text="item.kategori"></span>
                                <span class="text-xs font-extrabold font-mono text-amber-300" x-text="item.distanceText"></span>
                            </div>
                            <h4 class="text-sm font-bold text-white group-hover:text-emerald-300 transition mt-1.5" x-text="item.nama"></h4>
                            <p class="text-xs text-stone-400 mt-0.5 line-clamp-1" x-text="item.alamat"></p>
                        </div>

                        <div class="flex items-center justify-between text-xs pt-2 border-t border-stone-700/60 text-stone-400">
                            <span class="font-mono text-[11px]" x-text="`Arah ${item.bearing}°`"></span>
                            <span class="text-emerald-400 font-bold group-hover:underline flex items-center gap-1">
                                <span>Lihat Rute</span>
                                <span class="material-symbols-outlined text-xs">arrow_forward</span>
                            </span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
