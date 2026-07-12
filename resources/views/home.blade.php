@extends('layouts.app')

@section('title', \App\Models\SiteSetting::get('meta_title_default', 'Aromatica Coffee') . ' | Beranda')

@section('content')
<!-- Hero Section with smooth gradients and micro-interactions -->
<section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-primary/40 py-32 md:py-48 text-white overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-secondary/10 via-transparent to-transparent opacity-70"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl space-y-6">
            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-secondary/15 px-3.5 py-1.5 text-xs font-semibold text-secondary ring-1 ring-inset ring-secondary/35">
                Artisan Coffee Roaster Premium
            </span>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight font-title leading-none">
                Nikmati Cita Rasa Kopi Nusantara yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary to-orange-400">Sesungguhnya</span>.
            </h1>
            <p class="text-lg sm:text-xl text-gray-300 max-w-2xl leading-relaxed">
                Kami mengurasi biji kopi pilihan dari petani lokal di seluruh penjuru Indonesia dan memanggangnya segar secara presisi untuk menghadirkan kualitas terbaik di setiap cangkir kopi Anda.
            </p>
            <div class="flex flex-wrap gap-4 pt-4">
                <a href="{{ route('produk') }}" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl text-base font-semibold text-white bg-primary hover:bg-primary/95 transition shadow-lg shadow-primary/30 transform hover:-translate-y-0.5">
                    Jelajahi Katalog
                </a>
                <a href="{{ route('kontak') }}" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl text-base font-semibold text-gray-900 bg-white hover:bg-gray-100 transition shadow-lg transform hover:-translate-y-0.5">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Showcase Section -->
<section class="relative z-20 -mt-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl shadow-gray-200/50 p-8 md:p-10 border border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-gray-100">
        <div class="py-4 md:py-0 md:px-4">
            <span class="block text-4xl font-extrabold text-primary font-title">
                {{ date('Y') - $stats['founded_year'] }} Tahun
            </span>
            <span class="block text-sm text-gray-500 font-medium mt-1">Pengalaman Roasting</span>
        </div>
        <div class="py-4 md:py-0 md:px-4">
            <span class="block text-4xl font-extrabold text-primary font-title">
                {{ $stats['products_count'] }}+
            </span>
            <span class="block text-sm text-gray-500 font-medium mt-1">Varian Biji Kopi & Kopi Botol</span>
        </div>
        <div class="py-4 md:py-0 md:px-4">
            <span class="block text-4xl font-extrabold text-primary font-title">
                {{ $stats['locations_count'] }} Cabang
            </span>
            <span class="block text-sm text-gray-500 font-medium mt-1">Experience Bar & Roastery</span>
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
            <p class="text-base text-gray-600 leading-relaxed">
                {{ $profile->description ?? 'Panama Corner adalah penyedia kopi spesialti premium yang berdedikasi mengemas cita rasa terbaik langsung ke cangkir Anda.' }}
            </p>
            
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="h-6 w-6 rounded-full bg-primary/10 flex items-center justify-center text-primary mt-0.5">✓</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 text-sm">Biji Kopi 100% Pilihan Single Origin</h4>
                        <p class="text-xs text-gray-500">Dikurasi langsung dari perkebunan dataran tinggi terbaik.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="h-6 w-6 rounded-full bg-primary/10 flex items-center justify-center text-primary mt-0.5">✓</div>
                    <div>
                        <h4 class="font-semibold text-gray-900 text-sm">Proses Roasting Artisan</h4>
                        <p class="text-xs text-gray-500">Setiap batch dipanggang segar dengan profil rasa yang dikunci secara konsisten.</p>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <a href="{{ route('profil') }}" class="inline-flex items-center text-primary font-semibold hover:text-primary/80 transition group">
                    Baca Selengkapnya
                    <span class="transform group-hover:translate-x-1 transition duration-150 ml-1.5">&rarr;</span>
                </a>
            </div>
        </div>

        <div class="relative bg-gray-100 rounded-2xl aspect-video md:aspect-square overflow-hidden shadow-lg border border-gray-100 flex items-center justify-center">
            <!-- Simulated premium aesthetic container -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-secondary/10"></div>
            <div class="text-center p-6 relative z-10 space-y-3">
                <div class="text-4xl text-primary font-bold">Panama Corner</div>
                <div class="text-xs tracking-widest text-gray-500 uppercase">Specialty Coffee Roasters</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="max-w-2xl space-y-3">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 font-title sm:text-4xl">Produk Pilihan Terpopuler</h2>
                <p class="text-gray-500">Varian biji kopi single origin andalan dan produk siap minum terlaris di roastery kami.</p>
            </div>
            <div>
                <a href="{{ route('produk') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-primary/95 transition shadow-md shadow-primary/20">
                    Lihat Semua Produk
                </a>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300 flex flex-col h-full group">
                    <div class="aspect-square bg-gray-50 relative overflow-hidden flex items-center justify-center">
                        <!-- Product tag -->
                        <div class="absolute top-4 left-4 z-10 flex flex-wrap gap-2">
                            @if($product->is_bestseller)
                                <span class="bg-secondary text-gray-900 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">Terlaris</span>
                            @endif
                        </div>
                        @php
                            $thumb = $product->getFirstMediaUrl('gallery', 'thumb');
                        @endphp
                        @if($thumb)
                            <img src="{{ $thumb }}" alt="{{ $product->name }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-300">
                        @else
                            <div class="text-gray-300 text-6xl">☕</div>
                        @endif
                    </div>
                    
                    <div class="p-6 flex-grow flex flex-col space-y-3">
                        <span class="text-xs text-gray-400 font-semibold tracking-wider uppercase">{{ $product->category->name }}</span>
                        <h3 class="font-bold text-lg text-gray-900 font-title line-clamp-1 group-hover:text-primary transition">
                            <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed flex-grow">
                            {{ strip_tags($product->description) }}
                        </p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <div>
                                @if($product->price)
                                    <span class="text-lg font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-sm text-gray-500 font-medium">Hubungi Kami</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('produk.detail', $product->slug) }}" class="inline-flex items-center justify-center h-10 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-primary hover:bg-primary/95 transition">
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
            <p class="text-gray-500">Pelajari tips menyeduh dari barista profesional, seluk beluk biji kopi nusantara, dan panduan kopi lainnya.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestArticles as $article)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col h-full">
                    <div class="aspect-video bg-gray-50 relative overflow-hidden flex items-center justify-center">
                        @php
                            $thumb = $article->getFirstMediaUrl('featured_image', 'thumb');
                        @endphp
                        @if($thumb)
                            <img src="{{ $thumb }}" alt="{{ $article->title }}" class="object-cover w-full h-full">
                        @else
                            <div class="text-primary/20 text-5xl">📄</div>
                        @endif
                    </div>

                    <div class="p-6 flex-grow flex flex-col space-y-3">
                        <span class="text-xs text-primary font-bold uppercase tracking-widest">{{ $article->category->name }}</span>
                        <h3 class="font-bold text-lg text-gray-900 font-title line-clamp-2">
                            <a href="{{ route('artikel.detail', $article->slug) }}" class="hover:text-primary transition">{{ $article->title }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 line-clamp-2 flex-grow leading-relaxed">
                            {{ $article->excerpt }}
                        </p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50 text-xs text-gray-400">
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
