@extends('layouts.dashboard')

@section('title', 'Fasilitas')
@section('page_title', 'Kelola Fasilitas')
@section('page_description', 'Fasilitas yang tersedia di sini dapat dipilih pemilik saat mengelola homestay.')

@section('dashboard')
    <div class="grid gap-6 lg:grid-cols-[1fr_0.7fr] lg:items-start">
        <div class="space-y-4">
            @if ($fasilitas->isEmpty())
                <x-empty-state title="Belum ada fasilitas" description="Tambahkan fasilitas pertama melalui formulir di samping." />
            @else
                <x-card padding="p-0">
                    <ul class="divide-y divide-sand-100">
                        @foreach ($fasilitas as $item)
                            <li class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0">
                                    <p class="font-semibold text-forest-950">{{ $item->nama_fasilitas }}</p>
                                    <p class="mt-0.5 text-xs text-stone-400">Digunakan {{ $item->homestay_count }} homestay</p>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <form method="POST" action="{{ route('admin.fasilitas.update', $item) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input
                                            type="text"
                                            name="nama_fasilitas"
                                            value="{{ old('nama_fasilitas', $item->nama_fasilitas) }}"
                                            class="field !w-44 !py-2 text-sm"
                                            aria-label="Nama fasilitas {{ $item->nama_fasilitas }}"
                                            required
                                        >
                                        <x-button type="submit" variant="secondary" size="sm">Simpan</x-button>
                                    </form>

                                    @if ($item->homestay_count === 0)
                                        <x-confirm-button
                                            :action="route('admin.fasilitas.destroy', $item)"
                                            method="DELETE"
                                            label="Hapus"
                                            title="Hapus fasilitas ini?"
                                            message="Fasilitas yang belum digunakan homestay dapat dihapus permanen."
                                            confirm-label="Ya, hapus"
                                        />
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </x-card>

                <x-pagination :paginator="$fasilitas" />
            @endif
        </div>

        <x-card class="lg:sticky lg:top-28">
            <h2 class="font-display text-xl font-semibold text-forest-950">Tambah fasilitas</h2>
            <p class="mt-1 text-sm text-stone-500">Gunakan nama singkat yang mudah dipahami wisatawan.</p>

            <form method="POST" action="{{ route('admin.fasilitas.store') }}" class="mt-5 space-y-4">
                @csrf
                <x-form.input
                    name="nama_fasilitas"
                    label="Nama fasilitas"
                    placeholder="contoh: Air Panas"
                    required
                />
                <x-button type="submit" class="w-full">Tambah Fasilitas</x-button>
            </form>
        </x-card>
    </div>
@endsection
