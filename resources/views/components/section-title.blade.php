@props(['eyebrow' => null, 'title', 'description' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between']) }}>
    <div class="max-w-2xl">
        @if ($eyebrow)
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-clay-600">{{ $eyebrow }}</p>
        @endif
        <h2 class="mt-1 font-display text-2xl font-semibold text-forest-950 sm:text-3xl">{{ $title }}</h2>
        @if ($description)
            <p class="mt-2 text-sm leading-relaxed text-stone-600">{{ $description }}</p>
        @endif
    </div>
    @if (! $slot->isEmpty())
        <div class="shrink-0">{{ $slot }}</div>
    @endif
</div>
