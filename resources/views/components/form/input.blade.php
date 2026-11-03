@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);
    $inputValue = old($name, $value);
    $hasError = $errors->has($name);
@endphp

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if ($label)
        <label for="{{ $id }}" class="label">
            {{ $label }}
            @if ($required)<span class="text-clay-600" aria-hidden="true">*</span>@endif
        </label>
    @endif

    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $inputValue }}"
        @if ($required) required @endif
        @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
        {{ $attributes->except(['class', 'id'])->merge(['class' => 'field '.($hasError ? 'field-error' : '')]) }}
    >

    @if ($hint)
        <p id="{{ $id }}-hint" class="hint">{{ $hint }}</p>
    @endif

    @error($name)
        <p id="{{ $id }}-error" class="error-text">{{ $message }}</p>
    @enderror
</div>
