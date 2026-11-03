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
        label="Nama kamar"
        :value="$isEdit ? $kamar->nama_kamar : null"
        placeholder="contoh: Kamar Melati"
        required
    />

    <div class="grid gap-4 sm:grid-cols-2">
        <x-form.input
            name="kapasitas"
            type="number"
            label="Kapasitas tamu"
            :value="$isEdit ? $kamar->kapasitas : 2"
            min="1"
            max="20"
            required
        />

        <x-form.input
            name="harga_per_malam"
            type="number"
            label="Harga per malam (Rp)"
            :value="$isEdit ? (int) $kamar->harga_per_malam : null"
            min="0"
            step="5000"
            placeholder="250000"
            required
        />
    </div>

    <x-form.select name="status" label="Status kamar" required>
        <option value="tersedia" @selected(old('status', $isEdit ? $kamar->status->value : 'tersedia') === 'tersedia')>Tersedia</option>
        <option value="nonaktif" @selected(old('status', $isEdit ? $kamar->status->value : 'tersedia') === 'nonaktif')>Nonaktif</option>
    </x-form.select>

    <div class="flex flex-col gap-2 pt-2 sm:flex-row sm:justify-end">
        <x-button :href="route('pemilik.homestay.kamar.index', $isEdit ? $kamar->homestay : $homestay)" variant="secondary">Batal</x-button>
        <x-button type="submit">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Kamar' }}</x-button>
    </div>
</form>
