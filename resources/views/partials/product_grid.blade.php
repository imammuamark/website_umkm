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
