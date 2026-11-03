@props(['eyebrow' => null, 'title', 'description' => null])

<div {{ $attributes->merge(['class' => 'max-w-3xl']) }}>
    @if ($eyebrow)
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-clay-600">{{ $eyebrow }}</p>
    @endif
    <h1 class="mt-2 font-display text-3xl font-semibold leading-tight text-forest-950 sm:text-4xl">{{ $title }}</h1>
    @if ($description)
        <p class="mt-3 text-sm leading-relaxed text-stone-600 sm:text-base">{{ $description }}</p>
    @endif
</div>
