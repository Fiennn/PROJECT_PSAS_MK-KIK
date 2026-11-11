@extends('layouts.dashboard')

@section('title', 'Tambah Homestay Baru')
@section('page_title', 'Tambah Homestay Baru')
@section('page_description', 'Daftarkan homestay baru langsung dari panel admin Pokdarwis dan tetapkan pemilik warganya.')

@section('page_actions')
    <x-button :href="route('admin.homestay.index')" variant="secondary">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Kembali ke Daftar</span>
    </x-button>
@endsection

@section('dashboard')
    <div class="max-w-4xl mx-auto">
        <x-card>
            <form method="POST" action="{{ route('admin.homestay.store') }}" class="space-y-6">
                @csrf

                <!-- Informasi Dasar & Kepemilikan -->
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-[#13391b] border-b border-stone-200 pb-2 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">domain</span>
                        <span>Informasi Homestay & Warga Pengelola</span>
                    </h3>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <!-- Pemilik Homestay -->
                        <div class="sm:col-span-2">
                            <label for="id_pemilik" class="label">Pemilik Homestay (Warga Binaan Pokdarwis) <span class="text-red-500">*</span></label>
                            <select id="id_pemilik" name="id_pemilik" required class="field">
                                <option value="">-- Pilih Akun Pemilik Warga --</option>
                                @foreach ($pemilikList as $p)
                                    <option value="{{ $p->id_pemilik }}" @selected(old('id_pemilik') == $p->id_pemilik)>
                                        {{ $p->nama_pemilik }} — HP: {{ $p->no_hp }} ({{ $p->user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pemilik')
                                <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Homestay -->
                        <div>
                            <x-form.input
                                name="nama_homestay"
                                label="Nama Homestay"
                                placeholder="contoh: Homestay Kayu Asri Baturraden"
                                required
                            />
                        </div>

                        <!-- Status Verifikasi -->
                        <div>
                            <x-form.select name="status_verifikasi" label="Status Publikasi Katalog" required>
                                <option value="disetujui" @selected(old('status_verifikasi', 'disetujui') === 'disetujui')>Disetujui (Langsung Tampil di Katalog)</option>
                                <option value="menunggu" @selected(old('status_verifikasi') === 'menunggu')>Menunggu Verifikasi</option>
                                <option value="ditolak" @selected(old('status_verifikasi') === 'ditolak')>Ditolak / Ditinjau Ulang</option>
                            </x-form.select>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="sm:col-span-2">
                            <x-form.textarea
                                name="alamat"
                                label="Alamat Lengkap Lokasi"
                                rows="3"
                                placeholder="Dusun, RT/RW, Desa Wisata, Kecamatan, Kabupaten..."
                                required
                            />
                        </div>

                        <!-- Deskripsi Homestay -->
                        <div class="sm:col-span-2">
                            <x-form.textarea
                                name="deskripsi"
                                label="Deskripsi & Keunikan Homestay"
                                rows="4"
                                placeholder="Jelaskan suasana alam, kearifan lokal warga, pemandangan sekitar, serta kehangatan keluarga tuan rumah..."
                                required
                            />
                        </div>
                    </div>
                </div>

                <!-- Fasilitas Homestay -->
                <div class="pt-4 border-t border-stone-200">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-[#13391b] border-b border-stone-200 pb-2 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">checklist_rtl</span>
                        <span>Fasilitas yang Disediakan</span>
                    </h3>

                    @if ($fasilitas->isEmpty())
                        <p class="text-xs text-stone-500 italic">Belum ada master data fasilitas yang terdaftar.</p>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach ($fasilitas as $item)
                                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-stone-200 hover:border-emerald-300 hover:bg-emerald-50/40 transition-colors cursor-pointer text-xs font-semibold text-stone-800">
                                    <input
                                        type="checkbox"
                                        name="fasilitas[]"
                                        value="{{ $item->id_fasilitas }}"
                                        @checked(is_array(old('fasilitas')) && in_array($item->id_fasilitas, old('fasilitas')))
                                        class="rounded text-[#13391b] focus:ring-[#13391b] h-4 w-4"
                                    />
                                    <span>{{ $item->nama_fasilitas }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('fasilitas')
                            <p class="mt-2 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Action Footer -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-stone-200">
                    <x-button :href="route('admin.homestay.index')" variant="secondary">Batal</x-button>
                    <x-button type="submit" variant="primary">
                        <span class="material-symbols-outlined text-sm">add_home</span>
                        <span>Simpan & Daftarkan Homestay</span>
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
