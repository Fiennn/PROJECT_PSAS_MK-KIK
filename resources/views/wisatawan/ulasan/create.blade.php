@extends('layouts.dashboard')

@section('title', 'Tulis Ulasan')
@section('page_title', 'Bagikan Pengalaman Anda')

@section('page_actions')
    <x-button :href="route('wisatawan.booking.show', $booking)" variant="secondary">Kembali ke pesanan</x-button>
@endsection

@section('dashboard')
    @php
        $homestay = $booking->kamar->homestay;
    @endphp

    <div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
        <x-card>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-clay-600">Perjalanan selesai</p>
            <h2 class="mt-2 font-display text-2xl font-semibold text-forest-950">{{ $homestay->nama_homestay }}</h2>
            <p class="mt-1 text-sm text-stone-500">{{ $homestay->alamat }}</p>

            <dl class="mt-5 space-y-3 border-t border-sand-100 pt-5 text-sm">
                <div class="flex justify-between gap-3">
                    <dt class="text-stone-500">Kamar</dt>
                    <dd class="font-semibold text-forest-950">{{ $booking->kamar->nama_kamar }}</dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt class="text-stone-500">Menginap</dt>
                    <dd class="font-semibold text-forest-950">{{ tanggal_id($booking->tgl_checkin) }} – {{ tanggal_id($booking->tgl_checkout) }}</dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt class="text-stone-500">Jumlah tamu</dt>
                    <dd class="font-semibold text-forest-950">{{ $booking->jumlah_tamu }} orang</dd>
                </div>
            </dl>
        </x-card>

        <x-card>
            <h2 class="font-display text-xl font-semibold text-forest-950">Ulasan Anda</h2>
            <p class="mt-1 text-sm text-stone-500">
                Ulasan membantu wisatawan lain memilih homestay yang tepat. Ulasan hanya dapat dikirim sekali untuk setiap pemesanan.
            </p>

            <form
                method="POST"
                action="{{ route('wisatawan.ulasan.store', $booking) }}"
                class="mt-6 space-y-6"
                x-data="{ rating: {{ (int) old('rating', 5) }} }"
            >
                @csrf

                <fieldset>
                    <legend class="label">Rating homestay</legend>
                    <div class="flex items-center gap-1.5">
                        @for ($star = 1; $star <= 5; $star++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $star }}" x-model.number="rating" class="sr-only">
                                <span
                                    class="text-4xl leading-none transition"
                                    :class="rating >= {{ $star }} ? 'text-sand-500' : 'text-sand-200'"
                                    aria-hidden="true"
                                >★</span>
                                <span class="sr-only">{{ $star }} bintang</span>
                            </label>
                        @endfor
                        <span class="ml-2 text-sm font-semibold text-stone-600" x-text="rating + ' dari 5'"></span>
                    </div>
                    @error('rating')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </fieldset>

                <x-form.textarea
                    name="komentar"
                    label="Ceritakan pengalaman Anda"
                    rows="6"
                    placeholder="Bagaimana suasana homestay, pelayanan tuan rumah, dan pengalaman wisata Anda?"
                    required
                />

                <div class="flex flex-col gap-3 border-t border-sand-100 pt-5 sm:flex-row sm:justify-end">
                    <x-button :href="route('wisatawan.booking.show', $booking)" variant="secondary">Batal</x-button>
                    <x-button type="submit">Kirim Ulasan</x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
