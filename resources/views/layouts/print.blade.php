<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laporan') · HomeStay Go</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            @page { margin: 14mm; }
        }
    </style>
</head>
<body class="bg-stone-100 text-stone-900">
    <div class="no-print mx-auto flex max-w-5xl items-center justify-between px-4 py-4">
        <a href="{{ url()->previous() }}" class="text-sm font-semibold text-forest-700">&larr; Kembali</a>
        <button type="button" onclick="window.print()" class="rounded-full bg-forest-700 px-5 py-2.5 text-sm font-semibold text-white">
            Cetak Laporan
        </button>
    </div>

    <main class="mx-auto max-w-5xl bg-white p-6 shadow-sm sm:p-10 print:max-w-none print:shadow-none">
        <header class="mb-8 flex items-start justify-between gap-6 border-b border-stone-200 pb-5">
            <div>
                <x-logo />
                <h1 class="mt-4 font-display text-2xl font-semibold text-forest-950">@yield('report_title')</h1>
                <p class="mt-1 text-sm text-stone-500">@yield('report_period')</p>
            </div>
            <p class="text-right text-xs text-stone-500">Dicetak {{ tanggal_id(now()) }}</p>
        </header>

        @yield('content')
    </main>
</body>
</html>
