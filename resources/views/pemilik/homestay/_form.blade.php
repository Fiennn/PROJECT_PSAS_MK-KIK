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

    <x-card>
        <h2 class="font-display text-xl font-semibold text-forest-950">Informasi homestay</h2>
        <p class="mt-1 text-sm text-stone-500">Data ini akan tampil di halaman publik setelah disetujui admin desa wisata.</p>

        <div class="mt-5 space-y-5">
            <x-form.input
                name="nama_homestay"
                label="Nama homestay"
                :value="$isEdit ? $homestay->nama_homestay : null"
                placeholder="contoh: Homestay Rumah Kayu"
                required
            />

            <x-form.input
                name="alamat"
                label="Alamat lengkap"
                :value="$isEdit ? $homestay->alamat : null"
                placeholder="Nama desa, kecamatan, kabupaten, provinsi"
                required
            />

            <x-form.textarea
                name="deskripsi"
                label="Deskripsi homestay"
                :value="$isEdit ? $homestay->deskripsi : null"
                rows="6"
                placeholder="Ceritakan suasana rumah, pemandangan sekitar, dan pengalaman yang ditawarkan kepada tamu."
                required
            />
        </div>
    </x-card>

    <x-card>
        <h2 class="font-display text-xl font-semibold text-forest-950">Fasilitas</h2>
        <p class="mt-1 text-sm text-stone-500">Pilih fasilitas yang tersedia untuk tamu.</p>

        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            @foreach ($fasilitas as $facility)
                <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-sand-200 px-4 py-3 text-sm text-stone-700 transition hover:border-forest-300 hover:bg-forest-50/40">
                    <input
                        type="checkbox"
                        name="fasilitas[]"
                        value="{{ $facility->id_fasilitas }}"
                        @checked(in_array($facility->id_fasilitas, $selected, true))
                        class="h-4 w-4 rounded border-sand-300 text-forest-700 focus:ring-forest-400"
                    >
                    {{ $facility->nama_fasilitas }}
                </label>
            @endforeach
        </div>
        @error('fasilitas.*')
            <p class="error-text">{{ $message }}</p>
        @enderror
    </x-card>

    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
        <x-button :href="route('pemilik.homestay.index')" variant="secondary">Batal</x-button>
        <x-button type="submit">{{ $isEdit ? 'Simpan Perubahan' : 'Ajukan Homestay' }}</x-button>
    </div>
</form>
