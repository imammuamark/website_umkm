@extends('layouts.app')

@section('title', 'Jurnal & Cerita | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))

@section('content')
@include('partials.page_hero', array_merge(['eyebrow' => 'Jurnal Panama Corner', 'title' => 'Info, tips, dan kabar terbaru.', 'subtitle' => 'Baca informasi seputar menu, kopi, kegiatan, dan kabar terbaru dari Panama Corner.'], \App\Models\SiteSetting::pageHero('articles')))

<section class="public-page-content flex-grow px-5 py-16 sm:px-8 lg:px-12 lg:py-24 xl:px-16">
    <div class="mx-auto max-w-[1320px]">
        <form action="{{ route('artikel') }}" method="GET" class="home-reveal relative z-30 mb-12 grid gap-4 border-b border-[#d4d0c5] pb-8 md:grid-cols-[minmax(0,1fr)_auto] md:items-end">
            <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_220px]">
                <div>
                    <label for="q" class="mb-2 block text-[10px] font-bold uppercase tracking-[.18em] text-[#66716c]">Cari jurnal</label>
                    <div class="relative"><svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#89918d]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input type="search" name="q" id="q" value="{{ request('q') }}" maxlength="100" class="min-h-12 w-full rounded-full border-[#d4d0c5] bg-white pl-11 pr-4 text-sm" placeholder="Cari judul atau topik..."></div>
                </div>
                <div
                    x-data="{
                        open: false,
                        value: @js((string) request('category', '')),
                        label: @js($categories->firstWhere('slug', request('category'))?->name ?? 'Semua kategori'),
                        choose(value, label) { this.value = value; this.label = label; this.open = false; }
                    }"
                    @click.outside="open = false"
                    @keydown.escape.window="open = false"
                    class="relative"
                >
                    <label id="article-category-label" class="mb-2 block text-[10px] font-bold uppercase tracking-[.18em] text-[#66716c]">Kategori</label>
                    <input type="hidden" name="category" :value="value">
                    <button
                        type="button"
                        @click="open = !open"
                        :aria-expanded="open.toString()"
                        aria-haspopup="listbox"
                        aria-labelledby="article-category-label article-category-value"
                        class="group flex min-h-12 w-full items-center justify-between gap-4 rounded-full border border-[#d4d0c5] bg-white py-2 pl-4 pr-2 text-left shadow-[0_5px_18px_rgba(16,37,31,.035)] transition duration-200 hover:border-primary/35 hover:shadow-[0_8px_22px_rgba(16,37,31,.07)] focus-visible:border-primary focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-primary/10"
                    >
                        <span class="flex min-w-0 items-center gap-3">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#f1eee6] text-primary transition group-hover:bg-primary/10" aria-hidden="true">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                            </span>
                            <span id="article-category-value" x-text="label" class="truncate text-sm font-medium text-[#26332e]"></span>
                        </span>
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#10251f] text-white transition duration-200" :class="open ? 'rotate-180 bg-primary' : ''" aria-hidden="true">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m5 7.5 5 5 5-5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </button>

                    <div
                        x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2 scale-[.98]"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 -translate-y-1 scale-[.98]"
                        class="absolute left-0 right-0 top-[calc(100%+.65rem)] z-40 overflow-hidden rounded-[1.25rem] border border-[#d9d5cb] bg-white/95 p-2 shadow-[0_24px_60px_rgba(16,37,31,.16)] backdrop-blur-xl"
                        role="listbox"
                        aria-labelledby="article-category-label"
                    >
                        <button type="button" @click="choose('', 'Semua kategori')" role="option" :aria-selected="(value === '').toString()" class="flex min-h-11 w-full items-center justify-between rounded-xl px-3 text-left text-sm transition hover:bg-[#f3f0e8]" :class="value === '' ? 'bg-[#10251f] text-white' : 'text-[#4f5a55]'">
                            <span>Semua kategori</span><span class="text-[9px] font-bold uppercase tracking-[.12em] opacity-60">{{ $categories->count() }} topik</span>
                        </button>
                        <div class="my-1 h-px bg-[#ebe8e0]"></div>
                        @foreach($categories as $category)
                            <button type="button" @click="choose(@js($category->slug), @js($category->name))" role="option" :aria-selected="(value === @js($category->slug)).toString()" class="flex min-h-11 w-full items-center justify-between rounded-xl px-3 text-left text-sm transition hover:bg-[#f3f0e8] hover:text-primary" :class="value === @js($category->slug) ? 'bg-primary/8 font-semibold text-primary' : 'text-[#4f5a55]'">
                                <span>{{ $category->name }}</span><span x-show="value === @js($category->slug)" class="flex h-5 w-5 items-center justify-center rounded-full bg-primary text-[10px] text-white" aria-hidden="true">✓</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                @if(request()->filled('q') || request()->filled('category'))<a href="{{ route('artikel') }}" class="inline-flex min-h-12 items-center rounded-full border border-[#c8c5bc] px-5 text-xs font-bold text-[#48534e]">Reset</a>@endif
                <button type="submit" class="inline-flex min-h-12 items-center gap-3 rounded-full bg-[#10251f] px-6 text-xs font-bold text-white transition hover:-translate-y-0.5 hover:bg-primary">Terapkan <span>→</span></button>
            </div>
        </form>

        @if($articles->isNotEmpty())
            <div class="grid gap-x-7 gap-y-12 md:grid-cols-2 lg:grid-cols-3">
                @foreach($articles as $article)
                    @php($thumb = $article->resolvedFeaturedImageUrl('thumb'))
                    <article class="home-reveal group">
                        <a href="{{ route('artikel.detail', $article->slug) }}" class="block aspect-[4/3] overflow-hidden rounded-[1.35rem] bg-[#17362f]">
                            @if($thumb)<img src="{{ $thumb }}" alt="{{ $article->featured_image_alt ?: $article->title }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy" decoding="async">@else<div class="flex h-full items-center justify-center text-white/25"><svg class="h-14 w-14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M5 4h14v16H5zM8 8h8M8 12h8M8 16h5"/></svg></div>@endif
                        </a>
                        <div class="pt-5">
                            <p class="text-[9px] font-bold uppercase tracking-[.18em] text-primary">{{ $article->category?->name ?? 'Cerita' }} · {{ $article->published_at?->translatedFormat('d M Y') }}</p>
                            <h2 class="mt-3 text-xl font-semibold leading-7 tracking-[-.025em] text-[#10251f]"><a href="{{ route('artikel.detail', $article->slug) }}" class="transition hover:text-primary">{{ $article->title }}</a></h2>
                            <p class="mt-3 line-clamp-2 text-sm leading-6 text-[#68736e]">{{ $article->excerpt }}</p>
                            <div class="mt-5 flex items-center justify-between border-t border-[#dcd8cf] pt-4 text-[10px] text-[#7c8581]"><span>{{ $article->reading_time ?: 1 }} menit baca</span><span class="font-bold text-[#10251f] transition group-hover:translate-x-1">Baca →</span></div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-14">{{ $articles->links() }}</div>
        @else
            <div class="home-reveal rounded-[1.75rem] border border-dashed border-[#c9c6bd] bg-white/55 px-6 py-20 text-center"><h2 class="text-xl font-semibold text-[#10251f]">Artikel belum ditemukan</h2><p class="mt-2 text-sm text-[#68736e]">Coba kata kunci atau kategori yang berbeda.</p><a href="{{ route('artikel') }}" class="mt-6 inline-flex rounded-full bg-[#10251f] px-5 py-3 text-xs font-bold text-white">Tampilkan semua</a></div>
        @endif
    </div>
</section>
@endsection
