@props([
    'label' => null,
    'name',
    'required' => false,
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
@endphp

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if ($label)
        <label for="{{ $id }}" class="label">
            {{ $label }}
            @if ($required)<span class="text-clay-600" aria-hidden="true">*</span>@endif
        </label>
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        @if ($required) required @endif
        @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
        {{ $attributes->except(['class', 'id'])->merge(['class' => 'field '.($hasError ? 'field-error' : '')]) }}
    >
        {{ $slot }}
    </select>

    @if ($hint)
        <p class="hint">{{ $hint }}</p>
    @endif

    @error($name)
        <p id="{{ $id }}-error" class="error-text">{{ $message }}</p>
    @enderror
</div>
