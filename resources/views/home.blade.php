@extends('layouts.app')

@section('title', \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner') . ' | Beranda')
@section('meta_description', \App\Models\SiteSetting::get('meta_description_default', 'Kafe di Condongcatur dengan pilihan makanan dan minuman.'))

@section('content')
@php
    $heroImage = \App\Models\SiteSetting::homeHeroUrl() ?: asset('images/panama-roastery-hero.png');
    $businessName = $profile?->business_name ?? 'Panama Corner';
    $firstProduct = $featuredProducts->first();
    $sideProducts = $featuredProducts->skip(1)->take(2);
    $leadArticle = $latestArticles->first();
    $sideArticles = $latestArticles->skip(1)->take(2);
    $locationAddress = $primaryLocation?->address ?: 'Jl. Mancasan Indah III No.1, Condongcatur, Sleman';
@endphp

<section class="relative isolate min-h-[680px] overflow-hidden bg-[#07120f] text-white lg:min-h-[760px]">
    <img src="{{ $heroImage }}" alt="Suasana {{ $businessName }}" class="absolute inset-0 h-full w-full object-cover object-center" width="1920" height="1080" fetchpriority="high" decoding="async">
    <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(5,15,12,.94)_0%,rgba(5,15,12,.75)_38%,rgba(5,15,12,.24)_72%,rgba(5,15,12,.12)_100%)]"></div>
    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(3,10,8,.44)_0%,transparent_25%,transparent_62%,rgba(3,10,8,.78)_100%)]"></div>
    <div class="absolute inset-x-0 top-0 h-44 bg-gradient-to-b from-black/30 to-transparent"></div>

    <div class="relative mx-auto flex min-h-[680px] max-w-[1440px] items-end px-5 pb-16 pt-32 sm:px-8 sm:pb-20 lg:min-h-[760px] lg:px-12 lg:pb-24 xl:px-16">
        <div class="home-reveal max-w-[720px]">
            <div class="mb-7 flex items-center gap-4">
                <span class="h-px w-10 bg-secondary"></span>
                <p class="text-[11px] font-semibold uppercase tracking-[.24em] text-white/78">Cafe · Food · Coffee · Yogyakarta</p>
            </div>
            <h1 class="max-w-[700px] text-[2.75rem] font-semibold leading-[1.02] tracking-[-.045em] sm:text-[3.6rem] lg:text-[4.5rem]">
                {{ \App\Models\SiteSetting::get('hero_title', 'Makan enak, ngopi nyaman.') }}
            </h1>
            <p class="mt-7 max-w-[560px] text-[15px] leading-7 text-white/76 sm:text-base">
                {{ \App\Models\SiteSetting::get('hero_subtitle', 'Pilihan makanan, camilan, kopi, dan minuman nonkopi untuk makan santai, bekerja, atau berkumpul bersama.') }}
            </p>
            <div class="mt-9 flex flex-wrap items-center gap-4">
                <a href="{{ route('produk') }}" class="inline-flex min-h-12 items-center gap-3 rounded-full bg-secondary px-6 text-[13px] font-bold text-[#172019] shadow-[0_16px_36px_rgba(0,0,0,.22)] transition duration-300 hover:-translate-y-0.5 hover:brightness-105 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-secondary">
                    Jelajahi Sajian <span aria-hidden="true">↗</span>
                </a>
                <a href="{{ route('lokasi') }}" class="inline-flex min-h-12 items-center gap-3 rounded-full border border-white/35 bg-white/8 px-6 text-[13px] font-semibold text-white backdrop-blur-md transition duration-300 hover:border-white/65 hover:bg-white/14 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white">
                    Kunjungi Kami <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>

    <div class="absolute bottom-7 right-7 hidden items-center gap-3 text-[10px] uppercase tracking-[.2em] text-white/50 lg:flex">
        <span>Scroll untuk menjelajah</span><span class="block h-10 w-px bg-white/25"></span>
    </div>
</section>

<section aria-label="Informasi singkat" class="border-b border-[#d8d4ca] bg-[#f5f2eb]">
    <div class="mx-auto grid max-w-[1440px] grid-cols-1 sm:grid-cols-3 sm:px-8 lg:px-12 xl:px-16">
        @foreach([
            ['value' => '7 Hari', 'label' => 'Buka setiap pekan'],
            ['value' => ($stats['products_count'] ?? 0) . '+', 'label' => 'Pilihan sajian'],
            ['value' => max(1, $stats['locations_count'] ?? 0), 'label' => 'Lokasi untuk dikunjungi'],
        ] as $stat)
            <div class="home-reveal flex min-h-24 items-center gap-5 border-b border-[#d8d4ca] px-5 py-5 last:border-b-0 sm:border-b-0 sm:border-r sm:px-7 sm:last:border-r-0 lg:min-h-28 lg:px-10">
                <strong class="text-2xl font-semibold tracking-[-.04em] text-[#10251f] lg:text-3xl">{{ $stat['value'] }}</strong>
                <span class="max-w-28 text-[11px] font-medium uppercase leading-5 tracking-[.12em] text-[#68736e]">{{ $stat['label'] }}</span>
            </div>
        @endforeach
    </div>
