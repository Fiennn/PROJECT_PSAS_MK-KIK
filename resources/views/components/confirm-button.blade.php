@props([
    'action',
    'method' => 'POST',
    'label' => 'Hapus',
    'title' => 'Konfirmasi tindakan',
    'message' => 'Apakah Anda yakin ingin melanjutkan? Tindakan ini tidak dapat dibatalkan.',
    'confirmLabel' => 'Ya, lanjutkan',
    'variant' => 'danger',
    'size' => 'sm',
])

@php
    $buttonVariants = [
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'primary' => 'bg-forest-700 text-white hover:bg-forest-800',
        'secondary' => 'border border-sand-300 bg-white text-forest-900 hover:bg-sand-100',
    ];
    $sizes = [
        'sm' => 'px-3.5 py-2 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
    ];
@endphp

<div x-data="{ open: false }" class="inline-block">
    <button
        type="button"
        class="focus-ring inline-flex items-center justify-center gap-2 rounded-full font-semibold transition {{ $buttonVariants[$variant] ?? $buttonVariants['danger'] }} {{ $sizes[$size] ?? $sizes['sm'] }}"
        @click="open = true; $nextTick(() => $refs.confirm.focus())"
    >
        {{ $label }}
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-end justify-center bg-forest-950/50 p-4 backdrop-blur-sm sm:items-center"
        role="dialog"
        aria-modal="true"
        aria-labelledby="confirm-title-{{ md5($action.$label) }}"
        @keydown.escape.window="open = false"
    >
        <div x-show="open" x-transition class="w-full max-w-md rounded-3xl bg-white p-6 shadow-lift" @click.outside="open = false">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                </svg>
            </div>
            <h2 id="confirm-title-{{ md5($action.$label) }}" class="mt-4 font-display text-xl font-semibold text-forest-950">{{ $title }}</h2>
            <p class="mt-2 text-sm leading-relaxed text-stone-600">{{ $message }}</p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <x-button type="button" variant="secondary" @click="open = false">Batal</x-button>
                <form method="POST" action="{{ $action }}">
                    @csrf
                    @method($method)
                    <button type="submit" x-ref="confirm" class="focus-ring inline-flex w-full items-center justify-center rounded-full bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 sm:w-auto">
                        {{ $confirmLabel }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
