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
        label="Nama Paket Wisata"
        :value="$isEdit ? $paket->nama_paket : null"
        placeholder="Contoh: Trekking Sawah Terasering & Sunrise"
        required
    />

    <x-form.textarea
        name="deskripsi"
        label="Deskripsi Aktivitas"
        :value="$isEdit ? $paket->deskripsi : null"
        rows="4"
        placeholder="Jelaskan rute perjalanan, pengalaman lokal yang didapat, pemandu warga, atau suguhan kuliner khas."
        required
    />

    <div class="grid gap-4 sm:grid-cols-2">
        <x-form.input
            name="harga"
            type="number"
            label="Tarif / Peserta (Rp)"
            :value="$isEdit ? (int) $paket->harga : null"
            min="0"
            step="5000"
            placeholder="75000"
            required
        />

        <x-form.input
            name="durasi"
            label="Estimasi Durasi"
            :value="$isEdit ? $paket->durasi : null"
            placeholder="Contoh: 2 - 3 Jam"
            required
        />
    </div>

    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 pt-2">
        <a
            href="{{ route('pemilik.homestay.paket.index', $isEdit ? $paket->homestay : $homestay) }}"
            class="inline-flex items-center justify-center px-4 py-2.5 rounded-full border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold shadow-2xs transition-all"
        >
            <span>Batal</span>
        </a>
        <button
            type="submit"
            class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-full bg-[#13391b] hover:bg-[#0e2c14] text-white text-xs font-bold shadow-xs hover:shadow-md transition-all cursor-pointer"
        >
            <span class="material-symbols-outlined text-sm">check</span>
            <span>{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Paket Wisata' }}</span>
        </button>
    </div>
</form>
