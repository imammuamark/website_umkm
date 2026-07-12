@extends('layouts.app')

@section('title', ($article->meta_title ?: $article->title) . ' | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))
@section('meta_description', $article->meta_description ?: $article->excerpt)
@section('og_image', $article->resolvedFeaturedImageUrl('large') ?: asset('images/og-default.jpg'))

@section('content')
@include('partials.page_hero', [
    'eyebrow' => $article->category?->name ?? 'Artikel',
    'title' => $article->title,
    'subtitle' => $article->excerpt,
])

<section class="public-page-content flex-grow py-10 sm:py-14 lg:py-20">
    <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
        <div class="home-reveal mb-8 flex flex-wrap items-center gap-x-5 gap-y-2 border-b border-[#d4d0c5] px-1 pb-6 text-sm text-[#68736e] sm:px-2">
            <span class="inline-flex items-center gap-2 font-semibold text-slate-900">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary" aria-hidden="true">✦</span>
                {{ $article->author?->name ?? 'Tim Editorial' }}
            </span>
            <span aria-hidden="true" class="hidden h-1 w-1 rounded-full bg-slate-300 sm:block"></span>
            <time datetime="{{ $article->published_at?->toDateString() }}">{{ $article->published_at?->translatedFormat('d F Y') }}</time>
            <span aria-hidden="true" class="hidden h-1 w-1 rounded-full bg-slate-300 sm:block"></span>
            <span>{{ $article->reading_time ?: 1 }} menit baca</span>
        </div>

        @if(count($tableOfContents) > 0)
            <details x-data x-ref="mobileToc" class="group sticky top-[4.75rem] z-30 mb-6 rounded-2xl border border-slate-200/90 bg-white/95 shadow-[0_12px_35px_rgba(15,23,42,.12)] backdrop-blur-xl lg:hidden">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 font-bold text-slate-950 [&::-webkit-details-marker]:hidden">
                    <span class="flex min-w-0 items-center gap-2.5">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary" aria-hidden="true">☷</span>
                        <span>Daftar isi</span>
                    </span>
                    <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform duration-200 group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                    </svg>
                </summary>
                <nav aria-label="Daftar isi artikel" class="max-h-[min(60vh,26rem)] overflow-y-auto overscroll-contain border-t border-slate-100 px-5 py-4">
                    <ol class="space-y-1.5 text-sm text-slate-600">
                        @foreach($tableOfContents as $item)
                            <li class="{{ $item['level'] === 3 ? 'pl-4' : '' }}">
                                <a href="#{{ $item['id'] }}" x-on:click="$refs.mobileToc.removeAttribute('open')" class="block rounded-lg px-2.5 py-2 leading-snug transition hover:bg-primary/5 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/30">{{ $item['title'] }}</a>
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </details>
        @endif

        <div class="grid items-start gap-8 lg:grid-cols-[210px_minmax(0,1fr)] xl:grid-cols-[220px_minmax(0,820px)_280px] xl:justify-between">
            <aside class="sticky top-28 hidden lg:block" aria-label="Navigasi artikel">
                @if(count($tableOfContents) > 0)
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                        <p class="mb-4 text-xs font-extrabold uppercase tracking-[0.16em] text-primary">Dalam artikel</p>
                        <nav aria-label="Daftar isi artikel">
                            <ol class="space-y-3 border-l border-slate-200 text-sm leading-snug text-slate-600">
                                @foreach($tableOfContents as $item)
                                    <li class="{{ $item['level'] === 3 ? 'pl-7' : 'pl-4' }}">
                                        <a href="#{{ $item['id'] }}" class="block transition hover:text-primary">{{ $item['title'] }}</a>
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                @else
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 text-sm leading-relaxed text-slate-600 shadow-sm">
                        Artikel ini dapat dibaca dalam sekitar <strong class="text-slate-900">{{ $article->reading_time ?: 1 }} menit</strong>.
                    </div>
                @endif
            </aside>

            <main class="min-w-0 space-y-7">
                @php($largeImage = $article->resolvedFeaturedImageUrl('large'))
                @if($largeImage)
                    <figure class="home-reveal overflow-hidden rounded-[1.75rem] border border-white bg-slate-100 shadow-[0_20px_60px_rgba(16,37,31,.09)]">
                        <img src="{{ $largeImage }}" alt="{{ $article->featured_image_alt ?: $article->title }}" width="1200" height="630" fetchpriority="high" class="aspect-[16/9] h-auto w-full object-cover">
                        @if($article->featured_image_credit)
                            <figcaption class="border-t border-slate-100 bg-white px-5 py-3 text-[10px] text-slate-500">
                                Sumber:
                                @if($article->featured_image_credit_url)<a href="{{ $article->featured_image_credit_url }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary hover:underline">{{ $article->featured_image_credit }}</a>@else<span>{{ $article->featured_image_credit }}</span>@endif
                            </figcaption>
                        @endif
                    </figure>
                @endif

                <article class="article-prose editorial-surface home-reveal rounded-[1.75rem] p-6 text-slate-700 sm:p-9 lg:p-12">
                    {!! $articleContent !!}
                </article>

                @if($articleGallery->isNotEmpty())
                    <section aria-labelledby="article-gallery-title" class="rounded-[28px] border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8">
                        <div class="mb-6">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-primary">Dokumentasi</p>
                            <h2 id="article-gallery-title" class="mt-1 font-title text-2xl font-extrabold text-slate-950">Galeri artikel</h2>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach($articleGallery as $image)
                                <figure class="overflow-hidden rounded-2xl bg-slate-100 {{ $loop->first && $articleGallery->count() % 2 === 1 ? 'sm:col-span-2' : '' }}">
                                    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?: $article->title }}" width="1440" height="960" loading="lazy" decoding="async" class="aspect-[3/2] h-auto w-full object-cover transition duration-500 hover:scale-[1.02]">
                                    @if($image['caption'])<figcaption class="bg-white px-4 py-3 text-xs leading-5 text-slate-500">{{ $image['caption'] }}</figcaption>@endif
                                </figure>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($articleVideos->isNotEmpty())
                    <section aria-labelledby="article-video-title" class="space-y-5">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-primary">Media pendukung</p>
                            <h2 id="article-video-title" class="mt-1 font-title text-2xl font-extrabold text-slate-950">Video terkait</h2>
                        </div>
                        @foreach($articleVideos as $video)
                            <figure class="overflow-hidden rounded-[28px] border border-slate-200 bg-slate-950 shadow-lg">
                                <div class="aspect-video">
                                    <iframe src="{{ $video['embed_url'] }}" title="{{ $video['title'] ?: 'Video '.$video['provider'] }}" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="h-full w-full border-0"></iframe>
                                </div>
                                @if($video['title'])
                                    <figcaption class="bg-slate-900 px-5 py-4 text-sm font-semibold text-white">{{ $video['title'] }}</figcaption>
                                @endif
                            </figure>
                        @endforeach
                    </section>
                @endif
            </main>

            <aside class="space-y-5 lg:col-start-2 xl:col-start-auto xl:sticky xl:top-28" aria-label="Artikel terkait">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-primary">Topik</p>
                    <p class="mt-2 font-title text-lg font-extrabold text-slate-950">{{ $article->category?->name }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">Temukan wawasan lain dari kategori yang sama.</p>
                    <a href="{{ route('artikel', ['category' => $article->category?->slug]) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-primary hover:underline">Lihat semua artikel <span aria-hidden="true">→</span></a>
                </div>

                @if($relatedArticles->isNotEmpty())
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                        <h2 class="font-title text-lg font-extrabold text-slate-950">Bacaan berikutnya</h2>
                        <div class="mt-4 divide-y divide-slate-100">
                            @foreach($relatedArticles as $related)
                                <a href="{{ route('artikel.detail', $related->slug) }}" class="group block py-4 first:pt-0 last:pb-0">
                                    <p class="line-clamp-2 text-sm font-bold leading-snug text-slate-800 transition group-hover:text-primary">{{ $related->title }}</p>
                                    <span class="mt-1.5 block text-xs text-slate-500">{{ $related->reading_time ?: 1 }} menit baca</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</section>

<style>
    .article-prose { color: #475569; font-size: 1rem; line-height: 1.78; }
    .article-prose > * + * { margin-top: 1.1rem; }
    .article-prose h2,
    .article-prose h3 {
        scroll-margin-top: 8.5rem;
        color: #0f172a;
        font-family: var(--font-title);
        font-weight: 750;
        letter-spacing: -.018em;
        line-height: 1.32;
    }
    .article-prose h2 {
        position: relative;
        margin-top: 2.4rem;
        border-top: 1px solid #e8eeec;
        padding-top: 1.65rem;
        padding-left: 1rem;
        font-size: clamp(1.3rem, 1.8vw, 1.55rem);
    }
    .article-prose h2::before {
        position: absolute;
        top: 1.82rem;
        left: 0;
        width: 3px;
        height: 1.1em;
        border-radius: 999px;
        background: var(--color-primary);
        content: '';
    }
    .article-prose h3 { margin-top: 1.8rem; font-size: clamp(1.12rem, 1.5vw, 1.28rem); }
    .article-prose p { max-width: 72ch; }
    .article-prose ul, .article-prose ol { padding-left: 1.5rem; }
    .article-prose ul { list-style: disc; }
    .article-prose ol { list-style: decimal; }
    .article-prose li + li { margin-top: .45rem; }
    .article-prose blockquote { border-left: 3px solid var(--color-primary); border-radius: 0 .75rem .75rem 0; background: #f4f7f6; padding: 1rem 1.25rem; color: #475569; font-size: .975rem; font-style: italic; line-height: 1.7; }
    .article-prose a { color: var(--color-primary); font-weight: 650; text-decoration: underline; text-underline-offset: 3px; }
    .article-prose img { height: auto; max-width: 100%; border-radius: 1rem; }
    .article-prose pre { overflow-x: auto; border-radius: 1rem; background: #0f172a; padding: 1.25rem; color: #e2e8f0; }
    @media (max-width: 639px) {
        .article-prose { font-size: .975rem; line-height: 1.72; }
        .article-prose h2 { margin-top: 2rem; padding-top: 1.4rem; font-size: 1.25rem; }
        .article-prose h2::before { top: 1.53rem; }
        .article-prose h3 { font-size: 1.1rem; }
        .article-prose blockquote { padding: .9rem 1rem; font-size: .925rem; }
    }
</style>
@endsection
