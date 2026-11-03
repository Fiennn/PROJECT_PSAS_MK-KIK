@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-clay-600">Ruang kerja {{ auth()->user()->role->value }}</p>
                <h1 class="mt-1 font-display text-3xl font-semibold text-forest-950 sm:text-4xl">@yield('page_title', 'Dashboard')</h1>
                @hasSection('page_description')
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-stone-600">@yield('page_description')</p>
                @endif
            </div>
            @hasSection('page_actions')
                <div class="flex flex-wrap gap-3">@yield('page_actions')</div>
            @endif
        </div>

        <x-dashboard-nav />

        <div class="mt-6">
            @include('partials.flash')
            @yield('dashboard')
        </div>
    </div>
@endsection
