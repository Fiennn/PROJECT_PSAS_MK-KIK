@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $variants = [
        'primary' => 'bg-forest-700 text-white hover:bg-forest-800 focus-visible:ring-forest-500 shadow-sm',
        'secondary' => 'bg-white text-forest-900 border border-sand-300 hover:bg-sand-100 focus-visible:ring-forest-400',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:ring-red-400 shadow-sm',
        'ghost' => 'bg-transparent text-forest-800 hover:bg-forest-50 focus-visible:ring-forest-400',
        'clay' => 'bg-clay-600 text-white hover:bg-clay-700 focus-visible:ring-clay-400 shadow-sm',
    ];
    $sizes = [
        'sm' => 'px-3.5 py-2 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
    $classes = 'inline-flex items-center justify-center gap-2 rounded-full font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
