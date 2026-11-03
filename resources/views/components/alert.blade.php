@props(['type' => 'info'])

@php
    $styles = [
        'success' => ['bg-forest-50 border-forest-200 text-forest-900', 'text-forest-600', 'M9 12.75 11.25 15 15 9.75', 'Pesan berhasil'],
        'error' => ['bg-red-50 border-red-200 text-red-900', 'text-red-600', 'M6 18 18 6M6 6l12 12', 'Pesan kesalahan'],
        'warning' => ['bg-sand-100 border-sand-300 text-sand-950', 'text-sand-700', 'M12 9v3.75m0 3.75h.008v.008H12v-.008Z', 'Peringatan'],
        'info' => ['bg-sky-50 border-sky-200 text-sky-900', 'text-sky-600', 'M11.25 11.25l.041.041a.75.75 0 0 1 .209.53v4.429m0-9.75h.008v.008H11.25V7.5Z', 'Informasi'],
    ];
    [$box, $iconColor, $path, $ariaLabel] = $styles[$type] ?? $styles['info'];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    role="{{ $type === 'error' ? 'alert' : 'status' }}"
    aria-label="{{ $ariaLabel }}"
    {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-2xl border px-4 py-3.5 text-sm shadow-sm $box"]) }}
>
    <svg class="mt-0.5 h-5 w-5 shrink-0 {{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
        @if ($type === 'success')
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        @elseif ($type === 'error')
            <path stroke-linecap="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
        @elseif ($type === 'warning')
            <path stroke-linecap="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
        @else
            <path stroke-linecap="round" d="M12 9v4m0 3.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        @endif
    </svg>
    <div class="flex-1 leading-relaxed">{{ $slot }}</div>
    <button type="button" class="rounded-full p-1 opacity-60 transition hover:bg-black/5 hover:opacity-100" @click="show = false" aria-label="Tutup pesan">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
        </svg>
    </button>
</div>
