@extends('layouts.dashboard')

@section('title', 'Ubah Data Homestay')
@section('page_title', 'Ubah Data: '.$homestay->nama_homestay)
@section('page_description', 'Perbarui informasi profil, alamat, deskripsi, dan fasilitas homestay Anda.')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a
            href="{{ route('pemilik.homestay.kamar.index', $homestay) }}"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs transition-all"
        >
            <span class="material-symbols-outlined text-sm">bed</span>
            <span>Kelola Kamar ({{ $homestay->kamar()->count() }})</span>
        </a>
        <a
            href="{{ route('pemilik.homestay.index') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
        >
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali</span>
        </a>
    </div>
@endsection

@section('dashboard')
    @if ($homestay->status_verifikasi->value === 'ditolak')
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-xs text-red-800 flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600 text-lg shrink-0 mt-0.5">warning</span>
            <div>
                <p class="font-bold">Pengajuan Homestay Ini Memerlukan Perbaikan</p>
                <p class="mt-0.5 leading-relaxed">
                    Admin Pokdarwis sebelumnya menolak pengajuan ini karena data belum lengkap. Setelah Anda menyimpan perbaikan data di bawah, status verifikasi akan otomatis kembali diajukan menjadi <span class="font-bold">Menunggu Verifikasi</span>.
                </p>
            </div>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.45fr_0.55fr] lg:items-start">
        <div>
            @include('pemilik.homestay._form', [
                'homestay' => $homestay,
                'fasilitas' => $fasilitas,
                'selectedFacilities' => $selectedFacilities,
            ])
        </div>

        <div class="space-y-4 lg:sticky lg:top-28">
            <!-- Status Card -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
                <p class="text-xs font-bold uppercase tracking-wider text-stone-500 mb-3">Status Verifikasi Pokdarwis</p>
                <div class="mb-3">
                    <x-badge :status="$homestay->status_verifikasi->value" />
                </div>
                <p class="text-xs text-stone-600 leading-relaxed">
                    @if ($homestay->status_verifikasi->value === 'disetujui')
                        Homestay ini telah terverifikasi resmi dan tampil aktif di katalog publik desa wisata untuk reservasi wisatawan.
                    @elseif ($homestay->status_verifikasi->value === 'menunggu')
                        Pengajuan sedang dalam antrean peninjauan oleh tim pengelola Pokdarwis desa wisata.
                    @else
                        Perbaiki data profil homestay di samping lalu klik simpan untuk mengajukan verifikasi ulang.
                    @endif
                </p>
            </div>

            <!-- Quick Sub-Modules Card -->
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xs">
                <h3 class="text-sm font-extrabold text-stone-900 mb-3">Kelola Ruangan & Aktivitas</h3>
                <div class="space-y-2">
                    <a
                        href="{{ route('pemilik.homestay.kamar.index', $homestay) }}"
                        class="flex items-center justify-between p-3 rounded-2xl border border-stone-200 hover:border-emerald-300 hover:bg-stone-50 text-xs font-bold text-stone-800 transition-all"
                    >
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base text-[#13391b]">bed</span>
                            <span>Kamar & Tarif Per Malam</span>
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[11px] font-semibold">
                            {{ $homestay->kamar()->count() }} kamar
                        </span>
                    </a>

                    <a
                        href="{{ route('pemilik.homestay.paket.index', $homestay) }}"
                        class="flex items-center justify-between p-3 rounded-2xl border border-stone-200 hover:border-amber-300 hover:bg-stone-50 text-xs font-bold text-stone-800 transition-all"
                    >
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base text-[#ea580c]">hiking</span>
                            <span>Paket Wisata Lokal</span>
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 text-[11px] font-semibold">
                            {{ $homestay->paket()->count() }} paket
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
