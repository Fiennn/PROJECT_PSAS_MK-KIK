@php
    $isEdit = isset($kamar) && $kamar;
    $action = $isEdit ? route('pemilik.kamar.update', $kamar) : route('pemilik.homestay.kamar.store', $homestay);
    $method = $isEdit ? 'PATCH' : 'POST';
@endphp

<form method="POST" action="{{ $action }}" class="space-y-4">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-form.input
        name="nama_kamar"
        label="Nama Kamar"
        :value="$isEdit ? $kamar->nama_kamar : null"
        placeholder="Contoh: Kamar Melati, Paviliun Kayu"
        required
    />

    <div class="grid gap-4 sm:grid-cols-2">
        <x-form.input
            name="kapasitas"
            type="number"
            label="Kapasitas Tamu (Orang)"
            :value="$isEdit ? $kamar->kapasitas : 2"
            min="1"
            max="30"
            required
        />

        <x-form.input
            name="harga_per_malam"
            type="number"
            label="Tarif / Malam (Rp)"
            :value="$isEdit ? (int) $kamar->harga_per_malam : null"
            min="0"
            step="5000"
            placeholder="250000"
            required
        />
    </div>

    <x-form.select name="status" label="Status Ketersediaan" required>
        <option value="tersedia" @selected(old('status', $isEdit ? $kamar->status->value : 'tersedia') === 'tersedia')>Tersedia (Bisa Dipesan Wisatawan)</option>
        <option value="nonaktif" @selected(old('status', $isEdit ? $kamar->status->value : 'tersedia') === 'nonaktif')>Nonaktif (Sedang Renovasi / Tidak Disewakan)</option>
    </x-form.select>

    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 pt-2">
        <a
            href="{{ route('pemilik.homestay.kamar.index', $isEdit ? $kamar->homestay : $homestay) }}"
            class="inline-flex items-center justify-center px-4 py-2.5 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
        >
            <span>Batal</span>
        </a>
        <button
            type="submit"
            class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs hover:shadow-md transition-all cursor-pointer"
        >
            <span class="material-symbols-outlined text-sm">check</span>
            <span>{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Kamar' }}</span>
        </button>
    </div>
</form>
