@props(['paginator'])

@if ($paginator->hasPages())
    @php
        $start = max(1, $paginator->currentPage() - 2);
        $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
    @endphp

    <nav class="mt-8 flex flex-wrap items-center justify-between gap-3" aria-label="Navigasi halaman">
        <p class="text-xs text-stone-500">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>

        <div class="flex items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="rounded-xl border border-sand-200 bg-white px-3 py-2 text-xs text-stone-300" aria-disabled="true">Sebelumnya</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="focus-ring rounded-xl border border-sand-200 bg-white px-3 py-2 text-xs font-semibold text-forest-800 hover:bg-sand-100" rel="prev">Sebelumnya</a>
            @endif

            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" class="focus-ring rounded-xl border border-sand-200 bg-white px-3 py-2 text-xs font-semibold text-forest-800 hover:bg-sand-100">1</a>
                @if ($start > 2)
                    <span class="px-1 text-xs text-stone-400">…</span>
                @endif
            @endif

            @foreach (range($start, $end) as $page)
                @if ($page === $paginator->currentPage())
                    <span class="rounded-xl bg-forest-700 px-3 py-2 text-xs font-bold text-white" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="focus-ring rounded-xl border border-sand-200 bg-white px-3 py-2 text-xs font-semibold text-forest-800 hover:bg-sand-100">{{ $page }}</a>
                @endif
            @endforeach

            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)
                    <span class="px-1 text-xs text-stone-400">…</span>
                @endif
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="focus-ring rounded-xl border border-sand-200 bg-white px-3 py-2 text-xs font-semibold text-forest-800 hover:bg-sand-100">{{ $paginator->lastPage() }}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="focus-ring rounded-xl border border-sand-200 bg-white px-3 py-2 text-xs font-semibold text-forest-800 hover:bg-sand-100" rel="next">Berikutnya</a>
            @else
                <span class="rounded-xl border border-sand-200 bg-white px-3 py-2 text-xs text-stone-300" aria-disabled="true">Berikutnya</span>
            @endif
        </div>
    </nav>
@endif
