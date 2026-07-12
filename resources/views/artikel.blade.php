@extends('layouts.app')

@section('title', 'Edukasi Kopi & Cerita Roastery | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))

@section('content')
<!-- Header -->
<section class="bg-gray-900 py-16 text-white relative">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-3">
        <h1 class="text-3xl font-bold font-title tracking-tight sm:text-4xl">Artikel & Informasi</h1>
        <p class="text-gray-300 max-w-xl mx-auto">Tips menyeduh kopi, wawasan biji kopi lokal, dan cerita di balik dapur pemanggangan kami.</p>
    </div>
</section>

<!-- Main Container -->
<section class="py-16 bg-white flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Filters Sidebar -->
            <div class="col-span-1 space-y-6">
                <form action="{{ route('artikel') }}" method="GET" class="space-y-6 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <!-- Search input -->
                    <div class="space-y-2">
                        <label for="q" class="text-xs font-bold uppercase tracking-wider text-gray-400">Pencarian</label>
                        <input type="text" name="q" id="q" value="{{ request('q') }}" class="block w-full rounded-xl border-gray-200 pl-4 py-2.5 sm:text-sm focus:border-primary focus:ring-primary" placeholder="Cari judul/isi..." />
                    </div>

                    <!-- Category filter -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-wider text-gray-400">Kategori Artikel</label>
                        <div class="space-y-1">
                            <a href="{{ route('artikel', request()->except('category')) }}" class="block py-1.5 text-sm {{ !request('category') ? 'text-primary font-bold' : 'text-gray-600 hover:text-primary' }}">
                                Semua Kategori
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('artikel', array_merge(request()->all(), ['category' => $category->slug])) }}" class="block py-1.5 text-sm {{ request('category') === $category->slug ? 'text-primary font-bold' : 'text-gray-600 hover:text-primary' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-primary/95 transition">
                        Cari Artikel
                    </button>
                </form>
            </div>

            <!-- Articles Listing Area -->
            <div class="col-span-1 lg:col-span-3 space-y-8">
                @if($articles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($articles as $article)
                            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col h-full group">
                                <div class="aspect-video bg-gray-50 relative overflow-hidden flex items-center justify-center">
                                    @php
                                        $thumb = $article->getFirstMediaUrl('featured_image', 'thumb');
                                    @endphp
                                    @if($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $article->title }}" class="object-cover w-full h-full group-hover:scale-102 transition">
                                    @else
                                        <div class="text-primary/20 text-5xl">📄</div>
                                    @endif
                                </div>

                                <div class="p-6 flex-grow flex flex-col space-y-3">
                                    <span class="text-xs text-primary font-bold uppercase tracking-wider">{{ $article->category->name }}</span>
                                    <h3 class="font-bold text-lg text-gray-900 font-title line-clamp-2 group-hover:text-primary transition">
                                        <a href="{{ route('artikel.detail', $article->slug) }}">{{ $article->title }}</a>
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

                    <!-- Pagination -->
                    <div>
                        {{ $articles->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="text-gray-300 text-6xl mb-4">🔍</div>
                        <h3 class="font-bold text-lg text-gray-900 font-title">Artikel Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mt-1">Coba sesuaikan filter pencarian atau kategori Anda.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endsection
