@extends('layouts.app')

@section('title', \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner') . ' | Beranda')

@section('meta_description', 'Nikmati kopi Nusantara pilihan yang dikurasi dan dipanggang segar oleh Panama Corner.')

@section('content')
@php
    $heroUploaded = \App\Models\SiteSetting::get('hero_image_upload');
    $heroImage = $heroUploaded
        ? \Illuminate\Support\Facades\Storage::url($heroUploaded)
        : \App\Models\SiteSetting::get('hero_image_url', asset('images/panama-roastery-hero.png'));
    $yearsInBusiness = max(1, now()->year - (int) ($stats['founded_year'] ?? now()->year));
@endphp

<section class="home-hero relative isolate min-h-[620px] overflow-hidden bg-[#070a09] text-white lg:min-h-[680px]">
    <img
        src="{{ $heroImage }}"
        alt="Mesin roasting dan biji kopi Panama Corner"
        class="absolute inset-0 h-full w-full object-cover object-[66%_center]"
        width="1792"
        height="1024"
        fetchpriority="high"
        decoding="async"
    >
    <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(4,8,7,.98)_0%,rgba(4,8,7,.87)_35%,rgba(4,8,7,.33)_64%,rgba(4,8,7,.08)_100%)]"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-transparent to-black/10"></div>

    <div class="relative mx-auto flex min-h-[620px] max-w-7xl items-center px-4 pb-32 pt-20 sm:px-6 lg:min-h-[680px] lg:px-8 lg:pb-40">
        <div class="max-w-2xl">
            <p class="mb-5 inline-flex rounded-full border border-secondary/45 bg-black/25 px-3 py-1.5 text-[11px] font-semibold tracking-wide text-secondary backdrop-blur-sm">
                Artisan Coffee Roaster Premium
            </p>
            <h1 class="max-w-2xl text-4xl font-extrabold leading-[1.05] tracking-[-0.035em] sm:text-5xl lg:text-[4rem]">
                Nikmati Cita Rasa Kopi Nusantara yang <span class="text-secondary">Sesungguhnya.</span>
            </h1>
            <p class="mt-6 max-w-xl text-sm leading-7 text-white/80 sm:text-base">
                Kami mengurasi biji kopi pilihan dari petani lokal di seluruh penjuru Indonesia dan memanggangnya segar secara presisi untuk menghadirkan kualitas terbaik di setiap cangkir kopi Anda.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('produk') }}" class="inline-flex min-h-12 items-center justify-center rounded-lg bg-secondary px-6 text-sm font-bold text-[#17130b] shadow-[0_10px_30px_rgba(245,158,11,.28)] transition hover:-translate-y-0.5 hover:brightness-105 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-secondary">
                    Jelajahi Katalog
                </a>
                <a href="{{ route('kontak') }}" class="inline-flex min-h-12 items-center justify-center rounded-lg border border-secondary/75 bg-black/20 px-6 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-secondary hover:text-[#17130b] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-secondary">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

<section aria-label="Statistik Panama Corner" class="relative z-10 -mt-24 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-4 md:grid-cols-3">
        @foreach([
            ['value' => $yearsInBusiness . ' Tahun', 'label' => 'Pengalaman Roasting', 'icon' => 'coffee'],
            ['value' => ($stats['products_count'] ?? 0) . '+', 'label' => 'Varian Biji Kopi & Kopi Botol', 'icon' => 'bean'],
            ['value' => ($stats['locations_count'] ?? 0) . ' Cabang', 'label' => 'Experience Bar & Roastery', 'icon' => 'users'],
        ] as $stat)
            <article class="home-stat-card min-h-[164px] rounded-xl px-6 py-6 text-center sm:py-7">
                <div class="home-stat-icon mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-lg bg-secondary text-[#211b10]">
                    @if($stat['icon'] === 'coffee')
                        <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 9h11v5a5 5 0 0 1-5 5h-1a5 5 0 0 1-5-5V9Z"/><path d="M16 11h1.5a2.5 2.5 0 0 1 0 5H16M8 5c0 1 1 1 1 2M12 4c0 1 1 1 1 2"/></svg>
                    @elseif($stat['icon'] === 'bean')
                        <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15.5 4.5c4 1.5 5 6.3 2.3 10.2S10.5 20.8 7 18.3 4.2 11 7.1 7c2.7-2.7 5.8-3.5 8.4-2.5Z"/><path d="M16 5c-1.1 4-4.2 8.5-8.6 12.8"/></svg>
                    @else
                        <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="10" r="2.5"/><path d="M3.5 19v-1.5A4.5 4.5 0 0 1 8 13h2a4.5 4.5 0 0 1 4.5 4.5V19M14 14.2a4 4 0 0 1 6.5 3.1V19"/></svg>
                    @endif
                </div>
                <p class="text-2xl font-extrabold tracking-tight text-gray-950 sm:text-3xl">{{ $stat['value'] }}</p>
                <p class="mt-1 text-xs font-medium text-gray-600">{{ $stat['label'] }}</p>
            </article>
        @endforeach
    </div>
</section>

<section class="bg-white px-4 pb-20 pt-20 sm:px-6 lg:px-8 lg:pb-24 lg:pt-24">
    <div class="mx-auto grid max-w-7xl gap-12 lg:grid-cols-2 lg:gap-20">
        <div>
            <p class="mb-3 text-xs font-bold uppercase tracking-[.18em] text-primary">Tentang Panama Corner</p>
            <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-gray-950 sm:text-4xl">Dedikasi Kami untuk Kopi Indonesia yang Lebih Baik.</h2>
            <p class="mt-5 text-sm leading-7 text-gray-600">{{ $profile?->description ?? 'Panama Corner menghadirkan kopi spesialti premium dari perkebunan terbaik Indonesia, dipanggang segar dengan dedikasi pada kualitas dan konsistensi.' }}</p>
        </div>
        <div class="flex flex-col justify-center gap-6">
            <div class="flex gap-4">
                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-secondary text-xs font-bold text-[#20190c]">✓</span>
                <div><h3 class="text-sm font-bold text-gray-950">Biji Kopi 100% Pilihan Single Origin</h3><p class="mt-1 text-xs leading-5 text-gray-500">Dikurasi langsung dari perkebunan dataran tinggi dengan karakter rasa terbaik.</p></div>
            </div>
            <div class="flex gap-4">
                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-secondary text-xs font-bold text-[#20190c]">✓</span>
                <div><h3 class="text-sm font-bold text-gray-950">Proses Roasting Artisan</h3><p class="mt-1 text-xs leading-5 text-gray-500">Setiap batch dipanggang segar dengan profil yang dikunci secara konsisten.</p></div>
            </div>
            <a href="{{ route('profil') }}" class="inline-flex w-fit items-center gap-2 text-sm font-bold text-secondary transition hover:gap-3 hover:text-amber-600">Baca Selengkapnya <span aria-hidden="true">→</span></a>
        </div>
    </div>
</section>

<section class="border-y border-gray-200 bg-[#f7f8f7] px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
    <div class="mx-auto max-w-7xl">
        <div class="mb-9 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="mb-2 text-xs font-bold uppercase tracking-[.18em] text-primary">Katalog Pilihan</p><h2 class="text-3xl font-extrabold tracking-tight text-gray-950 sm:text-4xl">Produk Pilihan Terpopuler</h2><p class="mt-2 text-sm text-gray-600">Varian biji kopi single origin andalan dan produk siap minum terlaris kami.</p></div>
            <a href="{{ route('produk') }}" class="inline-flex min-h-11 w-fit items-center justify-center rounded-lg bg-secondary px-5 text-xs font-bold text-[#211b10] shadow-md transition hover:-translate-y-0.5 hover:brightness-105">Lihat Semua Produk</a>
        </div>

        @if($featuredProducts->isNotEmpty())
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredProducts as $product)
                    @php $thumb = $product->getFirstMediaUrl('gallery', 'large') ?: $product->getFirstMediaUrl('gallery', 'thumb'); @endphp
                    <article class="group overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <a href="{{ route('produk.detail', $product->slug) }}" class="relative block aspect-[4/3] overflow-hidden bg-[#ececea]">
                            @if($product->is_bestseller)<span class="absolute left-3 top-3 z-10 rounded-md bg-secondary px-2 py-1 text-[9px] font-extrabold uppercase tracking-wide text-[#211b10]">Terlaris</span>@endif
                            @if($thumb)
                                <img src="{{ $thumb }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                            @else
                                <div class="flex h-full items-center justify-center text-6xl text-primary/15" aria-hidden="true">☕</div>
                            @endif
                        </a>
                        <div class="p-5">
                            <p class="text-[10px] font-bold uppercase tracking-[.13em] text-primary">{{ $product->category?->name ?? 'Produk Panama Corner' }}</p>
                            <h3 class="mt-2 min-h-12 text-base font-extrabold leading-6 text-gray-950"><a class="transition hover:text-primary" href="{{ route('produk.detail', $product->slug) }}">{{ $product->name }}</a></h3>
                            <p class="mt-2 line-clamp-2 text-xs leading-5 text-gray-500">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 115) }}</p>
                            <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">
                                <strong class="text-sm text-gray-950">{{ $product->price ? 'Rp ' . number_format($product->price, 0, ',', '.') : 'Hubungi Kami' }}</strong>
                                <a href="{{ route('produk.detail', $product->slug) }}" class="rounded-md bg-secondary px-3 py-2 text-[10px] font-bold text-[#211b10] transition hover:brightness-95">Detail Kopi</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-sm text-gray-500">Produk pilihan sedang disiapkan. Silakan kembali lagi segera.</div>
        @endif

    </div>
