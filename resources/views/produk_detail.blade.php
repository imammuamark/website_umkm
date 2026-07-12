@extends('layouts.app')

@section('title', $product->name . ' | ' . \App\Models\SiteSetting::get('meta_title_default', 'Aromatica Coffee'))

@section('content')
<section class="py-16 bg-white flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Product Grid Details -->
        <div x-data="{ 
            activeImage: '{{ $product->getFirstMediaUrl('gallery', 'large') ?: asset('images/coffee-placeholder.jpg') }}' 
        }" class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16">
            
            <!-- Gallery Panel -->
            <div class="space-y-4">
                <!-- Large active image display -->
                <div class="aspect-square bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 flex items-center justify-center p-8 relative">
                    <img :src="activeImage" alt="{{ $product->name }}" class="object-contain max-h-full max-w-full rounded-2xl transform hover:scale-105 transition duration-300" />
                </div>
                
                <!-- Thumbnails loop -->
                @php
                    $mediaList = $product->getMedia('gallery');
                @endphp
                @if($mediaList->count() > 1)
                    <div class="grid grid-cols-5 gap-3">
                        @foreach($mediaList as $media)
                            <button 
                                @click="activeImage = '{{ $media->getUrl('large') }}'" 
                                type="button" 
                                class="aspect-square rounded-xl border border-gray-100 bg-gray-50 p-2 overflow-hidden hover:border-primary transition focus:outline-none"
                                :class="{'border-primary ring-2 ring-primary/20': activeImage === '{{ $media->getUrl('large') }}'}"
                            >
                                <img src="{{ $media->getUrl('thumb') }}" alt="thumbnail" class="object-cover h-full w-full rounded-lg" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Details Panel -->
            <div class="flex flex-col justify-between py-2">
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-x-1.5 rounded-md bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">
                        {{ $product->category->name }}
                    </span>
                    
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 font-title tracking-tight">
                        {{ $product->name }}
                    </h1>
                    
                    <div class="flex items-center gap-4">
                        @if($product->price)
                            <div class="text-3xl font-extrabold text-gray-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        @else
                            <div class="text-2xl font-extrabold text-gray-500">
                                Hubungi Kami
                            </div>
                        @endif

                        <!-- Stock status badge -->
                        @if($product->stock_status === 'tersedia')
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                Tersedia
                            </span>
                        @elseif($product->stock_status === 'habis')
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                Stok Habis
                            </span>
                        @elseif($product->stock_status === 'pre-order')
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                Pre-Order
                            </span>
                        @endif
                    </div>
                    
                    <hr class="border-gray-100" />
                    
                    <div class="space-y-2">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400">Deskripsi Kopi</h3>
                        <div class="text-sm text-gray-600 leading-relaxed space-y-4">
                            {!! $product->description !!}
                        </div>
                    </div>
                </div>

                <div class="pt-8 space-y-4">
                    <a href="{{ $whatsappUrl }}" target="_blank" class="w-full inline-flex items-center justify-center px-6 py-4 rounded-xl text-base font-semibold text-white bg-green-500 hover:bg-green-600 transition shadow-lg shadow-green-500/20 transform hover:-translate-y-0.5">
                        Pesan via WhatsApp (Instan)
                    </a>
                    <p class="text-center text-xs text-gray-400">
                        Klik tombol di atas untuk menghubungi admin, rincian produk otomatis terisi di chat.
                    </p>
                </div>
            </div>

        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->count() > 0)
            <div class="border-t border-gray-100 pt-16 space-y-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 font-title">Mungkin Anda Suka (Rekomendasi)</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relProduct)
                        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col h-full group">
                            <div class="aspect-square bg-gray-50 relative overflow-hidden flex items-center justify-center">
                                @php
                                    $relThumb = $relProduct->getFirstMediaUrl('gallery', 'thumb');
                                @endphp
                                @if($relThumb)
                                    <img src="{{ $relThumb }}" alt="{{ $relProduct->name }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="text-gray-300 text-4xl">☕</div>
                                @endif
                            </div>
                            
                            <div class="p-4 flex-grow flex flex-col space-y-2">
                                <span class="text-[9px] text-gray-400 font-semibold tracking-wider uppercase">{{ $relProduct->category->name }}</span>
                                <h4 class="font-bold text-xs text-gray-900 font-title line-clamp-1 group-hover:text-primary transition">
                                    <a href="{{ route('produk.detail', $relProduct->slug) }}">{{ $relProduct->name }}</a>
                                </h4>
                                <div class="text-sm font-extrabold text-gray-900 mt-auto pt-2">
                                    Rp {{ number_format($relProduct->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>
@endsection
