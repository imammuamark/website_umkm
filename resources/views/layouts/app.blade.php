<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Dynamic SEO Meta Tags -->
    <title>@yield('title', \App\Models\SiteSetting::get('meta_title_default', 'Aromatica Coffee'))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\SiteSetting::get('meta_description_default', 'Artisan Coffee Roaster Premium.'))">
    
    <!-- Open Graph (Facebook / WA / Instagram Sharing) -->
    <meta property="og:title" content="@yield('title', \App\Models\SiteSetting::get('meta_title_default', 'Aromatica Coffee'))">
    <meta property="og:description" content="@yield('meta_description', \App\Models\SiteSetting::get('meta_description_default', 'Artisan Coffee Roaster Premium.'))">
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
        "name": "{{ \App\Models\BusinessProfile::first()?->business_name ?? 'Aromatica Coffee' }}",
        "image": "{{ asset('images/logo.png') }}",
        "telephone": "{{ \App\Models\SiteSetting::get('office_phone', '') }}",
        "email": "{{ \App\Models\SiteSetting::get('email_address', '') }}",
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "Jl. Merdeka No. 56",
            "addressLocality": "Bandung",
            "addressRegion": "Jawa Barat",
            "postalCode": "40115",
            "addressCountry": "ID"
        },
        "url": "{{ url('/') }}"
    }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 overflow-x-hidden min-h-screen flex flex-col">

    <!-- Header Navigation -->
    <header x-data="{ open: false }" class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        @php
                            $logoPath = \App\Models\BusinessProfile::first()?->getFirstMediaUrl('logo');
                        @endphp
                        @if($logoPath)
                            <img class="h-12 w-12 rounded-full object-cover border-2 border-primary/20" src="{{ $logoPath }}" alt="Logo">
                        @else
                            <div class="h-12 w-12 rounded-full bg-primary flex items-center justify-center text-white font-extrabold text-xl shadow-md shadow-primary/20">
                                {{ strtoupper(substr(\App\Models\BusinessProfile::first()?->business_name ?? 'A', 0, 2)) }}
                            </div>
                        @endif
                        <span class="font-extrabold text-xl tracking-tight text-gray-900 font-title">
                            {{ \App\Models\BusinessProfile::first()?->business_name ?? 'Aromatica Coffee' }}
                        </span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="font-medium text-sm transition duration-150 {{ request()->routeIs('home') ? 'text-primary border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary pb-1' }}">Beranda</a>
                    <a href="{{ route('profil') }}" class="font-medium text-sm transition duration-150 {{ request()->routeIs('profil') ? 'text-primary border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary pb-1' }}">Tentang Kami</a>
                    <a href="{{ route('produk') }}" class="font-medium text-sm transition duration-150 {{ request()->routeIs('produk*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary pb-1' }}">Katalog Produk</a>
                    <a href="{{ route('artikel') }}" class="font-medium text-sm transition duration-150 {{ request()->routeIs('artikel*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary pb-1' }}">Artikel</a>
                    <a href="{{ route('lokasi') }}" class="font-medium text-sm transition duration-150 {{ request()->routeIs('lokasi') ? 'text-primary border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary pb-1' }}">Lokasi</a>
                    <a href="{{ route('kontak') }}" class="font-medium text-sm transition duration-150 {{ request()->routeIs('kontak') ? 'text-primary border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary pb-1' }}">Kontak</a>
                </nav>

                <!-- CTA Button -->
                <div class="hidden md:block">
                    <a href="{{ route('kontak') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-primary/90 transition shadow-lg shadow-primary/20 hover:shadow-primary/30 transform hover:-translate-y-0.5">
                        Hubungi Kami
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-primary hover:bg-gray-100 focus:outline-none transition" aria-controls="mobile-menu" aria-expanded="false">
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
                <a href="{{ route('home') }}" class="block px-3 py-3 rounded-xl text-base font-medium {{ request()->routeIs('home') ? 'bg-primary/5 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">Beranda</a>
                <a href="{{ route('profil') }}" class="block px-3 py-3 rounded-xl text-base font-medium {{ request()->routeIs('profil') ? 'bg-primary/5 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">Tentang Kami</a>
                <a href="{{ route('produk') }}" class="block px-3 py-3 rounded-xl text-base font-medium {{ request()->routeIs('produk*') ? 'bg-primary/5 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">Katalog Produk</a>
                <a href="{{ route('artikel') }}" class="block px-3 py-3 rounded-xl text-base font-medium {{ request()->routeIs('artikel*') ? 'bg-primary/5 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">Artikel</a>
                <a href="{{ route('lokasi') }}" class="block px-3 py-3 rounded-xl text-base font-medium {{ request()->routeIs('lokasi') ? 'bg-primary/5 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">Lokasi</a>
                <a href="{{ route('kontak') }}" class="block px-3 py-3 rounded-xl text-base font-medium {{ request()->routeIs('kontak') ? 'bg-primary/5 text-primary' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">Kontak</a>
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
    <a href="https://wa.me/{{ \App\Models\SiteSetting::get('whatsapp_number', '6281234567890') }}" target="_blank" class="fixed bottom-6 right-6 z-50 h-14 w-14 rounded-full bg-green-500 text-white flex items-center justify-center shadow-xl shadow-green-500/20 hover:shadow-green-500/35 hover:scale-110 transition duration-300 transform" title="Hubungi Kami di WhatsApp">
        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.731-1.456L0 24zm6.59-4.846c1.6.95 2.998 1.448 4.817 1.449 5.59 0 10.141-4.55 10.143-10.13.001-2.701-1.051-5.242-2.962-7.157C16.733 1.4 14.19 .35 11.487.35 5.9 0 1.35 4.549 1.347 10.13c-.001 1.882.502 3.31 1.47 4.908L1.83 20.08l5.226-1.37a9.78 9.78 0 0 0-4.41-1.556zm12.353-5.31c-.302-.15-1.786-.88-2.062-.98-.276-.1-.477-.15-.677.15-.2.3-.777.98-.952 1.18-.176.2-.351.225-.653.075-.302-.15-1.273-.47-2.425-1.494-.897-.8-1.502-1.79-1.678-2.09-.176-.3-.019-.462.132-.612.135-.135.302-.35.453-.525.15-.175.2-.3.302-.5.101-.2.05-.375-.025-.525-.075-.15-.677-1.632-.927-2.232-.243-.585-.491-.507-.677-.517-.175-.009-.376-.01-.577-.01s-.527.075-.803.375c-.276.3-1.053 1.03-1.053 2.51 0 1.48 1.079 2.91 1.229 3.11.15.2 2.124 3.245 5.146 4.548.718.31 1.279.496 1.716.636.721.23 1.378.198 1.9.12.58-.088 1.786-.73 2.037-1.43.251-.7.251-1.3 1.176-1.43-.05-.125-.1-.225-.15-.3z" />
        </svg>
    </a>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 pt-16 pb-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Branding column -->
                <div class="col-span-1 md:col-span-1 space-y-4">
                    <h3 class="text-white font-bold text-lg font-title">
                        {{ \App\Models\BusinessProfile::first()?->business_name ?? 'Aromatica Coffee' }}
                    </h3>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Pusat biji kopi artisan premium Bandung. Menyajikan keunikan rasa Nusantara langsung ke cangkir Anda.
                    </p>
                    <div class="flex space-x-4">
                        @php
                            $ig = \App\Models\SiteSetting::get('instagram_url');
                            $fb = \App\Models\SiteSetting::get('facebook_url');
                            $tk = \App\Models\SiteSetting::get('tiktok_url');
                        @endphp
                        @if($ig)
                            <a href="{{ $ig }}" class="hover:text-white transition duration-150" target="_blank">Instagram</a>
                        @endif
                        @if($fb)
                            <a href="{{ $fb }}" class="hover:text-white transition duration-150" target="_blank">Facebook</a>
                        @endif
                        @if($tk)
                            <a href="{{ $tk }}" class="hover:text-white transition duration-150" target="_blank">TikTok</a>
                        @endif
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="col-span-1">
                    <h4 class="text-white font-semibold text-sm tracking-wider uppercase mb-4">Navigasi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition duration-150">Beranda</a></li>
                        <li><a href="{{ route('profil') }}" class="hover:text-white transition duration-150">Tentang Kami</a></li>
                        <li><a href="{{ route('produk') }}" class="hover:text-white transition duration-150">Katalog Produk</a></li>
                        <li><a href="{{ route('artikel') }}" class="hover:text-white transition duration-150">Artikel & Tips</a></li>
                    </ul>
                </div>

                <!-- Legalities -->
                <div class="col-span-1">
                    <h4 class="text-white font-semibold text-sm tracking-wider uppercase mb-4">Kepercayaan</h4>
                    <ul class="space-y-2 text-sm">
                        @php
                            $docs = \App\Models\BusinessProfile::first()?->legal_docs ?? [];
                        @endphp
                        @foreach(array_slice($docs, 0, 3) as $doc)
                            <li><span class="text-xs text-gray-500 font-mono">{{ $doc['name'] }}:</span> <span class="text-gray-400">{{ $doc['number'] }}</span></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Kontak Column -->
                <div class="col-span-1">
                    <h4 class="text-white font-semibold text-sm tracking-wider uppercase mb-4">Kontak Kami</h4>
                    <ul class="space-y-2 text-sm">
                        <li><span class="text-gray-500">Email:</span> <a href="mailto:{{ \App\Models\SiteSetting::get('email_address', 'info@aromaticacoffee.com') }}" class="hover:text-white transition duration-150">{{ \App\Models\SiteSetting::get('email_address', 'info@aromaticacoffee.com') }}</a></li>
                        <li><span class="text-gray-500">Telepon:</span> <span class="text-gray-400">{{ \App\Models\SiteSetting::get('office_phone', '+62 22 1234567') }}</span></li>
                        <li><span class="text-gray-500">Alamat:</span> <span class="text-gray-400">Jl. Merdeka No. 56, Bandung</span></li>
                    </ul>
                </div>
            </div>

            <hr class="border-gray-800 mb-8" />

            <div class="flex flex-col md:flex-row justify-between items-center text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} {{ \App\Models\BusinessProfile::first()?->business_name ?? 'Aromatica Coffee' }}. Hak Cipta Dilindungi Undang-Undang.</p>
                <p>Built with Laravel & Filament</p>
            </div>
        </div>
    </footer>
</body>
</html>