</section>

<section class="bg-[#f5f2eb] px-5 py-20 sm:px-8 lg:px-12 lg:py-28 xl:px-16">
    <div class="mx-auto grid max-w-[1320px] items-center gap-12 lg:grid-cols-12 lg:gap-20">
        <div class="home-reveal relative lg:col-span-7">
            <div class="aspect-[4/3] overflow-hidden rounded-[1.75rem] bg-[#d9d5cb] shadow-[0_28px_70px_rgba(29,41,35,.16)]">
                @if($storyImage)
                    <img src="{{ $storyImage }}" alt="Suasana bersantai di {{ $businessName }}" class="h-full w-full object-cover" loading="lazy" decoding="async">
                @else
                    <img src="{{ asset('images/about-heritage.jpg') }}" alt="Suasana dan sajian {{ $businessName }}" class="h-full w-full object-cover" loading="lazy" decoding="async">
                @endif
            </div>
            <div class="absolute -bottom-6 right-5 rounded-2xl border border-white/65 bg-white/78 px-5 py-4 shadow-xl backdrop-blur-xl sm:right-8">
                <p class="text-[10px] font-bold uppercase tracking-[.18em] text-primary">Condongcatur</p>
                <p class="mt-1 text-sm font-semibold text-[#17231f]">Sleman, Yogyakarta</p>
            </div>
        </div>
        <div class="home-reveal lg:col-span-5">
            <p class="text-[11px] font-bold uppercase tracking-[.22em] text-primary">Tentang {{ $businessName }}</p>
            <h2 class="mt-5 text-3xl font-semibold leading-[1.12] tracking-[-.035em] text-[#10251f] sm:text-4xl lg:text-[2.75rem]">Menu lengkap, tempat nyaman.</h2>
            <p class="mt-7 text-[15px] leading-8 text-[#5c6762]">{{ $profile?->description ?? 'Panama Corner adalah kafe di Condongcatur dengan pilihan makanan, camilan, kopi, dan minuman nonkopi untuk dinikmati sendiri atau bersama teman.' }}</p>
            <div class="mt-8 grid grid-cols-2 gap-5 border-y border-[#d4d0c5] py-6">
                <div><p class="text-sm font-semibold text-[#10251f]">Sajian beragam</p><p class="mt-1 text-xs leading-5 text-[#707a75]">Dari makanan hingga minuman untuk berbagai waktu.</p></div>
                <div><p class="text-sm font-semibold text-[#10251f]">Tempat yang fleksibel</p><p class="mt-1 text-xs leading-5 text-[#707a75]">Cocok untuk makan, bekerja ringan, atau berkumpul.</p></div>
            </div>
            <a href="{{ route('profil') }}" class="mt-8 inline-flex items-center gap-3 text-[13px] font-bold text-[#10251f] transition hover:gap-4 hover:text-primary">Tentang Panama Corner <span aria-hidden="true">→</span></a>
        </div>
    </div>
</section>

