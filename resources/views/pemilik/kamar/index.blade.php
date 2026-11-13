@extends('layouts.dashboard')

@section('title', 'Kamar & Kalender Okupansi: '.$homestay->nama_homestay)
@section('page_title', 'Kelola Kamar & Kalender Okupansi')
@section('page_description', 'Atur daftar kamar, tarif menginap, serta pantau jadwal ketersediaan kamar secara visual untuk '.$homestay->nama_homestay.'.')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a
            href="{{ route('pemilik.homestay.paket.index', $homestay) }}"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
        >
            <span class="material-symbols-outlined text-sm text-[#ea580c]">hiking</span>
            <span>Paket Wisata ({{ $homestay->paket()->count() }})</span>
        </a>
        <a
            href="{{ route('pemilik.homestay.index') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
        >
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali ke Homestay</span>
        </a>
    </div>
@endsection

@section('dashboard')
<div x-data="{
    tab: 'kamar',
    selectedRoom: 'all',
    currentYear: {{ date('Y') }},
    currentMonth: {{ date('n') - 1 }},
    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
    bookings: {{ Js::from($roomBookings) }},
    activeDayDetail: null,
    activeDayDate: '',

    prevMonth() {
        if (this.currentMonth === 0) {
            this.currentMonth = 11;
            this.currentYear--;
        } else {
            this.currentMonth--;
        }
        this.activeDayDetail = null;
    },
    nextMonth() {
        if (this.currentMonth === 11) {
            this.currentMonth = 0;
            this.currentYear++;
        } else {
            this.currentMonth++;
        }
        this.activeDayDetail = null;
    },
    daysInMonth() {
        return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
    },
    firstDayOfWeek() {
        let day = new Date(this.currentYear, this.currentMonth, 1).getDay();
        return (day === 0) ? 6 : day - 1;
    },
    formatDate(day) {
        let m = String(this.currentMonth + 1).padStart(2, '0');
        let d = String(day).padStart(2, '0');
        return `${this.currentYear}-${m}-${d}`;
    },
    getBookingsForDate(dateStr) {
        return this.bookings.filter(b => {
            if (this.selectedRoom !== 'all' && b.id_kamar != this.selectedRoom) {
                return false;
            }
            return dateStr >= b.checkin && dateStr < b.checkout;
        });
    },
    openDay(day) {
        let dateStr = this.formatDate(day);
        let items = this.getBookingsForDate(dateStr);
        this.activeDayDate = `${day} ${this.monthNames[this.currentMonth]} ${this.currentYear}`;
        this.activeDayDetail = items;
    }
}">
    <!-- Context Banner Homestay -->
    <div class="mb-6 rounded-2xl border border-stone-200 bg-white p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-[#13391b]">
                <span class="material-symbols-outlined text-2xl">cottage</span>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-base font-extrabold text-stone-900">{{ $homestay->nama_homestay }}</h2>
                    <x-badge :status="$homestay->status_verifikasi->value" />
                </div>
                <p class="mt-0.5 text-xs text-stone-500">{{ $homestay->alamat }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-stone-100 text-stone-700 text-xs font-bold">
                <span class="material-symbols-outlined text-sm text-stone-500">bed</span>
                <span>{{ $kamar->count() }} Kamar Didaftarkan</span>
            </span>
        </div>
    </div>

    <!-- Navigation Tabs: Kamar vs Kalender Okupansi -->
    <div class="flex items-center gap-2 border-b border-stone-200 pb-3 mb-6">
        <button
            type="button"
            @click="tab = 'kamar'"
            :class="tab === 'kamar' ? 'bg-[#13391b] text-white shadow-xs' : 'bg-white text-stone-700 border border-stone-200 hover:bg-stone-50'"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold transition-all cursor-pointer"
        >
            <span class="material-symbols-outlined text-base">bed</span>
            <span>Daftar Kamar ({{ $kamar->count() }})</span>
        </button>

        <button
            type="button"
            @click="tab = 'kalender'"
            :class="tab === 'kalender' ? 'bg-[#13391b] text-white shadow-xs' : 'bg-white text-stone-700 border border-stone-200 hover:bg-stone-50'"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-bold transition-all cursor-pointer"
        >
            <span class="material-symbols-outlined text-base text-amber-300">calendar_month</span>
            <span>Kalender Okupansi Visual</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="tab === 'kalender' ? 'bg-white/20 text-white' : 'bg-orange-100 text-[#ea580c]'">
                {{ $roomBookings->count() }} Reservasi
            </span>
        </button>
    </div>

    <!-- TAB 1: DAFTAR KAMAR -->
    <div x-show="tab === 'kamar'" class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <div class="space-y-4">
            @if ($kamar->isEmpty())
                <div class="rounded-3xl border border-dashed border-stone-300 bg-white p-10 text-center shadow-xs">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-[#13391b] mb-3">
                        <span class="material-symbols-outlined text-3xl">bed</span>
                    </div>
                    <h3 class="text-base font-bold text-stone-900">Belum Ada Kamar yang Ditambahkan</h3>
                    <p class="mt-1 text-xs text-stone-500 max-w-sm mx-auto leading-relaxed">
                        Tambahkan kamar pertama menggunakan formulir di samping agar homestay Anda dapat dipesan oleh wisatawan desa.
                    </p>
                </div>
            @else
                @foreach ($kamar as $room)
                    <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-xs hover:border-emerald-300 transition-all flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-extrabold text-stone-900 truncate">{{ $room->nama_kamar }}</h3>
                                <x-badge :status="$room->status->value" />
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-stone-500">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-stone-100 text-stone-700 font-semibold">
                                    <span class="material-symbols-outlined text-xs">group</span>
                                    <span>Kapasitas: {{ $room->kapasitas }} Orang</span>
                                </span>
                                <span>&bull;</span>
                                <span class="text-xs text-stone-400">ID #{{ $room->id_kamar }}</span>
                            </div>

                            <p class="mt-3 text-lg sm:text-xl font-extrabold text-[#13391b]">
                                {{ rupiah($room->harga_per_malam) }}
                                <span class="text-xs font-normal text-stone-500">/ malam</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 pt-3 sm:pt-0 border-t sm:border-0 border-stone-100">
                            <a
                                href="{{ route('pemilik.kamar.edit', $room) }}"
                                class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
                            >
                                <span class="material-symbols-outlined text-xs">edit</span>
                                <span>Ubah</span>
                            </a>

                            @can('delete', $room)
                                <form
                                    method="POST"
                                    action="{{ route('pemilik.kamar.destroy', $room) }}"
                                    onsubmit="return confirm('Hapus kamar {{ $room->nama_kamar }}? Kamar hanya dapat dihapus jika belum memiliki riwayat reservasi.');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-2 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 transition-all cursor-pointer"
                                        title="Hapus Kamar"
                                    >
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Formulir Tambah Kamar Baru (Sticky) -->
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs lg:sticky lg:top-28">
            <div class="flex items-center gap-2 pb-3 border-b border-stone-100 mb-4">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-[#13391b]">
                    <span class="material-symbols-outlined text-base">add</span>
                </span>
                <div>
                    <h3 class="text-sm font-extrabold text-stone-900">Tambah Kamar Baru</h3>
                    <p class="text-[11px] text-stone-500">Kamar langsung aktif jika statusnya Tersedia.</p>
                </div>
            </div>

            @include('pemilik.kamar._form', ['kamar' => null, 'homestay' => $homestay])
        </div>
    </div>

    <!-- TAB 2: KALENDER OKUPANSI VISUAL INTERAKTIF -->
    <div x-show="tab === 'kalender'" x-cloak class="space-y-6">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
            {{-- Toolbar: Room Filter & Month Navigation --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-stone-100">
                <div>
                    <h3 class="text-lg font-extrabold text-stone-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-600">event_available</span>
                        <span>Jadwal Okupansi & Ketersediaan Kamar</span>
                    </h3>
                    <p class="text-xs text-stone-500 mt-1">
                        Pilih kamar untuk menyaring jadwal atau lihat seluruh kamar secara bersamaan.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    {{-- Filter Dropdown Kamar --}}
                    <div class="flex items-center gap-2">
                        <label for="room-select" class="text-xs font-semibold text-stone-500">Kamar:</label>
                        <select
                            id="room-select"
                            x-model="selectedRoom"
                            class="rounded-xl border border-stone-300 bg-stone-50 px-3 py-1.5 text-xs font-bold text-stone-800 focus:border-emerald-600 focus:outline-none"
                        >
                            <option value="all">Semua Kamar ({{ $kamar->count() }})</option>
                            @foreach ($kamar as $room)
                                <option value="{{ $room->id_kamar }}">{{ $room->nama_kamar }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Month Navigator Buttons --}}
                    <div class="flex items-center rounded-2xl border border-stone-200 bg-stone-50 p-1">
                        <button
                            type="button"
                            @click="prevMonth()"
                            class="p-1.5 rounded-xl hover:bg-white text-stone-600 transition"
                            title="Bulan Sebelumnya"
                        >
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </button>
                        <span class="px-3 text-xs font-extrabold text-stone-800 font-mono min-w-[130px] text-center"
                              x-text="`${monthNames[currentMonth]} ${currentYear}`"></span>
                        <button
                            type="button"
                            @click="nextMonth()"
                            class="p-1.5 rounded-xl hover:bg-white text-stone-600 transition"
                            title="Bulan Berikutnya"
                        >
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Calendar Grid --}}
            <div class="mt-6">
                {{-- Day Headers (Sen - Min) --}}
                <div class="grid grid-cols-7 gap-2 text-center text-[11px] font-extrabold uppercase tracking-wider text-stone-500 pb-3 border-b border-stone-100">
                    <div>Sen</div>
                    <div>Sel</div>
                    <div>Rab</div>
                    <div>Kam</div>
                    <div>Jum</div>
                    <div class="text-orange-600">Sab</div>
                    <div class="text-rose-600">Min</div>
                </div>

                {{-- Day Cells --}}
                <div class="grid grid-cols-7 gap-2 mt-3">
                    {{-- Blanks for days before 1st of month --}}
                    <template x-for="blank in firstDayOfWeek()" :key="'b-' + blank">
                        <div class="min-h-[85px] sm:min-h-[105px] rounded-2xl bg-stone-50/50 border border-transparent"></div>
                    </template>

                    {{-- Days of current month --}}
                    <template x-for="day in daysInMonth()" :key="'d-' + day">
                        <div
                            @click="openDay(day)"
                            :class="{
                                'bg-emerald-50/40 border-emerald-200/80 hover:bg-emerald-50': getBookingsForDate(formatDate(day)).length === 0,
                                'bg-rose-50/80 border-rose-200 hover:bg-rose-100/70': getBookingsForDate(formatDate(day)).length > 0
                            }"
                            class="min-h-[85px] sm:min-h-[105px] rounded-2xl p-2 sm:p-2.5 border transition cursor-pointer flex flex-col justify-between group"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-stone-700 group-hover:text-stone-900" x-text="day"></span>
                                <template x-if="getBookingsForDate(formatDate(day)).length === 0">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                </template>
                                <template x-if="getBookingsForDate(formatDate(day)).length > 0">
                                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                                </template>
                            </div>

                            <div class="mt-1 space-y-1">
                                <template x-if="getBookingsForDate(formatDate(day)).length === 0">
                                    <p class="text-[10px] text-emerald-800 font-medium">Tersedia</p>
                                </template>

                                <template x-for="item in getBookingsForDate(formatDate(day))" :key="item.id_booking">
                                    <div class="rounded-lg bg-rose-100 text-rose-900 px-1.5 py-0.5 text-[10px] font-bold truncate"
                                         :title="`${item.tamu} (${item.nama_kamar})`">
                                        <span x-text="item.tamu"></span>
                                    </div>
                                </template>
                            </div>

                            <p class="text-[9px] text-stone-400 text-right mt-1" x-text="getBookingsForDate(formatDate(day)).length > 0 ? 'Klik detail' : ''"></p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Detail Drawer / Alert when day is clicked --}}
            <div x-show="activeDayDetail !== null" x-cloak class="mt-6 rounded-2xl border border-stone-200 bg-stone-50 p-5 transition">
                <div class="flex items-center justify-between pb-3 border-b border-stone-200 mb-3">
                    <h4 class="text-sm font-extrabold text-stone-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-base text-emerald-800">event</span>
                        <span>Detail Tanggal: <span x-text="activeDayDate"></span></span>
                    </h4>
                    <button type="button" @click="activeDayDetail = null" class="text-xs font-bold text-stone-500 hover:text-stone-800">&times; Tutup</button>
                </div>

                <template x-if="activeDayDetail.length === 0">
                    <p class="text-xs text-stone-600">Tidak ada tamu yang menginap di tanggal ini. Seluruh kamar siap dipesan untuk tamu baru atau walk-in.</p>
                </template>

                <template x-if="activeDayDetail.length > 0">
                    <div class="space-y-3">
                        <template x-for="b in activeDayDetail" :key="b.id_booking">
                            <div class="rounded-xl bg-white p-3 border border-stone-200 shadow-2xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs text-stone-900" x-text="b.tamu"></span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-amber-100 text-amber-900" x-text="b.kode_tiket"></span>
                                    </div>
                                    <p class="text-xs text-stone-500 mt-0.5">
                                        Kamar: <span class="font-semibold text-stone-800" x-text="b.nama_kamar"></span> &bull;
                                        Menginap: <span class="font-mono" x-text="b.checkin"></span> s/d <span class="font-mono" x-text="b.checkout"></span>
                                    </p>
                                </div>
                                <a :href="`/pemilik/booking/${b.id_booking}`"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-[#13391b] text-white text-xs font-bold hover:bg-[#0d2813] transition self-start sm:self-auto">
                                    <span>Buka Reservasi</span>
                                    <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                </a>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Legend Footer --}}
            <div class="mt-8 pt-5 border-t border-stone-100 flex flex-wrap items-center justify-between gap-4 text-xs text-stone-600">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="font-medium text-stone-700">Tersedia (Kamar Kosong)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                        <span class="font-medium text-stone-700">Terisi (Dipesan Tamu)</span>
                    </div>
                </div>
                <p class="text-[11px] text-stone-400">Kalender real-time terintegrasi sistem proteksi anti-double booking.</p>
            </div>
        </div>
    </div>
</div>
@endsection
