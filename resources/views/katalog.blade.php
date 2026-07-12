@extends('layouts.app')

@section('title', 'Katalog Kopi & Alat Seduh | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))

@section('content')
<!-- Page Title Header -->
<section class="bg-gray-900 py-16 text-white relative">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-3">
        <h1 class="text-3xl font-bold font-title tracking-tight sm:text-4xl">Katalog Produk</h1>
        <p class="text-gray-300 max-w-xl mx-auto">Temukan kopi favorit dan peralatan menyeduh manual pilihan terbaik Anda.</p>
    </div>
</section>

<!-- Main Catalog Container with Alpine.js Quick View Modal State -->
<div x-data="{ 
    openModal: false, 
    activeProduct: { name: '', price: '', description: '', category: '', image: '', waUrl: '' }
}" class="py-16 bg-white flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Filters Sidebar -->
            <div class="col-span-1 space-y-6">
                <form action="{{ route('produk') }}" method="GET" class="space-y-6 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <!-- Search input -->
                    <div class="space-y-2">
                        <label for="q" class="text-xs font-bold uppercase tracking-wider text-gray-400">Pencarian</label>
                        <div class="relative">
                            <input type="text" name="q" id="q" value="{{ request('q') }}" class="block w-full rounded-xl border-gray-200 pl-4 pr-10 py-2.5 sm:text-sm focus:border-primary focus:ring-primary dark:border-gray-700" placeholder="Cari nama produk..." />
                        </div>
                    </div>

                    <!-- Category filter -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-wider text-gray-400">Kategori</label>
                        <div class="space-y-1">
                            <a href="{{ route('produk', request()->except('category')) }}" class="block py-1.5 text-sm {{ !request('category') ? 'text-primary font-bold' : 'text-gray-600 hover:text-primary' }}">
                                Semua Kategori
                            </a>
                            @foreach($categories as $category)
                                <div class="space-y-1">
                                    <a href="{{ route('produk', array_merge(request()->all(), ['category' => $category->slug])) }}" class="block py-1.5 text-sm {{ request('category') === $category->slug ? 'text-primary font-bold' : 'text-gray-600 hover:text-primary' }}">
                                        {{ $category->name }}
                                    </a>
                                    
                                    <!-- Child categories if exist -->
                                    @if($category->children->count() > 0)
                                        <div class="pl-3 space-y-0.5 border-l border-gray-200">
                                            @foreach($category->children as $child)
                                                <a href="{{ route('produk', array_merge(request()->all(), ['category' => $child->slug])) }}" class="block py-1 text-xs {{ request('category') === $child->slug ? 'text-primary font-bold' : 'text-gray-500 hover:text-primary' }}">
                                                    {{ $child->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sorting -->
                    <div class="space-y-2">
                        <label for="sort" class="text-xs font-bold uppercase tracking-wider text-gray-400">Urutkan</label>
                        <select name="sort" id="sort" onchange="this.form.submit()" class="block w-full rounded-xl border-gray-200 py-2.5 text-sm focus:border-primary focus:ring-primary">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-primary/95 transition">
                        Terapkan Filter
                    </button>
                </form>
            </div>

            <!-- Product Grid Area -->
            <div class="col-span-1 lg:col-span-3 space-y-8">
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300 flex flex-col h-full group">
                                <div class="aspect-square bg-gray-50 relative overflow-hidden flex items-center justify-center">
                                    <div class="absolute top-4 left-4 z-10 flex flex-wrap gap-2">
                                        @if($product->is_bestseller)
                                            <span class="bg-secondary text-gray-900 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">Terlaris</span>
                                        @endif
                                        @if($product->stock_status === 'habis')
                                            <span class="bg-red-500 text-white px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">Habis</span>
                                        @elseif($product->stock_status === 'pre-order')
                                            <span class="bg-amber-500 text-white px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">Pre-Order</span>
                                        @endif
                                    </div>
                                    
                                    @php
                                        $thumb = $product->getFirstMediaUrl('gallery', 'thumb');
                                    @endphp
                                    @if($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $product->name }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="text-gray-300 text-5xl">☕</div>
                                    @endif

                                    <!-- Quick View Hover Button -->
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <button 
                                            @click="
                                                activeProduct = {
                                                    name: '{{ addslashes($product->name) }}',
                                                    price: '{{ $product->price ? 'Rp ' . number_format($product->price, 0, ',', '.') : 'Hubungi Kami' }}',
                                                    description: '{{ addslashes(Str::limit(strip_tags($product->description), 250)) }}',
                                                    category: '{{ $product->category->name }}',
                                                    image: '{{ $product->getFirstMediaUrl('gallery', 'large') ?: asset('images/coffee-placeholder.jpg') }}',
                                                    waUrl: 'https://wa.me/{{ \App\Models\SiteSetting::get('whatsapp_number') }}?text={{ urlencode(str_replace('{product_name}', $product->name, \App\Models\SiteSetting::get('whatsapp_text_template', 'Halo, saya tertarik dengan {product_name}'))) }}'
                                                };
                                                openModal = true;
                                            "
                                            type="button" 
                                            class="px-4 py-2 bg-white/95 backdrop-blur-sm text-gray-900 rounded-xl text-xs font-bold shadow-md hover:bg-primary hover:text-white transition duration-250"
                                        >
                                            Quick View
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="p-6 flex-grow flex flex-col space-y-2">
                                    <span class="text-[10px] text-gray-400 font-semibold tracking-wider uppercase">{{ $product->category->name }}</span>
                                    <h3 class="font-bold text-sm text-gray-900 font-title line-clamp-1 group-hover:text-primary transition">
                                        <a href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a>
                                    </h3>
                                    
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-50 mt-auto">
                                        <div>
                                            @if($product->price)
                                                <span class="text-base font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-xs text-gray-500 font-medium">Hubungi Kami</span>
                                            @endif
                                        </div>
                                        
                                        <a href="{{ route('produk.detail', $product->slug) }}" class="inline-flex items-center justify-center h-9 px-3.5 py-1.5 rounded-xl text-xs font-semibold text-white bg-primary hover:bg-primary/95 transition">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Custom Pagination links -->
                    <div class="pt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="text-gray-300 text-6xl mb-4">🔍</div>
                        <h3 class="font-bold text-lg text-gray-900 font-title">Produk Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mt-1">Coba sesuaikan kata kunci pencarian atau ganti kategori filter Anda.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Quick View Modal Container (Alpine.js) -->
    <div 
        x-show="openModal" 
        class="fixed inset-0 z-50 overflow-y-auto" 
        style="display: none;"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div @click="openModal = false" class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Content Panel -->
            <div 
                x-show="openModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-gray-100"
            >
                <div class="absolute top-4 right-4 z-10">
                    <button @click="openModal = false" type="button" class="h-10 w-10 bg-white/90 backdrop-blur-sm hover:bg-gray-100 text-gray-500 hover:text-gray-900 rounded-full flex items-center justify-center shadow-sm transition">
                        ✕
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Photo -->
                    <div class="bg-gray-50 aspect-square flex items-center justify-center p-8">
                        <img :src="activeProduct.image" :alt="activeProduct.name" class="object-contain max-h-full rounded-2xl" />
                    </div>

                    <!-- Details -->
                    <div class="p-8 flex flex-col justify-between">
                        <div class="space-y-4">
                            <span x-text="activeProduct.category" class="text-xs text-primary font-bold uppercase tracking-wider"></span>
                            <h2 x-text="activeProduct.name" class="text-2xl font-bold text-gray-900 font-title"></h2>
                            <div class="text-xl font-extrabold text-primary" x-text="activeProduct.price"></div>
                            
                            <hr class="border-gray-100" />
                            
                            <p x-text="activeProduct.description" class="text-sm text-gray-500 leading-relaxed"></p>
                        </div>

                        <div class="pt-6">
                            <a :href="activeProduct.waUrl" target="_blank" class="w-full inline-flex items-center justify-center px-5 py-3.5 rounded-xl text-sm font-semibold text-white bg-green-500 hover:bg-green-600 transition shadow-lg shadow-green-500/20">
                                Pesan Sekarang via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
