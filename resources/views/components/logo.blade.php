@props(['compact' => false, 'light' => false])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 group select-none']) }}>
    <img src="{{ asset('logo.png') }}" alt="Logo HomeStay Go" class="h-10 w-10 sm:h-11 sm:w-11 object-contain transition-transform group-hover:scale-105" />
    @unless ($compact)
        <span class="leading-none">
            <span class="block text-xl font-extrabold tracking-tight">
                <span class="{{ $light ? 'text-white' : 'text-[#1b4d24]' }}">HomeStay</span><span class="text-[#ea580c]">Go</span>
            </span>
            <span class="block text-xs font-semibold {{ $light ? 'text-stone-300' : 'text-[#1b4d24]' }} tracking-normal mt-0.5">
                Desa Wisata
            </span>
        </span>
    @endunless
</span>
