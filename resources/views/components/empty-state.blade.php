@props(['title' => 'Belum ada data', 'description' => null])

<div class="rounded-3xl border border-dashed border-sand-300 bg-white/70 px-6 py-14 text-center">
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-forest-50 text-forest-600">
        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75 12 3l8.25 6.75v9a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-9Z" />
            <path stroke-linecap="round" d="M9 20.25v-6h6v6" />
        </svg>
    </div>
    <h2 class="mt-4 font-display text-xl font-semibold text-forest-950">{{ $title }}</h2>
    @if ($description)
        <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-stone-500">{{ $description }}</p>
    @endif
    @if (! $slot->isEmpty())
        <div class="mt-6">{{ $slot }}</div>
    @endif
</div>
