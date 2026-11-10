@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-100 text-[#13391b] text-xs font-semibold mb-2">
                    <span class="material-symbols-outlined text-sm text-[#ea580c]">shield_person</span>
                    <span>Ruang Kerja {{ ucfirst(auth()->user()->role->value) }}</span>
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-stone-900">@yield('page_title', 'Dashboard')</h1>
                @hasSection('page_description')
                    <p class="mt-1.5 max-w-2xl text-sm text-stone-500 leading-relaxed">@yield('page_description')</p>
                @endif
            </div>
            @hasSection('page_actions')
                <div class="flex flex-wrap gap-2.5">@yield('page_actions')</div>
            @endif
        </div>

        <x-dashboard-nav />

        <div class="mt-6">
            @include('partials.flash')
            @yield('dashboard')
        </div>
    </div>
@endsection
