@php
    $thumb = $product->getFirstMediaUrl('gallery', 'large') ?: $product->getFirstMediaUrl('gallery', 'thumb');
    $priceLabel = $product->price ? 'Rp ' . number_format($product->price, 0, ',', '.') : 'Hubungi Kami';
    $whatsappNumber = preg_replace('/\D+/', '', (string) \App\Models\SiteSetting::get('whatsapp_number', '6281234567890'));
    $whatsappText = str_replace(
        '{product_name}',
        $product->name,
        \App\Models\SiteSetting::get('whatsapp_text_template', 'Halo, saya tertarik dengan {product_name}')
    );
    $quickViewProduct = [
        'name' => $product->name,
        'price' => $priceLabel,
        'description' => \Illuminate\Support\Str::limit(trim(strip_tags($product->description)), 280),
        'category' => $product->category?->name ?? 'Produk Panama Corner',
        'image' => $thumb,
        'waUrl' => 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($whatsappText),
        'detailUrl' => route('produk.detail', $product->slug),
        'stock' => $product->stock_status,
    ];
@endphp

<article class="catalog-product-card group flex h-full flex-col overflow-hidden rounded-2xl border border-gray-200/80 bg-white transition duration-300 hover:-translate-y-1 hover:border-primary/20 hover:shadow-[0_22px_48px_rgba(15,23,42,.12)]">
    <div class="relative aspect-[4/3] overflow-hidden bg-[#e9ece9]">
        <div class="absolute left-4 top-4 z-10 flex flex-wrap gap-2">
            @if($product->is_bestseller)
                <span class="rounded-md bg-secondary px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[.12em] text-[#211b10] shadow-sm">Terlaris</span>
            @endif
            @if($product->stock_status === 'habis')
                <span class="rounded-md bg-red-600 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[.12em] text-white">Stok Habis</span>
            @elseif($product->stock_status === 'pre-order')
                <span class="rounded-md bg-[#fff7df] px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[.12em] text-amber-800 ring-1 ring-amber-300">Pre-Order</span>
            @endif
        </div>

        @if($thumb)
            <img src="{{ $thumb }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy" decoding="async">
        @else
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_20%,rgba(245,158,11,.18),transparent_35%),linear-gradient(145deg,#edf1ee,#dce3df)]"></div>
            <div class="absolute inset-0 flex items-center justify-center text-primary/25" aria-hidden="true">
                <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M5 9h11v5a5 5 0 0 1-5 5h-1a5 5 0 0 1-5-5V9Z"/><path d="M16 11h1.5a2.5 2.5 0 0 1 0 5H16M8 5c0 1 1 1 1 2M12 4c0 1 1 1 1 2"/></svg>
            </div>
        @endif

        <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/55 via-black/0 to-transparent p-4 opacity-0 transition duration-300 group-hover:opacity-100 group-focus-within:opacity-100">
            <button @click="selectProduct(@js($quickViewProduct))" type="button" class="inline-flex min-h-10 w-full items-center justify-center gap-2 rounded-lg border border-white/50 bg-white/92 text-xs font-extrabold text-gray-950 shadow-lg backdrop-blur-md transition hover:bg-secondary">
                <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                Lihat Cepat
            </button>
        </div>
    </div>

    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-center justify-between gap-3">
            <p class="text-[9px] font-extrabold uppercase tracking-[.15em] text-primary">{{ $product->category?->name ?? 'Produk Panama Corner' }}</p>
            <span class="text-[10px] font-semibold {{ $product->stock_status === 'tersedia' ? 'text-emerald-600' : 'text-gray-400' }}">{{ $product->stock_status === 'tersedia' ? '● Tersedia' : ucfirst($product->stock_status) }}</span>
        </div>
        <h2 class="mt-3 text-base font-extrabold leading-6 text-gray-950"><a href="{{ route('produk.detail', $product->slug) }}" class="transition hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">{{ $product->name }}</a></h2>
        <p class="mt-2 line-clamp-2 text-xs leading-5 text-gray-500">{{ \Illuminate\Support\Str::limit(trim(strip_tags($product->description)), 110) }}</p>

        <div class="mt-auto flex items-end justify-between gap-4 border-t border-gray-100 pt-5">
            <div><p class="text-[9px] font-semibold uppercase tracking-wider text-gray-400">Harga</p><p class="mt-1 text-base font-extrabold text-gray-950">{{ $priceLabel }}</p></div>
            <a href="{{ route('produk.detail', $product->slug) }}" class="inline-flex min-h-10 items-center justify-center rounded-lg bg-primary px-4 text-[11px] font-bold text-white transition hover:-translate-y-0.5 hover:brightness-105">Lihat Detail</a>
        </div>
    </div>
</article>
