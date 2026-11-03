@extends('layouts.dashboard')

@section('title', 'Verifikasi Homestay')
@section('page_title', 'Verifikasi Homestay')
@section('page_description', 'Tinjau pengajuan homestay dari pemilik sebelum tampil di katalog publik.')

@section('dashboard')
    <x-card>
        <form method="GET" action="{{ route('admin.homestay.index') }}" class="grid gap-4 sm:grid-cols-[1.4fr_0.7fr_auto] sm:items-end">
            <div>
                <label for="q" class="label">Cari homestay</label>
                <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama homestay, alamat, atau pemilik..." class="field">
            </div>
            <x-form.select name="status" label="Status verifikasi">
                <option value="">Semua status</option>
                <option value="menunggu" @selected(($filters['status'] ?? '') === 'menunggu')>Menunggu</option>
                <option value="disetujui" @selected(($filters['status'] ?? '') === 'disetujui')>Disetujui</option>
                <option value="ditolak" @selected(($filters['status'] ?? '') === 'ditolak')>Ditolak</option>
            </x-form.select>
            <div class="flex gap-2">
                <x-button type="submit">Terapkan</x-button>
                <x-button :href="route('admin.homestay.index')" variant="secondary">Reset</x-button>
            </div>
        </form>
    </x-card>

    @if ($homestays->isEmpty())
        <div class="mt-6">
            <x-empty-state title="Tidak ada homestay" description="Belum ada pengajuan homestay yang sesuai filter." />
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($homestays as $homestay)
                <x-card padding="p-0">
                    <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="font-display text-lg font-semibold text-forest-950">{{ $homestay->nama_homestay }}</h2>
                                <x-badge :status="$homestay->status_verifikasi->value" />
                            </div>
                            <p class="mt-1 text-sm text-stone-500">{{ $homestay->alamat }}</p>
                            <p class="mt-1 text-xs text-stone-400">
                                Pemilik: {{ $homestay->pemilik->nama_pemilik }} · {{ $homestay->kamar_count }} kamar · {{ $homestay->paket_count }} paket
                            </p>
                            @if ($homestay->fasilitas->isNotEmpty())
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    @foreach ($homestay->fasilitas->take(4) as $facility)
                                        <span class="rounded-full bg-sand-100 px-2.5 py-1 text-[10px] font-semibold text-sand-800">{{ $facility->nama_fasilitas }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('admin.homestay.show', $homestay) }}" class="focus-ring shrink-0 rounded-full bg-forest-50 px-4 py-2 text-xs font-bold text-forest-800 transition hover:bg-forest-700 hover:text-white">
                            Tinjau
                        </a>
                    </div>
                </x-card>
            @endforeach
        </div>

        <x-pagination :paginator="$homestays" />
    @endif
@endsection
