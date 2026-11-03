@extends('layouts.dashboard')

@section('title', 'Edit Paket Wisata')
@section('page_title', 'Edit '.$paket->nama_paket)
@section('page_description', 'Perbarui detail paket wisata. Perubahan harga tidak mengubah pesanan yang sudah ada.')

@section('page_actions')
    <x-button :href="route('pemilik.homestay.paket.index', $paket->homestay)" variant="secondary">Kembali</x-button>
@endsection

@section('dashboard')
    <div class="max-w-2xl">
        <x-card>
            @include('pemilik.paket._form', ['paket' => $paket, 'homestay' => $paket->homestay])
        </x-card>
    </div>
@endsection
