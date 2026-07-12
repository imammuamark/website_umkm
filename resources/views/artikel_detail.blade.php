@extends('layouts.app')

@section('title', ($article->meta_title ?: $article->title) . ' | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))
@section('meta_description', $article->meta_description ?: $article->excerpt)

@section('content')
@include('partials.page_hero', ['eyebrow' => $article->category?->name ?? 'Jurnal Panama Corner', 'title' => $article->title, 'subtitle' => $article->excerpt])

<section class="public-page-content py-20 bg-[#f7f8f7] flex-grow">
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Category & Title -->
        <div class="premium-surface space-y-4 rounded-2xl bg-white p-6 text-center">
            <span class="inline-flex items-center gap-x-1.5 rounded-md bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">
                {{ $article->category->name }}
            </span>
            <!-- Metadata -->
            <div class="flex items-center justify-center gap-6 text-xs text-gray-500 pt-2">
                <span class="flex items-center gap-1.5">
                    👤 {{ $article->author->name }}
                </span>
                <span class="flex items-center gap-1.5">
                    📅 {{ $article->published_at?->format('d M Y') }}
                </span>
                <span class="flex items-center gap-1.5">
                    ⏱️ {{ $article->reading_time }} Menit Baca
                </span>
            </div>
        </div>

        <hr class="border-gray-100" />

        <!-- Featured Image -->
        @php
            $largeImage = $article->getFirstMediaUrl('featured_image', 'large');
        @endphp
        @if($largeImage)
            <div class="aspect-video bg-gray-50 rounded-3xl overflow-hidden border border-gray-100">
                <img src="{{ $largeImage }}" alt="{{ $article->title }}" class="object-cover w-full h-full" />
            </div>
        @endif

        <!-- Content Area -->
        <div class="premium-surface prose-custom max-w-none rounded-3xl bg-white p-7 text-base leading-relaxed text-gray-700 sm:p-10 sm:text-lg space-y-6">
            {!! $article->content !!}
        </div>

        <!-- Custom Styling for Rich Text inside prose-custom -->
        <style>
            .prose-custom h2 {
                font-size: 1.5rem;
                font-weight: 700;
                color: #111827;
                margin-top: 2rem;
                margin-bottom: 1rem;
                font-family: var(--font-title);
            }
            .prose-custom h3 {
                font-size: 1.25rem;
                font-weight: 600;
                color: #111827;
                margin-top: 1.5rem;
                margin-bottom: 0.75rem;
                font-family: var(--font-title);
            }
            .prose-custom p {
                margin-bottom: 1.25rem;
            }
            .prose-custom ul {
                list-style-type: disc;
                padding-left: 1.5rem;
                margin-bottom: 1.25rem;
            }
            .prose-custom ol {
                list-style-type: decimal;
                padding-left: 1.5rem;
                margin-bottom: 1.25rem;
            }
            .prose-custom li {
                margin-bottom: 0.5rem;
            }
            .prose-custom blockquote {
                border-left: 4px solid var(--color-primary);
                padding-left: 1.25rem;
                font-style: italic;
                color: #4b5563;
                margin: 1.5rem 0;
            }
        </style>

        <hr class="border-gray-100 my-12" />

        <!-- Related Articles -->
        @if($relatedArticles->count() > 0)
            <div class="space-y-6 pt-4">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 font-title">Artikel Terkait</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedArticles as $relArticle)
                        <div class="premium-product-card bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col h-full group">
                            <div class="aspect-video bg-gray-50 relative overflow-hidden flex items-center justify-center">
                                @php
                                    $relThumb = $relArticle->getFirstMediaUrl('featured_image', 'thumb');
                                @endphp
                                @if($relThumb)
                                    <img src="{{ $relThumb }}" alt="{{ $relArticle->title }}" class="object-cover w-full h-full">
                                @else
                                    <div class="text-primary/20 text-3xl">📄</div>
                                @endif
                            </div>
                            
                            <div class="p-4 flex-grow flex flex-col space-y-2">
                                <span class="text-[9px] text-gray-400 font-semibold tracking-wider uppercase">{{ $relArticle->category->name }}</span>
                                <h4 class="font-bold text-sm text-gray-900 font-title line-clamp-2 group-hover:text-primary transition">
                                    <a href="{{ route('artikel.detail', $relArticle->slug) }}">{{ $relArticle->title }}</a>
                                </h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </article>
</section>
@endsection
