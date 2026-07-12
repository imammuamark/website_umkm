@extends('layouts.digital-menu')

@section('content')
@php
    $business = \App\Models\BusinessProfile::first();
    $logo = $business?->getFirstMediaUrl('logo');
    $visibleProducts = $categories->flatMap->products
        ->filter(fn ($product) => $settings->show_unavailable || $product->stock_status !== 'habis');
    $defaultView = match ($settings->layout) {
        'visual' => 'visual',
        'compact', 'list' => 'compact',
        default => 'balanced',
    };
@endphp
<div x-data="{ search: '', searchOpen: false, viewOpen: false, category: @js($selectedCategory ?: 'all'), viewMode: @js($defaultView), selected: null, init() { const saved = localStorage.getItem('digital-menu-view'); if (['visual','balanced','compact'].includes(saved)) this.viewMode = saved }, setView(mode) { this.viewMode = mode; this.viewOpen = false; localStorage.setItem('digital-menu-view', mode) }, openProduct(product) { this.selected = product; document.body.style.overflow='hidden' }, closeProduct() { this.selected=null; document.body.style.overflow='' } }" x-on:keydown.escape.window="closeProduct(); searchOpen=false; viewOpen=false" class="min-h-screen">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/92 backdrop-blur-xl">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-2.5 sm:px-6">
            <a href="{{ route('digital-menu.index') }}" class="flex min-w-0 items-center gap-3">
                @if($logo)<span class="flex h-9 max-w-[70px] shrink-0 items-center"><img src="{{ $logo }}" alt="Logo {{ $business?->business_name }}" class="block max-h-full w-auto max-w-full object-contain"></span>
                @else @include('partials.brand-mark', ['class' => 'h-9 w-9 rounded-[10px] text-white', 'style' => 'background:var(--menu-primary)']) @endif
                <span class="min-w-0"><strong class="block truncate text-sm font-extrabold">{{ $business?->business_name ?? 'Digital Menu' }}</strong><span class="hidden text-[10px] text-slate-500 sm:block">Digital Menu</span></span>
            </a>
            <a href="{{ route('home') }}" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-bold text-slate-600 transition hover:border-slate-300 hover:text-slate-950">Website <span aria-hidden="true">↗</span></a>
        </div>
    </header>

    <main>
        <section class="bg-[#0b1715] text-white">
            <div class="mx-auto max-w-6xl px-4 py-9 sm:px-6 sm:py-12">
                @if($accessPoint)<p class="mb-3 text-[10px] font-bold uppercase tracking-[.2em]" style="color:var(--menu-accent)">{{ $accessPoint->label }}</p>@endif
                <h1 class="max-w-2xl text-3xl font-extrabold tracking-[-.025em] sm:text-[2.6rem]">{{ $settings->title }}</h1>
                @if($settings->subtitle)<p class="mt-3 max-w-2xl text-sm leading-6 text-white/65 sm:text-base">{{ $settings->subtitle }}</p>@endif
                <div class="mt-6 flex flex-wrap gap-3 text-xs text-white/70"><span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">{{ $visibleProducts->count() }} pilihan</span><span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Harga dalam Rupiah</span></div>
            </div>
        </section>

        <div class="sticky top-[57px] z-30 border-b border-slate-200 bg-[#f4f6f5]/96 shadow-[0_8px_24px_rgba(15,23,42,.04)] backdrop-blur-xl">
            <div class="relative mx-auto max-w-6xl px-4 py-2.5 sm:px-6">
                <div class="flex items-center gap-2">
                    <div class="flex min-w-0 flex-1 gap-2 overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <button type="button" x-on:click="category='all'" :class="category==='all' ? 'text-white shadow-md' : 'border border-slate-200 bg-white text-slate-600'" :style="category==='all' ? 'background:var(--menu-primary)' : ''" class="shrink-0 rounded-full px-4 py-2 text-xs font-bold transition">Semua</button>
                    @foreach($categories as $category)
                        <button type="button" x-on:click="category=@js($category->slug)" :class="category===@js($category->slug) ? 'text-white shadow-md' : 'border border-slate-200 bg-white text-slate-600'" :style="category===@js($category->slug) ? 'background:var(--menu-primary)' : ''" class="shrink-0 rounded-full px-4 py-2 text-xs font-bold transition">{{ $category->menu_display_name ?: $category->name }}</button>
                    @endforeach
                    </div>
                    <div class="hidden w-64 shrink-0 md:block">
                        @if($settings->show_search)<div class="relative"><svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input x-model.debounce.150ms="search" type="search" maxlength="100" autocomplete="off" placeholder="Cari menu..." class="h-9 w-full rounded-full border border-slate-200 bg-white pl-9 pr-3 text-xs outline-none focus:border-[var(--menu-primary)]"></div>@endif
                    </div>
                    @if($settings->show_search)<button type="button" x-on:click="searchOpen=!searchOpen; viewOpen=false; $nextTick(() => searchOpen && $refs.mobileSearch?.focus())" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm md:hidden" aria-label="Buka pencarian"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg></button>@endif
                    <div class="relative shrink-0" x-on:click.outside="viewOpen=false">
                        <button type="button" x-on:click="viewOpen=!viewOpen; searchOpen=false" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-sm" aria-label="Pilih mode tampilan"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="7" height="7" rx="1"/><path d="M13 6h8M13 10h6M3 16h18M3 20h13"/></svg></button>
                        <div x-show="viewOpen" x-transition x-cloak class="absolute right-0 top-11 z-40 w-48 rounded-2xl border border-slate-200 bg-white p-2 shadow-[0_18px_50px_rgba(15,23,42,.16)]" role="group" aria-label="Pilih mode tampilan menu">
                            <p class="px-2 pb-2 pt-1 text-[9px] font-extrabold uppercase tracking-[.15em] text-slate-400">Mode tampilan</p>
                        <button type="button" x-on:click="setView('visual')" :aria-pressed="viewMode==='visual'" :class="viewMode==='visual' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="flex h-9 w-full items-center gap-2 rounded-xl px-3 text-xs font-bold transition" aria-label="Mode visual"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg><span>Visual</span></button>
                        <button type="button" x-on:click="setView('balanced')" :aria-pressed="viewMode==='balanced'" :class="viewMode==='balanced' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="mt-1 flex h-9 w-full items-center gap-2 rounded-xl px-3 text-xs font-bold transition" aria-label="Mode seimbang"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="7" height="7" rx="1"/><path d="M13 6h8M13 10h6M3 16h18M3 20h13"/></svg><span>Seimbang</span></button>
                        <button type="button" x-on:click="setView('compact')" :aria-pressed="viewMode==='compact'" :class="viewMode==='compact' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="mt-1 flex h-9 w-full items-center gap-2 rounded-xl px-3 text-xs font-bold transition" aria-label="Mode ringkas"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M8 6h13M8 12h13M8 18h13"/><circle cx="4" cy="6" r="1" fill="currentColor"/><circle cx="4" cy="12" r="1" fill="currentColor"/><circle cx="4" cy="18" r="1" fill="currentColor"/></svg><span>Ringkas</span></button>
                        </div>
                    </div>
                </div>
                @if($settings->show_search)<div x-show="searchOpen" x-transition x-cloak class="relative mt-2.5 md:hidden"><svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input x-ref="mobileSearch" x-model.debounce.150ms="search" type="search" maxlength="100" autocomplete="off" placeholder="Cari makanan atau minuman..." class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-10 text-sm outline-none focus:border-[var(--menu-primary)]"><button type="button" x-on:click="search=''; searchOpen=false" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" aria-label="Tutup pencarian">✕</button></div>@endif
            </div>
        </div>

        <div class="mx-auto max-w-6xl space-y-10 px-4 py-8 sm:px-6 sm:py-12">
            @foreach($categories as $category)
                @php
                    $products = $category->products->filter(
                        fn ($product) => $settings->show_unavailable || $product->stock_status !== 'habis'
                    );
                @endphp
                @if($products->isNotEmpty())
                    <section x-show="category==='all' || category===@js($category->slug)" x-cloak>
                        <div class="mb-5 flex items-end justify-between gap-4"><div><p class="text-[10px] font-bold uppercase tracking-[.18em]" style="color:var(--menu-primary)">Kategori</p><h2 class="mt-1 text-xl font-extrabold tracking-tight sm:text-2xl">{{ $category->menu_display_name ?: $category->name }}</h2></div><span class="text-xs text-slate-400">{{ $products->count() }} pilihan</span></div>
                        <div class="grid" :class="viewMode==='visual' ? 'gap-5 sm:grid-cols-2 lg:grid-cols-3' : (viewMode==='balanced' ? 'gap-4 md:grid-cols-2' : 'gap-2.5 md:grid-cols-2 xl:grid-cols-3')">
                            @foreach($products as $product)
                                @php
                                    $short = $product->menu_short_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 120);
                                    $thumb = $product->resolvedImageUrl('thumb');
                                    $productData = ['name'=>$product->name,'category'=>$category->menu_display_name ?: $category->name,'price'=>'Rp '.number_format((float)$product->price,0,',','.'),'description'=>trim(strip_tags($product->description)),'short'=>$short,'image'=>$product->resolvedImageUrl('large'),'stock'=>$product->stock_status,'badge'=>$product->menu_badge ?: ($product->is_bestseller ? 'Terlaris' : ($product->is_featured ? 'Pilihan' : null))];
                                @endphp
                                <article x-show="search==='' || @js(mb_strtolower($product->name.' '.$short)).includes(search.toLowerCase())" :class="viewMode==='visual' ? 'block rounded-[20px]' : (viewMode==='balanced' ? 'flex items-stretch rounded-2xl' : 'flex items-stretch rounded-xl')" class="group overflow-hidden border border-slate-200/80 bg-white shadow-[0_8px_28px_rgba(15,23,42,.045)] transition duration-300 hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-[0_18px_40px_rgba(15,23,42,.09)]">
                                    @if($settings->show_images)
                                        <button type="button" x-on:click="openProduct(@js($productData))" :class="viewMode==='visual' ? 'aspect-[4/3] w-full' : (viewMode==='balanced' ? 'min-h-32 w-28 shrink-0 sm:min-h-40 sm:w-44' : 'h-24 w-24 shrink-0')" class="relative block overflow-hidden bg-slate-100" aria-label="Lihat detail {{ $product->name }}">
                                            @if($thumb)<img src="{{ $thumb }}" alt="{{ $product->name }}" width="300" height="300" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.025]">@else<div class="flex h-full items-center justify-center text-3xl text-slate-300">◇</div>@endif
                                            @if($settings->show_badges && $productData['badge'])<span x-show="viewMode!=='compact'" class="absolute left-3 top-3 rounded-full px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-wide text-slate-950" style="background:var(--menu-accent)">{{ $productData['badge'] }}</span>@endif
                                        </button>
                                    @endif
                                    <div :class="viewMode==='compact' ? 'p-3' : (viewMode==='balanced' ? 'py-4 pl-4 pr-5 sm:p-5' : 'p-4 sm:p-5')" class="flex min-w-0 flex-1 flex-col">
                                        <button type="button" x-on:click="openProduct(@js($productData))" class="min-w-0 text-left"><h3 :class="viewMode==='compact' ? 'text-sm' : 'text-base'" class="line-clamp-2 break-words font-extrabold leading-snug text-slate-950">{{ $product->name }}</h3></button>
                                        @if($settings->show_descriptions && $short)<p x-show="viewMode!=='compact'" class="mt-2 line-clamp-2 text-xs leading-5 text-slate-500">{{ $short }}</p>@endif
                                        <div :class="viewMode==='compact' ? 'pt-2' : 'pt-4'" class="mt-auto flex flex-wrap items-end justify-between gap-x-3 gap-y-1.5"><p :class="viewMode==='compact' ? 'text-sm' : 'text-base'" class="whitespace-nowrap font-extrabold" style="color:var(--menu-primary)">Rp {{ number_format((float)$product->price,0,',','.') }}</p>@if($settings->show_stock)<span class="shrink-0 text-[10px] font-bold {{ $product->stock_status === 'habis' ? 'text-rose-600' : 'text-emerald-700' }}">{{ ucfirst($product->stock_status) }}</span>@endif</div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach

            @if($settings->cta_enabled && $settings->safeCtaUrl())
                <a href="{{ $settings->safeCtaUrl() }}" class="fixed inset-x-4 bottom-[max(1rem,env(safe-area-inset-bottom))] z-30 mx-auto flex min-h-12 max-w-md items-center justify-center rounded-2xl px-5 text-sm font-extrabold text-white shadow-[0_18px_45px_rgba(15,23,42,.28)]" style="background:var(--menu-primary)">{{ $settings->cta_label ?: 'Hubungi Kami' }}</a>
            @endif
        </div>
    </main>

    <footer class="border-t border-slate-200 bg-white px-4 py-8 text-center text-xs text-slate-500"><p class="font-bold text-slate-700">{{ $business?->business_name }}</p><p class="mt-1">Harga dan ketersediaan dapat berubah. Silakan konfirmasi kepada staf.</p></footer>

    <div x-show="selected" x-cloak class="fixed inset-0 z-50" role="dialog" aria-modal="true" aria-labelledby="menu-detail-title">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" x-on:click="closeProduct()"></div>
        <div class="absolute inset-x-0 bottom-0 mx-auto max-h-[88vh] max-w-2xl overflow-y-auto rounded-t-[28px] bg-white shadow-2xl sm:bottom-6 sm:rounded-[28px]">
            <button type="button" x-on:click="closeProduct()" class="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-slate-600 shadow" aria-label="Tutup">✕</button>
            <template x-if="selected?.image"><img :src="selected.image" :alt="selected.name" class="aspect-[16/10] w-full object-cover"></template>
            <div class="p-6 sm:p-8"><p x-text="selected?.category" class="text-[10px] font-bold uppercase tracking-[.18em]" style="color:var(--menu-primary)"></p><h2 id="menu-detail-title" x-text="selected?.name" class="mt-2 pr-10 text-2xl font-extrabold"></h2><p x-text="selected?.price" class="mt-3 text-xl font-extrabold" style="color:var(--menu-primary)"></p><p x-text="selected?.description" class="mt-5 whitespace-pre-line text-sm leading-7 text-slate-600"></p><div class="mt-6 border-t border-slate-100 pt-4 text-xs font-bold text-slate-500">Status: <span x-text="selected?.stock"></span></div></div>
        </div>
    </div>
</div>
@endsection