<section class="bg-white px-5 py-20 sm:px-8 lg:px-12 lg:py-28 xl:px-16" aria-labelledby="featured-menu-title">
    <div class="mx-auto max-w-[1320px]">
        <div class="home-reveal mb-10 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between lg:mb-14">
            <div><p class="text-[11px] font-bold uppercase tracking-[.22em] text-primary">Pilihan dari dapur</p><h2 id="featured-menu-title" class="mt-4 text-3xl font-semibold tracking-[-.04em] text-[#10251f] sm:text-4xl lg:text-[2.75rem]">Coba menu pilihan kami.</h2></div>
            <a href="{{ route('produk') }}" class="inline-flex w-fit items-center gap-3 border-b border-[#10251f] pb-2 text-[12px] font-bold uppercase tracking-[.12em] text-[#10251f] transition hover:border-primary hover:text-primary">Lihat seluruh menu <span>↗</span></a>
        </div>

        @if($firstProduct)
            <div class="grid gap-5 lg:grid-cols-12">
                <article class="home-reveal group relative min-h-[470px] overflow-hidden rounded-[1.75rem] bg-[#10251f] lg:col-span-7 lg:min-h-[620px]">
                    @if($firstProduct->resolvedImageUrl('large'))<img src="{{ $firstProduct->resolvedImageUrl('large') }}" alt="{{ $firstProduct->name }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-[1.035]" loading="lazy" decoding="async">@endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/12 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-7 text-white sm:p-9">
                        <p class="text-[10px] font-bold uppercase tracking-[.2em] text-secondary">{{ $firstProduct->category?->name ?? 'Pilihan menu' }}</p>
                        <div class="mt-3 flex items-end justify-between gap-6"><h3 class="text-2xl font-semibold tracking-[-.03em] sm:text-3xl">{{ $firstProduct->name }}</h3><p class="shrink-0 text-sm font-bold">{{ $firstProduct->price ? 'Rp '.number_format($firstProduct->price, 0, ',', '.') : 'Tanya harga' }}</p></div>
                        <a href="{{ route('produk.detail', $firstProduct->slug) }}" class="absolute inset-0"><span class="sr-only">Lihat {{ $firstProduct->name }}</span></a>
                    </div>
                </article>
                <div class="grid gap-5 sm:grid-cols-2 lg:col-span-5 lg:grid-cols-1">
                    @foreach($sideProducts as $product)
                        <article class="home-reveal group relative min-h-[290px] overflow-hidden rounded-[1.75rem] bg-[#e8e4da]">
                            @if($product->resolvedImageUrl('large'))<img src="{{ $product->resolvedImageUrl('large') }}" alt="{{ $product->name }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy" decoding="async">@endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/5 to-transparent"></div>
                            <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-5 p-6 text-white">
                                <div><p class="text-[9px] font-bold uppercase tracking-[.18em] text-secondary">{{ $product->category?->name ?? 'Menu' }}</p><h3 class="mt-2 text-lg font-semibold">{{ $product->name }}</h3></div>
                                <p class="shrink-0 text-xs font-bold">{{ $product->price ? 'Rp '.number_format($product->price, 0, ',', '.') : 'Tanya harga' }}</p>
                                <a href="{{ route('produk.detail', $product->slug) }}" class="absolute inset-0"><span class="sr-only">Lihat {{ $product->name }}</span></a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @else
            <div class="rounded-3xl border border-dashed border-[#d3d0c7] bg-[#f7f5ef] p-12 text-center text-sm text-[#68736e]">Sajian pilihan sedang dipersiapkan.</div>
        @endif
    </div>
</section>

<section class="relative isolate overflow-hidden bg-[#0b2c25] px-5 py-24 text-white sm:px-8 lg:px-12 lg:py-32 xl:px-16">
    <div class="absolute -right-24 -top-36 h-[520px] w-[520px] rounded-full border border-white/8"></div><div class="absolute -right-5 -top-10 h-[320px] w-[320px] rounded-full border border-secondary/15"></div>
    <div class="home-reveal relative mx-auto max-w-5xl text-center">
        <p class="text-[10px] font-semibold uppercase tracking-[.26em] text-secondary">Panama Corner</p>
        <blockquote class="mt-7 text-3xl font-medium leading-[1.2] tracking-[-.04em] text-white sm:text-4xl lg:text-5xl">Mau makan, ngopi, kerja sebentar, atau kumpul bareng? Pilih meja yang nyaman dan pesan menu favoritmu.</blockquote>
        <div class="mx-auto mt-9 h-px w-16 bg-secondary"></div>
    </div>
</section>

