<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'HomeStay Go — pesan homestay dan paket wisata di desa wisata Indonesia dengan mudah.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HomeStay Go') &mdash; HomeStay Go</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-sand-50 bg-grain font-sans">
    <a href="#main-content" class="sr-only z-50 rounded-xl bg-forest-700 px-4 py-2 text-white focus:not-sr-only focus:absolute focus:left-4 focus:top-4">
        Lewati ke konten utama
    </a>

    <x-navbar />

    <main id="main-content">
        @yield('content')
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>
