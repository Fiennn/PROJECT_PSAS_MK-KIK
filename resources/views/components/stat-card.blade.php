@props(['label', 'value', 'hint' => null, 'tone' => 'forest'])

@php
    $tones = [
        'forest' => 'bg-forest-50 text-forest-800',
        'sand' => 'bg-sand-100 text-sand-800',
        'clay' => 'bg-clay-50 text-clay-800',
        'sky' => 'bg-sky-50 text-sky-800',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-3xl border border-sand-200 bg-white p-5 shadow-soft']) }}>
    <div class="flex items-start justify-between gap-3">
        <p class="text-sm font-semibold text-stone-500">{{ $label }}</p>
        <span class="rounded-2xl px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $tones[$tone] ?? $tones['forest'] }}">
            {{ $hint ?? 'Total' }}
        </span>
    </div>
    <p class="mt-4 font-display text-3xl font-semibold text-forest-950">{{ $value }}</p>
</div>
