<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Dynamic Favicon -->
    @include('partials.favicon')

    <!-- Dynamic SEO Meta Tags -->
    <title>@yield('title', \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\SiteSetting::get('meta_description_default', 'Kafe di Condongcatur dengan pilihan makanan dan minuman.'))">
    
    <!-- Open Graph (Facebook / WA / Instagram Sharing) -->
    <meta property="og:title" content="@yield('title', \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))">
    <meta property="og:description" content="@yield('meta_description', \App\Models\SiteSetting::get('meta_description_default', 'Kafe di Condongcatur dengan pilihan makanan dan minuman.'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    
    <!-- Google Fonts Dynamic Load -->
    @php
        $fontTitle = \App\Models\SiteSetting::get('theme_font_title', 'Plus Jakarta Sans');
        $fontBody = \App\Models\SiteSetting::get('theme_font_body', 'Inter');
        $fontTitleUrl = urlencode($fontTitle);
        $fontBodyUrl = urlencode($fontBody);
    @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ $fontTitleUrl }}:wght@400;500;600;700;800&family={{ $fontBodyUrl }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dynamic CSS Custom Properties (Theme Colors) -->
    <style>
        :root {
            --color-primary: {{ \App\Models\SiteSetting::get('theme_primary_color', '#0F766E') }};
            --color-secondary: {{ \App\Models\SiteSetting::get('theme_secondary_color', '#F59E0B') }};
            --font-title: '{{ $fontTitle }}', sans-serif;
            --font-sans: '{{ $fontBody }}', sans-serif;
        }
        body {
            font-family: var(--font-sans);
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-title);
        }
    </style>

    <!-- Vite Styles & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Analytics Integration -->
    @php
        $gaId = \App\Models\SiteSetting::get('google_analytics_id');
        $metaPixelId = \App\Models\SiteSetting::get('meta_pixel_id');
    @endphp

    @if($gaId)
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}');
        </script>
    @endif

    <!-- Meta Pixel Integration -->
    @if($metaPixelId)
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $metaPixelId }}');
            fbq('track', 'PageView');
        </script>
    @endif

    <!-- JSON-LD Structured Data for Local Business -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "LocalBusiness",
        "name": "{{ \App\Models\BusinessProfile::first()?->business_name ?? 'Panama Corner' }}",
        "image": "{{ asset('images/logo.png') }}",
        "telephone": "{{ \App\Models\SiteSetting::get('office_phone', '') }}",
        "email": "{{ \App\Models\SiteSetting::get('email_address', '') }}",
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "Jl. Mancasan Indah III No.1, Ngringin, Condongcatur",
            "addressLocality": "Sleman",
            "addressRegion": "Daerah Istimewa Yogyakarta",
            "postalCode": "55281",
            "addressCountry": "ID"
        },
        "url": "{{ url('/') }}"
    }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 overflow-x-hidden min-h-screen flex flex-col">

    <!-- Header Navigation -->
    <header
        x-data="{ open: false, compact: false }"
        x-init="compact = window.scrollY > 32"
        @scroll.window.throttle.80ms="compact = window.scrollY > 32"
        :class="compact || open ? 'bg-white/94 border-black/8 shadow-[0_12px_40px_rgba(16,37,31,.08)]' : 'bg-transparent border-white/15'"
        class="fixed inset-x-0 top-0 z-50 border-b backdrop-blur-xl transition-all duration-500"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div :class="compact ? 'h-16' : 'h-[4.75rem]'" class="flex items-center justify-between transition-all duration-500">
                <!-- Logo -->
                <div class="min-w-0 flex-shrink-0">
                    @php
                        $headerProfile = \App\Models\BusinessProfile::first();
                        $logoPath = $headerProfile?->getFirstMediaUrl('logo');
                        $headerBusinessName = $headerProfile?->business_name ?? 'Panama Corner';
                    @endphp
                    <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3 sm:gap-3.5" aria-label="{{ $headerBusinessName }} — Beranda">
                        @if($logoPath)
                            <span :class="compact ? 'h-9' : 'h-10'" class="flex max-w-[76px] shrink-0 items-center transition-all duration-500">
                                <img src="{{ $logoPath }}" alt="Logo {{ $headerBusinessName }}" class="block max-h-full w-auto max-w-full object-contain transition duration-500" :class="compact ? '' : 'drop-shadow-[0_2px_8px_rgba(0,0,0,.22)]'">
                            </span>
                        @else
                            <span :class="compact ? 'h-9 w-9 rounded-[10px] border-primary bg-primary text-white' : 'h-10 w-10 rounded-xl border-white/35 bg-white/10 text-white'" class="inline-flex shrink-0 items-center justify-center border backdrop-blur-md transition-all duration-500" aria-hidden="true">
                                <svg class="h-[58%] w-[58%]" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7.5 13h14v4.2a7 7 0 0 1-7 7 7 7 0 0 1-7-7V13Z"/><path d="M21.5 15h1.7a3.1 3.1 0 0 1 0 6.2h-2.9M6 26h18"/><path d="M11 5.5c-1.3 1.3 1.3 2.3 0 3.7M16 4.5c-1.5 1.5 1.5 2.5 0 4.2"/></svg>
                            </span>
                        @endif
                        <span :class="compact ? 'text-gray-950' : 'text-white'" class="truncate font-title text-base font-bold tracking-[-.025em] transition-colors duration-500 sm:text-lg">
                            {{ $headerBusinessName }}
                        </span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8">
                    @php
                        $menuItems = \App\Models\MenuItem::where('is_active', true)->with('page')->orderBy('sort_order')->get();
                    @endphp
                    @foreach($menuItems as $item)
                        <a href="{{ $item->getUrl() }}" :class="compact ? '{{ $item->isActiveRoute() ? 'text-primary' : 'text-gray-600 hover:text-primary' }}' : '{{ $item->isActiveRoute() ? 'text-secondary' : 'text-white/78 hover:text-white' }}'" class="border-b pb-1 text-xs font-semibold transition duration-300 {{ $item->isActiveRoute() ? 'border-current' : 'border-transparent' }}">
                            {{ $item->label }}
                        </a>
                    @endforeach
                </nav>

                <!-- CTA Button -->
                <div class="hidden md:block">
                    <a href="{{ route('kontak') }}" :class="compact ? 'border-primary bg-primary text-white hover:bg-primary/90' : 'border-white/45 bg-white/8 text-white hover:bg-white hover:text-gray-950'" class="inline-flex min-h-10 items-center justify-center rounded-full border px-5 text-xs font-semibold backdrop-blur transition duration-300">
                        Hubungi Kami
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="open = !open" type="button" :class="compact || open ? 'text-gray-700 hover:bg-gray-100' : 'text-white hover:bg-white/10'" class="inline-flex items-center justify-center rounded-full p-2.5 focus:outline-none transition" aria-controls="mobile-menu" :aria-expanded="open.toString()">
                        <svg class="h-6 w-6" :class="{'hidden': open, 'block': !open }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg class="h-6 w-6 hidden" :class="{'block': open, 'hidden': !open }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="md:hidden bg-white border-b border-gray-100" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                @foreach($menuItems as $item)
                    <a href="{{ $item->getUrl() }}" class="block px-3 py-3 rounded-xl text-base font-medium {{ $item->isActiveRoute() ? 'bg-primary/5 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                        {{ $item->label }}
                    </a>
                @endforeach
            </div>
            <div class="p-4 border-t border-gray-100">
                <a href="{{ route('kontak') }}" class="block w-full text-center px-4 py-3 rounded-xl text-base font-semibold text-white bg-primary hover:bg-primary/90 transition shadow-lg shadow-primary/20">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- WhatsApp Floating Action Button -->
    @if((bool) \App\Models\SiteSetting::get('show_whatsapp_fab', true))
    <a href="https://wa.me/{{ \App\Models\SiteSetting::get('whatsapp_number', '6281234567890') }}" target="_blank" class="fixed bottom-6 right-6 z-50 h-14 w-14 rounded-full bg-green-500 text-white flex items-center justify-center shadow-xl shadow-green-500/20 hover:shadow-green-500/35 hover:scale-110 transition duration-300 transform" title="Hubungi Kami di WhatsApp">
        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.731-1.456L0 24zm6.59-4.846c1.6.95 2.998 1.448 4.817 1.449 5.59 0 10.141-4.55 10.143-10.13.001-2.701-1.051-5.242-2.962-7.157C16.733 1.4 14.19 .35 11.487.35 5.9 0 1.35 4.549 1.347 10.13c-.001 1.882.502 3.31 1.47 4.908L1.83 20.08l5.226-1.37a9.78 9.78 0 0 0-4.41-1.556zm12.353-5.31c-.302-.15-1.786-.88-2.062-.98-.276-.1-.477-.15-.677.15-.2.3-.777.98-.952 1.18-.176.2-.351.225-.653.075-.302-.15-1.273-.47-2.425-1.494-.897-.8-1.502-1.79-1.678-2.09-.176-.3-.019-.462.132-.612.135-.135.302-.35.453-.525.15-.175.2-.3.302-.5.101-.2.05-.375-.025-.525-.075-.15-.677-1.632-.927-2.232-.243-.585-.491-.507-.677-.517-.175-.009-.376-.01-.577-.01s-.527.075-.803.375c-.276.3-1.053 1.03-1.053 2.51 0 1.48 1.079 2.91 1.229 3.11.15.2 2.124 3.245 5.146 4.548.718.31 1.279.496 1.716.636.721.23 1.378.198 1.9.12.58-.088 1.786-.73 2.037-1.43.251-.7.251-1.3 1.176-1.43-.05-.125-.1-.225-.15-.3z" />
        </svg>
    </a>
    @endif

    @include('partials.footer')
</body>
</html>
