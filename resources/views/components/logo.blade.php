@props(['compact' => false])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-forest-700 text-sand-100 shadow-sm" aria-hidden="true">
        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7">
            <path d="M4 20c4.5-1 7.5-3.5 9-7.5 1.5 4 4.5 6.5 9 7.5" stroke-linecap="round" />
            <path d="M12 12.5C9.5 9 9 5.5 12 3c3 2.5 2.5 6 0 9.5Z" stroke-linejoin="round" />
        </svg>
    </span>
    @unless ($compact)
        <span class="leading-tight">
            <span class="block font-display text-xl font-semibold tracking-tight text-forest-900">HomeStay Go</span>
            <span class="block text-[10px] font-semibold uppercase tracking-[0.2em] text-clay-600">Desa Wisata</span>
        </span>
    @endunless
</span>
