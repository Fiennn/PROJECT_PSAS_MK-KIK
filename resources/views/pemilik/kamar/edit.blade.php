@extends('layouts.dashboard')

@section('title', 'Edit Kamar')
@section('page_title', 'Edit '.$kamar->nama_kamar)
@section('page_description', 'Perbarui kapasitas, harga, atau status kamar. Perubahan harga tidak mengubah total pesanan yang sudah ada.')

@section('page_actions')
    <x-button :href="route('pemilik.homestay.kamar.index', $kamar->homestay)" variant="secondary">Kembali</x-button>
@endsection

@section('dashboard')
    <div class="max-w-2xl">
        <x-card>
            @include('pemilik.kamar._form', ['kamar' => $kamar, 'homestay' => $kamar->homestay])
        </x-card>
    </div>
@endsection
