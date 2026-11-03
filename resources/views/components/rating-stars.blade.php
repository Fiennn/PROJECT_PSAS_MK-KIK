@props(['rating' => 0, 'showValue' => false])

@php
    $rounded = round((float) $rating);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1']) }} aria-label="Rating {{ number_format((float) $rating, 1, ',', '.') }} dari 5">
    <span class="flex text-base leading-none" aria-hidden="true">
        @for ($i = 1; $i <= 5; $i++)
            <span class="{{ $i <= $rounded ? 'text-sand-500' : 'text-sand-200' }}">★</span>
        @endfor
    </span>
    @if ($showValue)
        <span class="text-xs font-semibold text-stone-600">{{ number_format((float) $rating, 1, ',', '.') }}</span>
    @endif
</span>