<section class="bg-[#f5f2eb] px-5 py-20 sm:px-8 lg:px-12 lg:py-28 xl:px-16" aria-labelledby="journal-title">
    <div class="mx-auto max-w-[1320px]">
        <div class="home-reveal mb-10 flex items-end justify-between gap-6 lg:mb-14">
            <div><p class="text-[11px] font-bold uppercase tracking-[.22em] text-primary">Jurnal & cerita</p><h2 id="journal-title" class="mt-4 text-3xl font-semibold tracking-[-.04em] text-[#10251f] sm:text-4xl lg:text-[2.75rem]">Catatan dari Panama Corner.</h2></div>
            <a href="{{ route('artikel') }}" class="hidden text-[12px] font-bold uppercase tracking-[.12em] text-[#10251f] transition hover:text-primary sm:inline-flex sm:items-center sm:gap-3">Semua artikel <span>→</span></a>
        </div>

        @if($leadArticle)
            <div class="grid gap-8 lg:grid-cols-12 lg:gap-12">
                <article class="home-reveal group lg:col-span-7">
                    <a href="{{ route('artikel.detail', $leadArticle->slug) }}" class="block aspect-[16/10] overflow-hidden rounded-[1.5rem] bg-[#16352e]">
                        @if($leadArticle->getFirstMediaUrl('featured_image', 'large') ?: $leadArticle->getFirstMediaUrl('featured_image', 'thumb'))<img src="{{ $leadArticle->getFirstMediaUrl('featured_image', 'large') ?: $leadArticle->getFirstMediaUrl('featured_image', 'thumb') }}" alt="{{ $leadArticle->title }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.035]" loading="lazy" decoding="async">@endif
                    </a>
                    <p class="mt-6 text-[10px] font-bold uppercase tracking-[.18em] text-primary">{{ $leadArticle->category?->name ?? 'Cerita' }} · {{ $leadArticle->published_at?->translatedFormat('d M Y') }}</p>
                    <h3 class="mt-3 text-2xl font-semibold leading-tight tracking-[-.03em] text-[#10251f] sm:text-3xl"><a href="{{ route('artikel.detail', $leadArticle->slug) }}" class="transition hover:text-primary">{{ $leadArticle->title }}</a></h3>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-[#68736e]">{{ $leadArticle->excerpt }}</p>
                </article>
                <div class="lg:col-span-5">
                    @foreach($sideArticles as $article)
                        <article class="home-reveal group grid grid-cols-[116px_1fr] gap-5 border-b border-[#d4d0c5] py-6 first:pt-0 sm:grid-cols-[160px_1fr]">
                            <a href="{{ route('artikel.detail', $article->slug) }}" class="aspect-square overflow-hidden rounded-xl bg-[#16352e]">
                                @if($article->getFirstMediaUrl('featured_image', 'thumb'))<img src="{{ $article->getFirstMediaUrl('featured_image', 'thumb') }}" alt="{{ $article->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" decoding="async">@endif
                            </a>
                            <div class="self-center"><p class="text-[9px] font-bold uppercase tracking-[.16em] text-primary">{{ $article->category?->name ?? 'Cerita' }}</p><h3 class="mt-2 text-base font-semibold leading-6 text-[#10251f] sm:text-lg"><a href="{{ route('artikel.detail', $article->slug) }}" class="transition hover:text-primary">{{ $article->title }}</a></h3><p class="mt-3 text-[10px] text-[#7c8581]">{{ $article->reading_time ?? 1 }} menit baca</p></div>
                        </article>
                    @endforeach
                    <a href="{{ route('artikel') }}" class="mt-7 inline-flex items-center gap-3 text-[12px] font-bold uppercase tracking-[.12em] text-[#10251f] sm:hidden">Semua artikel <span>→</span></a>
                </div>
            </div>
        @else
            <div class="rounded-3xl border border-dashed border-[#d3d0c7] p-12 text-center text-sm text-[#68736e]">Cerita terbaru sedang disiapkan.</div>
        @endif
    </div>
</section>

<section class="bg-white px-5 py-10 sm:px-8 lg:px-12 lg:py-16 xl:px-16">
    <div class="home-reveal mx-auto grid max-w-[1320px] overflow-hidden rounded-[2rem] bg-[#10251f] shadow-[0_30px_80px_rgba(16,37,31,.18)] lg:grid-cols-2">
        <div class="flex flex-col justify-center p-8 text-white sm:p-12 lg:p-16">
            <p class="text-[10px] font-bold uppercase tracking-[.22em] text-secondary">Temui kami</p>
            <h2 class="mt-5 text-3xl font-semibold tracking-[-.04em] sm:text-4xl">Datang dan pilih menu favoritmu.</h2>
            <p class="mt-6 max-w-lg text-sm leading-7 text-white/68">{{ $locationAddress }}</p>
            @if($primaryLocation?->phone)<a href="tel:{{ preg_replace('/[^0-9+]/', '', $primaryLocation->phone) }}" class="mt-3 w-fit text-sm font-semibold text-white/90 hover:text-secondary">{{ $primaryLocation->phone }}</a>@endif
            <a href="{{ route('lokasi') }}" class="mt-8 inline-flex w-fit items-center gap-3 rounded-full bg-secondary px-6 py-3 text-xs font-bold text-[#152019] transition hover:-translate-y-0.5 hover:brightness-105">Lihat lokasi & jam buka <span>↗</span></a>
        </div>
        <div class="relative min-h-[320px] overflow-hidden bg-[#28443d] lg:min-h-[480px]">
            <img src="{{ $storyImage ?: $heroImage }}" alt="Suasana {{ $businessName }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy" decoding="async">
            <div class="absolute inset-0 bg-gradient-to-r from-[#10251f]/55 to-transparent lg:from-[#10251f]/35"></div>
        </div>
    </div>
</section>
@endsection
