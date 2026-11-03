@props(['label', 'name', 'value' => '1', 'checked' => false])

@php
    $id = $attributes->get('id', 'checkbox-'.md5($name.$value));
@endphp

<div class="flex items-start gap-3">
    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="checkbox"
        value="{{ $value }}"
        @checked(old($name, $checked))
        {{ $attributes->except(['id'])->merge(['class' => 'mt-0.5 h-4 w-4 rounded border-sand-300 text-forest-700 focus:ring-forest-400']) }}
    >
    <label for="{{ $id }}" class="text-sm leading-relaxed text-stone-700">{{ $label }}</label>
</div>
