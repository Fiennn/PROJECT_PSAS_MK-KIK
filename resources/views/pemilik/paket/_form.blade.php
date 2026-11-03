@php
    $isEdit = isset($paket) && $paket;
    $action = $isEdit ? route('pemilik.paket.update', $paket) : route('pemilik.homestay.paket.store', $homestay);
    $method = $isEdit ? 'PATCH' : 'POST';
@endphp

<form method="POST" action="{{ $action }}" class="space-y-4">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-form.input
        name="nama_paket"
        label="Nama paket"
        :value="$isEdit ? $paket->nama_paket : null"
        placeholder="contoh: Trekking Terasering"
        required
    />

    <x-form.textarea
        name="deskripsi"
        label="Deskripsi paket"
        :value="$isEdit ? $paket->deskripsi : null"
        rows="4"
        placeholder="Jelaskan aktivitas, lokasi, dan apa yang didapat peserta."
        required
    />

    <div class="grid gap-4 sm:grid-cols-2">
        <x-form.input
            name="harga"
            type="number"
            label="Harga per peserta (Rp)"
            :value="$isEdit ? (int) $paket->harga : null"
            min="0"
            step="5000"
            placeholder="75000"
            required
        />

        <x-form.input
            name="durasi"
            label="Durasi"
            :value="$isEdit ? $paket->durasi : null"
            placeholder="contoh: 2 jam"
            required
        />
    </div>

    <div class="flex flex-col gap-2 pt-2 sm:flex-row sm:justify-end">
        <x-button :href="route('pemilik.homestay.paket.index', $isEdit ? $paket->homestay : $homestay)" variant="secondary">Batal</x-button>
        <x-button type="submit">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Paket' }}</x-button>
    </div>
</form>
