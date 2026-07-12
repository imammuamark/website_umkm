@extends('layouts.app')

@section('title', \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner') . ' | Beranda')

@section('content')
<!-- Hero Section with split-screen dark aesthetic and responsive layout -->
<section class="relative bg-gradient-to-br from-[#090d16] via-[#0b1329] to-[#060b18] text-white pt-24 pb-28 lg:pt-32 lg:pb-36 overflow-hidden">
    <!-- Ambient luxury radial glows -->
    <div class="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-teal-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-amber-500/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <!-- Hero Left Column: Text & Buttons -->
        <div class="lg:col-span-7 space-y-6 lg:pr-6">
            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-secondary/15 px-3.5 py-1.5 text-xs font-semibold text-secondary ring-1 ring-inset ring-secondary/35">
                Artisan Coffee Roaster Premium
            </span>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight font-title leading-tight">
                Nikmati Cita Rasa Kopi Nusantara yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary to-orange-400">Sesungguhnya</span>.
            </h1>
            <p class="text-base sm:text-lg text-gray-350 leading-relaxed font-light">
                Kami mengurasi biji kopi pilihan dari petani lokal di seluruh penjuru Indonesia dan memanggangnya segar secara presisi untuk menghadirkan kualitas terbaik di setiap cangkir kopi Anda.
            </p>
            <div class="flex flex-wrap gap-4 pt-2">
                <a href="{{ route('produk') }}" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl text-base font-semibold text-gray-900 bg-secondary hover:bg-secondary/95 transition shadow-lg shadow-secondary/20 transform hover:-translate-y-0.5">
                    Jelajahi Katalog
                </a>
                <a href="{{ route('kontak') }}" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl text-base font-semibold text-white border border-white/20 hover:bg-white/10 transition transform hover:-translate-y-0.5">
                    Hubungi Kami
                </a>
            </div>
        </div>

        <!-- Hero Right Column: Configurable Large Image -->
        <div class="lg:col-span-5 relative">
            @php
                $heroUploaded = \App\Models\SiteSetting::get('hero_image_upload');
                $heroUrl = $heroUploaded ? \Illuminate\Support\Facades\Storage::url($heroUploaded) : \App\Models\SiteSetting::get('hero_image_url', 'https://images.unsplash.com/photo-1525203135335-74d292fc8d9f?q=80&w=1200&auto=format&fit=crop');
            @endphp
            <div class="relative rounded-3xl overflow-hidden aspect-[4/3] lg:aspect-[5/6] shadow-2xl border border-white/10 group">
                <img src="{{ $heroUrl }}" alt="Panama Corner Hero" class="object-cover w-full h-full group-hover:scale-102 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
            </div>
        </div>
    </div>
</section>

<!-- Floating Glassmorphic Stats Cards Overlay -->
<section class="relative z-20 -mt-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-md rounded-2xl shadow-xl shadow-gray-250/10 p-8 border border-white/40 dark:border-gray-800 flex flex-col items-center text-center group hover:scale-[1.02] transition duration-300">
            <div class="h-14 w-14 rounded-full bg-secondary/15 flex items-center justify-center text-secondary text-2xl shadow-inner mb-4">
                ☕
            </div>
            <span class="block text-3xl font-extrabold text-slate-900 dark:text-white font-title leading-none">
                {{ date('Y') - $stats['founded_year'] }} Tahun
            </span>
            <span class="block text-xs text-slate-500 dark:text-slate-400 font-medium mt-2">Pengalaman Roasting</span>
        </div>
        <!-- Card 2 -->
        <div class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-md rounded-2xl shadow-xl shadow-gray-250/10 p-8 border border-white/40 dark:border-gray-800 flex flex-col items-center text-center group hover:scale-[1.02] transition duration-300">
            <div class="h-14 w-14 rounded-full bg-secondary/15 flex items-center justify-center text-secondary text-2xl shadow-inner mb-4">
                🥤
            </div>
            <span class="block text-3xl font-extrabold text-slate-900 dark:text-white font-title leading-none">
                {{ $stats['products_count'] }}+
            </span>
            <span class="block text-xs text-slate-500 dark:text-slate-400 font-medium mt-2">Varian Biji Kopi & Kopi Botol</span>
        </div>
        <!-- Card 3 -->
        <div class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-md rounded-2xl shadow-xl shadow-gray-250/10 p-8 border border-white/40 dark:border-gray-800 flex flex-col items-center text-center group hover:scale-[1.02] transition duration-300">
            <div class="h-14 w-14 rounded-full bg-secondary/15 flex items-center justify-center text-secondary text-2xl shadow-inner mb-4">
                👥
            </div>
            <span class="block text-3xl font-extrabold text-slate-900 dark:text-white font-title leading-none">
                {{ $stats['locations_count'] }} Cabang
            </span>
            <span class="block text-xs text-slate-500 dark:text-slate-400 font-medium mt-2">Experience Bar & Roastery</span>
        </div>
    </div>
</section>

<!-- Business Storytelling Teaser -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 font-title sm:text-4xl">
                Dedikasi Kami untuk Kopi Indonesia yang Lebih Baik.
            </h2>
            <p class="text-base text-gray-650 leading-relaxed font-light">
                {{ $profile->description ?? 'Panama Corner adalah penyedia kopi spesialti premium yang berdedikasi mengemas cita rasa terbaik langsung ke cangkir Anda.' }}
            </p>
            
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="h-5 w-5 rounded-full bg-secondary/15 flex items-center justify-center text-secondary text-xs mt-0.5">✓</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 text-sm">Biji Kopi 100% Pilihan Single Origin</h4>
                        <p class="text-xs text-gray-500">Dikurasi langsung dari perkebunan dataran tinggi terbaik.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="h-5 w-5 rounded-full bg-secondary/15 flex items-center justify-center text-secondary text-xs mt-0.5">✓</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 text-sm">Proses Roasting Artisan</h4>
                        <p class="text-xs text-gray-500">Setiap batch dipanggang segar dengan profil rasa yang dikunci secara konsisten.</p>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <a href="{{ route('profil') }}" class="inline-flex items-center text-secondary font-semibold hover:text-secondary/80 transition group text-sm">
                    Baca Selengkapnya
                    <span class="transform group-hover:translate-x-1 transition duration-150 ml-1.5">&rarr;</span>
                </a>
            </div>
        </div>

        <div class="relative bg-gray-100 rounded-3xl aspect-video md:aspect-square overflow-hidden shadow-lg border border-gray-100 flex items-center justify-center group">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-500/10 to-amber-500/10 group-hover:scale-102 transition duration-500"></div>
            <div class="text-center p-6 relative z-10 space-y-3">
                <div class="text-4xl text-teal-800 font-extrabold tracking-tight font-title">Panama Corner</div>
                <div class="text-xs tracking-widest text-slate-500 uppercase font-sans">Specialty Coffee Roasters</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-24 bg-slate-50/50 border-t border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="max-w-2xl space-y-3">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 font-title sm:text-4xl">Produk Pilihan Terpopuler</h2>
                <p class="text-gray-550 font-light">Varian biji kopi single origin andalan dan produk siap minum terlaris di roastery kami.</p>
            </div>
            <div>
                <a href="{{ route('produk') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl text-sm font-semibold text-gray-900 bg-secondary hover:bg-secondary/95 transition shadow-md shadow-secondary/20">
                    Lihat Semua Produk
                </a>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col h-full group">
                    <div class="aspect-square bg-slate-50 relative overflow-hidden flex items-center justify-center">
                        <!-- Product tag -->
                        <div class="absolute top-4 left-4 z-10 flex flex-wrap gap-2">
                            @if($product->is_bestseller)
                                <span class="bg-secondary text-gray-900 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">Terlaris</span>
                            @endif
                        </div>
                        @php
                            $thumb = $product->getFirstMediaUrl('gallery', 'thumb');
                        @endphp
                        @if($thumb)
                            <img src="{{ $thumb }}" alt="{{ $product->name }}" class="object-cover w-full h-full group-hover:scale-103 transition duration-300">
                        @else
                            <div class="text-teal-900/10 text-6xl">☕</div>
                        @endif
                    </div>
                    
                    <div class="p-6 flex-grow flex flex-col space-y-3">
                        <span class="text-xs text-teal-800 font-bold tracking-wider uppercase">{{ $product->category->name }}</span>
                        <h3 class="font-bold text-lg text-gray-900 font-title line-clamp-1 group-hover:text-teal-700 transition">
                            <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed flex-grow font-light">
                            {{ strip_tags($product->description) }}
                        </p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div>
                                @if($product->price)
                                    <span class="text-lg font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-sm text-gray-500 font-medium">Hubungi Kami</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('produk.detail', $product->slug) }}" class="inline-flex items-center justify-center h-10 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-teal-700 hover:bg-teal-850 transition">
                                Detail Kopi
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Content Marketing / Latest Blog Articles -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 font-title sm:text-4xl">Artikel & Edukasi Menyeduh</h2>
            <p class="text-gray-550 font-light">Pelajari tips menyeduh dari barista profesional, seluk beluk biji kopi nusantara, dan panduan kopi lainnya.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestArticles as $article)
                <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl transition duration-300 flex flex-col h-full group">
                    <div class="aspect-video bg-slate-50 relative overflow-hidden flex items-center justify-center">
                        @php
                            $thumb = $article->getFirstMediaUrl('featured_image', 'thumb');
                        @endphp
                        @if($thumb)
                            <img src="{{ $thumb }}" alt="{{ $article->title }}" class="object-cover w-full h-full group-hover:scale-103 transition duration-300">
                        @else
                            <div class="text-teal-900/10 text-5xl">📄</div>
                        @endif
                    </div>

                    <div class="p-6 flex-grow flex flex-col space-y-3">
                        <span class="text-xs text-secondary font-bold uppercase tracking-widest">{{ $article->category->name }}</span>
                        <h3 class="font-bold text-lg text-gray-900 font-title line-clamp-2">
                            <a href="{{ route('artikel.detail', $article->slug) }}" class="hover:text-teal-700 transition">{{ $article->title }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 line-clamp-2 flex-grow leading-relaxed font-light">
                            {{ $article->excerpt }}
                        </p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 text-xs text-gray-400">
                            <span>{{ $article->published_at?->format('d M Y') }}</span>
                            <span>{{ $article->reading_time }} Menit Baca</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
