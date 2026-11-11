@php
    $isEdit = isset($homestay) && $homestay;
    $action = $isEdit ? route('pemilik.homestay.update', $homestay) : route('pemilik.homestay.store');
    $method = $isEdit ? 'PATCH' : 'POST';
    $selected = old('fasilitas', $selectedFacilities ?? []);
    $selected = array_map('intval', (array) $selected);
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <!-- Informasi Utama Homestay -->
    <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
        <div class="flex items-center gap-2 pb-4 border-b border-stone-100 mb-6">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-[#13391b]">
                <span class="material-symbols-outlined text-lg">home_work</span>
            </span>
            <div>
                <h2 class="text-lg font-extrabold text-stone-900">Informasi Unit Homestay</h2>
                <p class="text-xs text-stone-500">Data ini akan tampil di katalog publik desa wisata setelah disetujui Pokdarwis.</p>
            </div>
        </div>

        <div class="space-y-5">
            <x-form.input
                name="nama_homestay"
                label="Nama Homestay"
                :value="$isEdit ? $homestay->nama_homestay : null"
                placeholder="Contoh: Homestay Rumah Kayu Asri"
                required
            />

            <x-form.input
                name="alamat"
                label="Alamat Lengkap Homestay"
                :value="$isEdit ? $homestay->alamat : null"
                placeholder="Dusun, RT/RW, Desa Wisata, Kecamatan, Kabupaten"
                required
            />

            <x-form.textarea
                name="deskripsi"
                label="Deskripsi & Pengalaman Menginap"
                :value="$isEdit ? $homestay->deskripsi : null"
                rows="6"
                placeholder="Ceritakan keistimewaan homestay Anda: pemandangan sawah terasering, kesejukan udara lereng gunung, santapan kuliner rumahan, dan keramahan keluarga desa yang menyambut."
                required
            />
        </div>
    </div>

    <!-- Pilihan Fasilitas -->
    <div class="rounded-3xl border border-stone-200 bg-white p-6 sm:p-8 shadow-xs">
        <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-6">
            <div class="flex items-center gap-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-[#ea580c]">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                </span>
                <div>
                    <h2 class="text-lg font-extrabold text-stone-900">Fasilitas yang Disediakan</h2>
                    <p class="text-xs text-stone-500">Pilih fasilitas yang dapat dinikmati wisatawan selama menginap.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3">
            @foreach ($fasilitas as $facility)
                <label class="relative flex cursor-pointer items-center gap-3 rounded-2xl border border-stone-200 p-3.5 text-xs font-semibold text-stone-800 transition hover:border-emerald-400 hover:bg-emerald-50/30">
                    <input
                        type="checkbox"
                        name="fasilitas[]"
                        value="{{ $facility->id_fasilitas }}"
                        @checked(in_array($facility->id_fasilitas, $selected, true))
                        class="h-4 w-4 rounded border-stone-300 text-[#13391b] focus:ring-[#13391b]/20"
                    >
                    <span class="select-none">{{ $facility->nama_fasilitas }}</span>
                </label>
            @endforeach
        </div>
        @error('fasilitas.*')
            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tombol Simpan & Batal -->
    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-2">
        <a
            href="{{ route('pemilik.homestay.index') }}"
            class="inline-flex items-center justify-center px-6 py-3 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
        >
            <span>Batal</span>
        </a>
        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 px-7 py-3 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs hover:shadow-md transition-all cursor-pointer"
        >
            <span class="material-symbols-outlined text-base">check</span>
            <span>{{ $isEdit ? 'Simpan Perubahan Homestay' : 'Ajukan Homestay ke Pokdarwis' }}</span>
        </button>
    </div>
</form>