</section>

<section class="relative overflow-hidden bg-white px-4 py-20 sm:px-6 lg:px-8 lg:py-28" aria-labelledby="latest-insights-title">
    <div class="pointer-events-none absolute -right-32 -top-32 h-80 w-80 rounded-full bg-secondary/8 blur-3xl" aria-hidden="true"></div>
    <div class="mx-auto max-w-7xl">
        <div class="mb-10 grid gap-6 border-b border-gray-200 pb-8 lg:grid-cols-[1fr_auto] lg:items-end">
            <div class="max-w-2xl">
                <div class="mb-4 flex items-center gap-3">
                    <span class="h-px w-9 bg-secondary" aria-hidden="true"></span>
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-primary">Jurnal Panama Corner</p>
                </div>
                <h2 id="latest-insights-title" class="text-3xl font-extrabold tracking-tight text-gray-950 sm:text-4xl">Wawasan, Cerita, dan Panduan Kopi</h2>
                <p class="mt-3 text-sm leading-6 text-gray-600">Temukan pengetahuan praktis dari roastery kami—mulai dari perjalanan biji kopi hingga teknik seduh yang dapat Anda terapkan di rumah.</p>
            </div>
            <a href="{{ route('artikel') }}" class="inline-flex min-h-11 w-fit items-center gap-2 rounded-lg border border-primary px-5 text-xs font-bold text-primary transition hover:bg-primary hover:text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">
                Jelajahi Semua Artikel <span aria-hidden="true">→</span>
            </a>
        </div>

        @if($latestArticles->isNotEmpty())
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($latestArticles as $article)
                    @php $articleThumb = $article->getFirstMediaUrl('featured_image', 'thumb'); @endphp
                    <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-[0_8px_30px_rgba(15,23,42,.06)] transition duration-300 hover:-translate-y-1 hover:border-primary/25 hover:shadow-[0_18px_45px_rgba(15,23,42,.11)]">
                        <a href="{{ route('artikel.detail', $article->slug) }}" class="relative block aspect-[16/9] overflow-hidden bg-[#102521]" tabindex="-1" aria-hidden="true">
                            @if($articleThumb)
                                <img src="{{ $articleThumb }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                            @else
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_10%,rgba(245,158,11,.28),transparent_42%),linear-gradient(135deg,#173d36,#081511)]"></div>
                                <div class="absolute inset-0 flex items-center justify-center" aria-hidden="true">
                                    <svg class="h-14 w-14 text-secondary/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/><path d="M8 7h8M8 11h6"/></svg>
                                </div>
                            @endif
                            <span class="absolute bottom-3 left-3 rounded-full bg-white/95 px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-[.14em] text-primary shadow-sm backdrop-blur">{{ $article->category?->name ?? 'Artikel' }}</span>
                        </a>

                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex items-center gap-2 text-[10px] font-medium text-gray-400">
                                <time datetime="{{ $article->published_at?->toDateString() }}">{{ $article->published_at?->translatedFormat('d M Y') }}</time>
                                <span aria-hidden="true">•</span>
                                <span>{{ $article->reading_time ?? 1 }} menit baca</span>
                            </div>
                            <h3 class="mt-3 text-lg font-extrabold leading-7 text-gray-950">
                                <a href="{{ route('artikel.detail', $article->slug) }}" class="transition group-hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">{{ $article->title }}</a>
                            </h3>
                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-gray-500">{{ $article->excerpt }}</p>
                            <a href="{{ route('artikel.detail', $article->slug) }}" class="mt-6 inline-flex w-fit items-center gap-2 text-xs font-bold text-primary transition group-hover:gap-3" aria-label="Baca artikel: {{ $article->title }}">Baca Artikel <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-12 text-center">
                <p class="text-sm font-semibold text-gray-700">Artikel terbaru sedang disiapkan.</p>
                <p class="mt-1 text-xs text-gray-500">Kunjungi kembali jurnal kami untuk wawasan seputar kopi Nusantara.</p>
            </div>
        @endif
    </div>
</section>
@endsection
