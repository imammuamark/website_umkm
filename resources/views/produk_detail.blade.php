@extends('layouts.app')

@section('title', $product->name . ' | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))

@section('content')
@include('partials.page_hero', ['eyebrow' => $product->category?->name ?? 'Menu & Sajian', 'title' => $product->name, 'subtitle' => 'Detail sajian, harga, ketersediaan, dan informasi pemesanan.'])

<section class="public-page-content flex-grow px-5 py-16 sm:px-8 lg:px-12 lg:py-24 xl:px-16">
    <div class="mx-auto max-w-[1320px]">
        @php($mediaList = $product->getMedia('gallery'))
        <div x-data="{ activeImage: @js($product->resolvedImageUrl('large') ?: asset('images/coffee-placeholder.jpg')) }" class="grid items-start gap-10 lg:grid-cols-12 lg:gap-16">
            <div class="home-reveal space-y-4 lg:col-span-7">
                <div class="relative aspect-[4/3] overflow-hidden rounded-[1.75rem] bg-[#e5e2da] shadow-[0_28px_70px_rgba(16,37,31,.12)]">
                    <img :src="activeImage" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-700">
                    @if($product->is_bestseller)<span class="absolute left-5 top-5 rounded-full bg-secondary px-3 py-1.5 text-[9px] font-bold uppercase tracking-[.14em] text-[#172019]">Pilihan populer</span>@endif
                </div>
                @if($mediaList->count() > 1)
                    <div class="flex gap-3 overflow-x-auto pb-2" aria-label="Galeri foto produk">
                        @foreach($mediaList as $media)
                            <button @click="activeImage = @js($media->getUrl('large'))" type="button" :aria-pressed="activeImage === @js($media->getUrl('large'))" class="h-20 w-20 shrink-0 overflow-hidden rounded-xl border-2 bg-white p-1 transition" :class="activeImage === @js($media->getUrl('large')) ? 'border-primary' : 'border-transparent hover:border-primary/35'">
                                <img src="{{ $media->getUrl('thumb') }}" alt="Sudut foto {{ $loop->iteration }} dari {{ $product->name }}" class="h-full w-full rounded-lg object-cover" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <article class="home-reveal lg:col-span-5 lg:sticky lg:top-24">
                <div class="border-b border-[#d4d0c5] pb-7">
                    <div class="flex items-center justify-between gap-4"><p class="text-[10px] font-bold uppercase tracking-[.2em] text-primary">{{ $product->category?->name ?? 'Sajian' }}</p><span class="text-[10px] font-semibold {{ $product->stock_status === 'tersedia' ? 'text-emerald-700' : 'text-amber-700' }}">● {{ $product->stock_status === 'tersedia' ? 'Tersedia' : ucfirst($product->stock_status) }}</span></div>
                    <h1 class="mt-5 text-3xl font-semibold leading-tight tracking-[-.04em] text-[#10251f] sm:text-4xl">{{ $product->name }}</h1>
                    <p class="mt-6 text-2xl font-semibold tracking-[-.03em] text-[#10251f]">{{ $product->price ? 'Rp '.number_format($product->price, 0, ',', '.') : 'Hubungi kami' }}</p>
                </div>
                <div class="py-7">
                    <p class="text-[10px] font-bold uppercase tracking-[.18em] text-[#747d78]">Deskripsi Produk</p>
                    <div class="mt-4 space-y-4 text-sm leading-7 text-[#5e6964]">{!! $product->description !!}</div>
                </div>
                @if((bool) \App\Models\SiteSetting::get('enable_whatsapp_order', true))
                    <div class="border-t border-[#d4d0c5] pt-7"><a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-[52px] w-full items-center justify-center gap-3 rounded-full bg-[#10251f] px-6 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-primary">Pesan via WhatsApp (Instan) <span>↗</span></a><p class="mt-3 text-center text-[10px] leading-5 text-[#7b847f]">Nama produk otomatis disertakan dalam pesan.</p></div>
                @endif
            </article>
        </div>

        @if($relatedProducts->isNotEmpty())
            <section class="mt-20 border-t border-[#d4d0c5] pt-12 lg:mt-28 lg:pt-16" aria-labelledby="related-title">
                <div class="mb-9 flex items-end justify-between gap-5"><div><p class="text-[10px] font-bold uppercase tracking-[.2em] text-primary">Masih satu kategori</p><h2 id="related-title" class="mt-3 text-3xl font-semibold tracking-[-.04em] text-[#10251f]">Sajian lainnya.</h2></div><a href="{{ route('produk', ['category' => $product->category?->slug]) }}" class="hidden text-xs font-bold text-[#10251f] hover:text-primary sm:block">Lihat kategori →</a></div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($relatedProducts as $relProduct)
                        @php($relThumb = $relProduct->resolvedImageUrl('thumb'))
                        <article class="home-reveal group"><a href="{{ route('produk.detail', $relProduct->slug) }}" class="block aspect-square overflow-hidden rounded-[1.25rem] bg-[#e4e1d9]">@if($relThumb)<img src="{{ $relThumb }}" alt="{{ $relProduct->name }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy">@endif</a><p class="mt-4 text-[9px] font-bold uppercase tracking-[.16em] text-primary">{{ $relProduct->category?->name }}</p><h3 class="mt-2 font-semibold text-[#10251f]"><a href="{{ route('produk.detail', $relProduct->slug) }}" class="hover:text-primary">{{ $relProduct->name }}</a></h3><p class="mt-2 text-sm font-semibold text-[#4f5a55]">{{ $relProduct->price ? 'Rp '.number_format($relProduct->price, 0, ',', '.') : 'Hubungi kami' }}</p></article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</section>
@endsection
