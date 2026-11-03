@props(['padding' => 'p-6'])

<div {{ $attributes->merge(['class' => "rounded-3xl border border-sand-200 bg-white shadow-soft $padding"]) }}>
    {{ $slot }}
</div>
