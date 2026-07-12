<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="{{ $settings->allow_indexing ? 'index,follow' : 'noindex,follow' }}">
    <meta name="theme-color" content="{{ $primary }}">
    @include('partials.favicon')
    <title>{{ $settings->title }} | {{ \App\Models\BusinessProfile::first()?->business_name ?? 'Digital Menu' }}</title>
    <meta name="description" content="{{ $settings->subtitle }}">
    <link rel="canonical" href="{{ $settings->allow_indexing ? route('digital-menu.index') : route('produk') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --menu-primary: {{ $primary }}; --menu-accent: {{ $accent }}; }
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        body { font-family: var(--font-sans); }
    </style>
</head>
<body class="min-h-screen bg-[#f4f6f5] text-slate-900 antialiased">
    @yield('content')
</body>
</html>
