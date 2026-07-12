@extends('layouts.app')

@section('title', 'Katalog Kopi & Alat Seduh | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))

@section('content')
@include('partials.page_hero', ['eyebrow' => 'Kurasi Pilihan', 'title' => 'Katalog Produk', 'subtitle' => 'Temukan kopi Nusantara pilihan dan peralatan seduh yang kami kurasi untuk pengalaman terbaik di setiap cangkir.'])

@php
    $activeFilterCount = collect(['q', 'category', 'min_price', 'max_price'])
        ->filter(fn ($key) => request()->filled($key))
        ->count();
@endphp

<div
    x-data="{
        openModal: false,
        activeProduct: { name: '', price: '', description: '', category: '', image: '', waUrl: '', detailUrl: '', stock: '' },
        selectProduct(product) { this.activeProduct = product; this.openModal = true; document.body.style.overflow = 'hidden'; },
        closeModal() { this.openModal = false; document.body.style.overflow = ''; }
    }"
    @keydown.escape.window="closeModal()"
    class="public-page-content flex-grow bg-[#f4f5f3] py-16 lg:py-20"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-5 rounded-2xl border border-white/80 bg-white p-5 shadow-[0_12px_35px_rgba(15,23,42,.06)] sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[.18em] text-primary">Product Discovery</p>
                <p class="mt-1 text-sm text-gray-500"><strong class="text-gray-950">{{ $products->total() }}</strong> produk ditemukan{{ request('q') ? ' untuk “' . e(request('q')) . '”' : '' }}.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if($activeFilterCount > 0)
                    <span class="rounded-full bg-primary/8 px-3 py-1.5 text-[10px] font-bold text-primary">{{ $activeFilterCount }} filter aktif</span>
                    <a href="{{ route('produk') }}" class="rounded-full border border-gray-200 px-3 py-1.5 text-[10px] font-bold text-gray-600 transition hover:border-primary hover:text-primary">Reset Filter</a>
                @endif
                <span class="text-[10px] font-medium text-gray-400">Halaman {{ $products->currentPage() }} dari {{ max(1, $products->lastPage()) }}</span>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[270px_1fr]">
            <aside>
                <form action="{{ route('produk') }}" method="GET" class="premium-surface space-y-7 rounded-2xl border border-gray-100 bg-white p-6 lg:sticky lg:top-28">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-5">
                        <div><p class="text-[10px] font-bold uppercase tracking-[.16em] text-primary">Filter Produk</p><h2 class="mt-1 text-lg font-extrabold text-gray-950">Persempit pilihan</h2></div>
                        <svg aria-hidden="true" class="h-5 w-5 text-secondary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                    </div>

                    <div class="space-y-2">
                        <label for="q" class="text-[10px] font-bold uppercase tracking-[.14em] text-gray-500">Pencarian</label>
                        <div class="relative">
                            <svg aria-hidden="true" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                            <input type="search" name="q" id="q" value="{{ request('q') }}" maxlength="100" autocomplete="off" class="block min-h-11 w-full rounded-xl py-2 pl-10 pr-3 text-sm outline-none" placeholder="Nama produk...">
                        </div>
                    </div>

                    <fieldset>
                        <legend class="text-[10px] font-bold uppercase tracking-[.14em] text-gray-500">Kategori</legend>
                        <div class="mt-3 space-y-1.5">
                            <a href="{{ route('produk', request()->except(['category', 'page'])) }}" class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm transition {{ !request('category') ? 'bg-primary text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}"><span>Semua Produk</span><span class="text-[10px] opacity-65">{{ \App\Models\Product::count() }}</span></a>
                            @foreach($categories as $category)
                                <a href="{{ route('produk', array_merge(request()->except('page'), ['category' => $category->slug])) }}" class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm transition {{ request('category') === $category->slug ? 'bg-primary text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}"><span>{{ $category->name }}</span><span class="text-[10px] opacity-65">{{ $category->products_count + $category->children->sum('products_count') }}</span></a>
                                @if($category->children->isNotEmpty())
                                    <div class="ml-3 border-l border-gray-100 pl-3">
                                        @foreach($category->children as $child)
                                            <a href="{{ route('produk', array_merge(request()->except('page'), ['category' => $child->slug])) }}" class="flex items-center justify-between py-1.5 text-xs transition {{ request('category') === $child->slug ? 'font-bold text-primary' : 'text-gray-400 hover:text-primary' }}"><span>{{ $child->name }}</span><span>{{ $child->products_count }}</span></a>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </fieldset>

                    <div class="space-y-3">
                        <p class="text-[10px] font-bold uppercase tracking-[.14em] text-gray-500">Rentang Harga</p>
                        <div class="grid grid-cols-2 gap-2">
                            <div><label for="min_price" class="sr-only">Harga minimum</label><input type="number" min="0" step="1000" name="min_price" id="min_price" value="{{ request('min_price') }}" class="block min-h-10 w-full rounded-lg px-3 text-xs" placeholder="Minimum"></div>
                            <div><label for="max_price" class="sr-only">Harga maksimum</label><input type="number" min="0" step="1000" name="max_price" id="max_price" value="{{ request('max_price') }}" class="block min-h-10 w-full rounded-lg px-3 text-xs" placeholder="Maksimum"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="sort" class="text-[10px] font-bold uppercase tracking-[.14em] text-gray-500">Urutkan</label>
                        <select name="sort" id="sort" class="block min-h-11 w-full rounded-xl px-3 text-sm">
                            <option value="latest" @selected(request('sort', 'latest') === 'latest')>Produk Terbaru</option>
                            <option value="price_asc" @selected(request('sort') === 'price_asc')>Harga Terendah</option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>Harga Tertinggi</option>
                            <option value="popular" @selected(request('sort') === 'popular')>Paling Populer</option>
                        </select>
                    </div>

                    <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 text-sm font-bold text-white shadow-[0_10px_24px_rgba(15,118,110,.2)] transition hover:-translate-y-0.5 hover:brightness-105">
                        Terapkan Filter <span aria-hidden="true">→</span>
                    </button>
                </form>
            </aside>

            <section aria-label="Daftar produk">
                @if($products->isNotEmpty())
                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($products as $product)
                            @include('partials.product_card', ['product' => $product])
                        @endforeach
                    </div>
                    <div class="mt-10 rounded-xl bg-white px-5 py-2 shadow-sm">{{ $products->links() }}</div>
                @else
                    <div class="premium-surface rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center sm:p-16">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/8 text-primary"><svg aria-hidden="true" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg></span>
                        <h2 class="mt-5 text-xl font-extrabold text-gray-950">Produk tidak ditemukan</h2>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">Tidak ada produk yang sesuai dengan kombinasi filter saat ini. Coba kata kunci atau rentang harga lain.</p>
                        <a href="{{ route('produk') }}" class="mt-6 inline-flex min-h-11 items-center justify-center rounded-lg bg-secondary px-5 text-xs font-bold text-[#211b10]">Tampilkan Semua Produk</a>
                    </div>
                @endif
            </section>
        </div>
    </div>

    <div x-show="openModal" x-cloak class="fixed inset-0 z-[70] overflow-y-auto p-4 sm:p-8" role="dialog" aria-modal="true" aria-labelledby="quick-view-title">
        <div class="fixed inset-0 bg-[#07110f]/78 backdrop-blur-md" @click="closeModal()" aria-hidden="true"></div>
        <div class="relative mx-auto flex min-h-full max-w-4xl items-center justify-center">
            <div x-show="openModal" x-transition class="relative w-full overflow-hidden rounded-[26px] border border-white/80 bg-white shadow-[0_35px_100px_rgba(0,0,0,.35)]">
                <button @click="closeModal()" type="button" class="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white/90 text-gray-500 shadow-sm backdrop-blur transition hover:bg-gray-950 hover:text-white" aria-label="Tutup tampilan cepat">✕</button>
                <div class="grid md:grid-cols-2">
                    <div class="relative flex min-h-[330px] items-center justify-center bg-[#e9ece9] p-8 md:min-h-[520px]">
                        <template x-if="activeProduct.image"><img :src="activeProduct.image" :alt="activeProduct.name" class="max-h-[430px] w-full rounded-xl object-contain"></template>
                        <template x-if="!activeProduct.image"><svg aria-hidden="true" class="h-20 w-20 text-primary/20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M5 9h11v5a5 5 0 0 1-5 5h-1a5 5 0 0 1-5-5V9Z"/><path d="M16 11h1.5a2.5 2.5 0 0 1 0 5H16"/></svg></template>
                    </div>
                    <div class="flex flex-col justify-center p-7 sm:p-10">
                        <p x-text="activeProduct.category" class="text-[10px] font-extrabold uppercase tracking-[.17em] text-primary"></p>
                        <h2 id="quick-view-title" x-text="activeProduct.name" class="mt-3 text-2xl font-extrabold leading-tight text-gray-950 sm:text-3xl"></h2>
                        <p x-text="activeProduct.price" class="mt-4 text-xl font-extrabold text-secondary"></p>
                        <div class="my-6 h-px bg-gray-100"></div>
                        <p x-text="activeProduct.description" class="text-sm leading-7 text-gray-600"></p>
                        <div class="mt-8 grid gap-3 sm:grid-cols-2">
                            <a :href="activeProduct.detailUrl" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-primary text-sm font-bold text-primary transition hover:bg-primary hover:text-white">Detail Produk</a>
                            <a :href="activeProduct.waUrl" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-[#25d366] px-4 text-sm font-bold text-white transition hover:brightness-95">Pesan via WhatsApp</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
