@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))

@if($page->meta_description)
    @section('meta_description', $page->meta_description)
@endif

@section('content')
@php
    $profileImageUrl = isset($profile) ? $profile?->getFirstMediaUrl('about_image') : null;
    $pageHeroUrl = $page->getResolvedHeroUrl()
        ?: $profileImageUrl;
@endphp
<!-- Page Hero / Header -->
@include('partials.page_hero', [
    'eyebrow' => $page->eyebrow ?: ($page->template === 'about' ? 'Cerita & Nilai Kami' : ($page->template === 'locations' ? 'Temui Kami' : 'Informasi')),
    'title' => $page->title,
    'subtitle' => $page->subtitle ?: $page->meta_description,
    'imageUrl' => $pageHeroUrl,
    'imageAlt' => $page->hero_alt ?: ($pageHeroUrl ? 'Visual halaman ' . $page->title : ''),
    'imageCredit' => $page->hero_credit,
    'imageCreditUrl' => $page->getSafeHeroCreditUrl(),
])

<!-- Main Content Area -->
@if($page->template === 'about' && isset($profile))
    @php
        $businessName = $profile->business_name ?: 'Panama Corner';
        $foundedYear = $profile->founded_year;
        $vision = \Illuminate\Support\Str::of(strip_tags((string) $profile->vision))->squish();
        $mission = trim(strip_tags((string) $profile->mission));
        $legalDocuments = collect(is_array($profile->legal_docs) ? $profile->legal_docs : [])
            ->filter(fn ($document) => is_array($document) && filled(data_get($document, 'name')))
            ->values();
        $valuesTitle = $page->about_values_title ?: 'Arah & Prinsip Usaha';
        $primaryLabel = $page->about_primary_label ?: 'Tujuan Kami';
        $secondaryLabel = $page->about_secondary_label ?: 'Prinsip Kami';
        $storyImageUrl = $page->getFirstMediaUrl('content_image', 'large')
            ?: $page->getFirstMediaUrl('content_image')
            ?: $profileImageUrl;

        if (filled($mission)) {
            if (!str_contains($mission, "\n") && preg_match('/1\.\s+/', $mission)) {
                $misiList = preg_split('/(?=\d+\.\s+)/', $mission);
            } else {
                $misiList = explode("\n", $mission);
            }
        } else {
            $misiList = [
                "Menyediakan biji kopi arabika dan robusta pilihan dengan proses kurasi yang ketat.",
                "Menerapkan teknik pemanggangan modern untuk menghasilkan profil rasa kopi yang konsisten dan premium.",
                "Membangun kemitraan yang adil dan berkelanjutan bersama petani kopi lokal.",
                "Mengedukasi masyarakat luas tentang keragaman rasa kopi asli Indonesia."
            ];
        }
    @endphp
    <!-- Unified About Page Layout (Mimics Kontak workspace style) -->
    <section class="public-page-content flex-grow py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="home-reveal overflow-hidden rounded-[1.75rem] border border-[#d8d4ca] bg-white/90 p-7 shadow-[0_28px_80px_rgba(16,37,31,.08)] sm:p-10 md:p-12 space-y-16">

                <!-- Section 1: Our Heritage -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center pb-16 border-b border-gray-100">
                    <!-- Left: CMS-managed story image -->
                    <div class="lg:col-span-5">
                        <div class="group relative aspect-[4/3] overflow-hidden rounded-[26px] border border-white/80 bg-[linear-gradient(145deg,#e8eeeb,#d9e3de)] shadow-[0_24px_55px_rgba(15,23,42,.16)]">
                            @if($storyImageUrl)
                                <img
                                    src="{{ $storyImageUrl }}"
                                    alt="{{ $page->content_image_alt ?: 'Suasana ' . $businessName }}"
                                    class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]"
                                    loading="lazy"
                                    decoding="async"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-transparent"></div>
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-primary/20" aria-hidden="true">
                                    <svg class="h-20 w-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M4 19V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14"/><path d="m4 16 4-4 3 3 4-5 5 6M8 8h.01"/></svg>
                                </div>
                            @endif
                            <div class="absolute bottom-5 left-5 rounded-full border border-white/25 bg-black/30 px-3 py-1.5 text-[9px] font-bold uppercase tracking-[.16em] text-white backdrop-blur-md">{{ $businessName }}</div>
                        </div>
                    </div>
                    <!-- Right: Content -->
                    <div class="lg:col-span-7 space-y-4">
                        <div class="space-y-1">
                            <span class="text-xs text-primary font-bold uppercase tracking-[.25em]">Tentang Kami</span>
                            <h2 class="text-2xl font-semibold tracking-[-.03em] text-[#10251f] font-title sm:text-3xl">Kafe di Condongcatur</h2>
                        </div>
                        <div class="prose prose-slate prose-teal text-sm leading-7 text-gray-600 max-w-none">
                            {!! $page->content !!}
                        </div>
                    </div>
                </div>

                <!-- Section 2: Vision & Mission -->
                <div class="space-y-8 pb-16 border-b border-gray-100">
                    <h2 class="text-2xl font-semibold tracking-[-.03em] text-[#10251f] font-title">{{ $valuesTitle }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Visi Card -->
                        <div class="bg-[#f4f5f3]/40 p-6 md:p-8 rounded-[20px] border-b-4 border-b-primary border border-gray-150 shadow-[0_4px_12px_rgba(0,0,0,.01)] space-y-4 flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-extrabold text-lg text-gray-950 font-title">{{ $primaryLabel }}</h3>
                                </div>
                                <p class="text-gray-600 leading-relaxed text-sm">
                                    {{ $vision->isNotEmpty() ? $vision : 'Menyediakan menu yang enak, pelayanan yang ramah, dan tempat yang nyaman.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Misi Card -->
                        <div class="bg-[#f4f5f3]/40 p-6 md:p-8 rounded-[20px] border-b-4 border-b-primary border border-gray-150 shadow-[0_4px_12px_rgba(0,0,0,.01)] space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                </div>
                                <h3 class="font-extrabold text-lg text-gray-950 font-title">{{ $secondaryLabel }}</h3>
                            </div>
                            <ol class="space-y-2.5 text-gray-600 text-sm leading-relaxed list-none pl-0">
                                @foreach($misiList as $index => $item)
                                    @php
                                        $cleanItem = preg_replace('/^\d+[\.\s\-]+/i', '', trim($item));
                                    @endphp
                                    @if(filled($cleanItem))
                                        <li class="flex items-start gap-2">
                                            <span class="font-extrabold text-primary shrink-0">{{ $index + 1 }}.</span>
                                            <span>{{ $cleanItem }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>

                @if($legalDocuments->isNotEmpty())
                    <div class="space-y-8 pb-16 border-b border-gray-100">
                        <h2 class="text-2xl font-extrabold tracking-tight text-gray-950 font-title">Dokumen Usaha</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($legalDocuments as $doc)
                                <div class="bg-[#f4f5f3]/40 p-6 rounded-[20px] border-t-4 border-t-primary border border-gray-150 shadow-[0_4px_12px_rgba(0,0,0,.01)] flex flex-col items-center text-center space-y-4">
                                    <div class="h-10 w-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="font-extrabold text-gray-900 font-title text-sm sm:text-base leading-tight">{{ data_get($doc, 'name') }}</h3>
                                        @if(filled(data_get($doc, 'number')))
                                            <p class="text-base font-black text-gray-950 tracking-tight font-mono py-0.5">{{ data_get($doc, 'number') }}</p>
                                        @endif
                                    </div>
                                    <div class="w-full pt-3 border-t border-gray-150 flex flex-col gap-0.5 text-xs text-gray-400">
                                        <span>Penerbit: <strong class="text-gray-600 font-semibold">{{ data_get($doc, 'issuer', 'Instansi penerbit') }}</strong></span>
                                        <span>Tahun: <strong class="text-gray-600 font-semibold">{{ data_get($doc, 'year', '—') }}</strong></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Section 4: Widgets (Roasting & Social Links) -->
                @if(isset($page->widgets) && is_array($page->widgets) && count($page->widgets) > 0)
                    <div>
                        @include('partials.page_widgets', ['widgets' => $page->widgets])
                    </div>
                @endif

            </div>
        </div>
    </section>

@elseif($page->template === 'locations')
    <!-- Outlets Experience Layout (Lokasi) -->
    <section class="public-page-content flex-grow py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-16">

            @if(filled(strip_tags($page->content)))
                <article class="bg-white rounded-[28px] border border-white/80 p-8 md:p-12 shadow-[0_28px_80px_rgba(15,23,42,.04)] prose max-w-none prose-slate prose-teal mb-10">
                    {!! $page->content !!}
                </article>
            @endif

            <!-- Maps Grid -->
            <div class="grid overflow-hidden rounded-[28px] border border-white/80 bg-white shadow-[0_28px_80px_rgba(15,23,42,.11)] lg:grid-cols-[1.65fr_.75fr]">
                <div class="relative min-h-[390px] bg-[#e7ebe8] lg:min-h-[520px]">
                    @if(isset($mapsEmbedUrl) && $mapsEmbedUrl)
                        <iframe
                            src="{{ $mapsEmbedUrl }}"
                            title="Peta lokasi Panama Corner"
                            class="absolute inset-0 h-full w-full border-0 grayscale-[15%]"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                        ></iframe>
                    @else
                        <div class="absolute inset-0 flex items-center justify-center p-8 text-center">
                            <div>
                                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                                    <svg aria-hidden="true" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                </span>
                                <h2 class="mt-4 text-lg font-bold text-gray-900">Peta sedang diperbarui</h2>
                                <p class="mt-2 text-sm text-gray-500">Alamat lengkap setiap cabang tetap tersedia di bawah.</p>
                            </div>
                        </div>
                    @endif
                    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/15 to-transparent"></div>
                </div>

                <aside class="relative isolate overflow-hidden bg-[#0b2420] p-8 text-white sm:p-10 lg:p-12">
                    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_90%_10%,rgba(245,158,11,.2),transparent_35%),radial-gradient(circle_at_0%_100%,rgba(15,118,110,.5),transparent_48%)]"></div>
                    <p class="text-[10px] font-bold uppercase tracking-[.22em] text-secondary">Jaringan Kami</p>
                    <p class="mt-4 text-5xl font-extrabold tracking-tight">{{ isset($locations) ? $locations->count() : 0 }}</p>
                    <h2 class="mt-2 text-xl font-bold">Lokasi aktif</h2>
                    <p class="mt-4 text-sm leading-7 text-white/60">Temukan alamat, nomor kontak, dan jam operasional Panama Corner sebelum berkunjung.</p>

                    <div class="mt-10 space-y-5 border-t border-white/10 pt-8">
                        <div class="flex items-center gap-3 text-sm text-white/75"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/8 text-secondary">✓</span><span>Makanan, camilan, dan minuman</span></div>
                        <div class="flex items-center gap-3 text-sm text-white/75"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/8 text-secondary">✓</span><span>Informasi jam buka harian</span></div>
                        <div class="flex items-center gap-3 text-sm text-white/75"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/8 text-secondary">✓</span><span>Akses petunjuk arah langsung</span></div>
                    </div>

                    <a href="{{ route('kontak') }}" class="mt-10 inline-flex min-h-11 items-center justify-center rounded-xl border border-white/20 px-5 text-xs font-bold text-white transition hover:border-secondary hover:bg-secondary hover:text-[#17130b]">Hubungi Tim Kami</a>
                </aside>
            </div>

            <!-- Branch Outlets List -->
            @if(isset($locations) && $locations->isNotEmpty())
                <div>
                    <div class="mb-9 flex flex-col gap-4 border-b border-gray-200 pb-7 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[.2em] text-primary">Direktori Cabang</p>
                            <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-gray-950">Pilih lokasi terdekat</h2>
                        </div>
                        <p class="max-w-md text-sm leading-6 text-gray-500">Periksa alamat dan jam operasional sebelum berkunjung agar kami dapat melayani Anda dengan optimal.</p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        @foreach($locations as $loc)
                            <article class="premium-surface group flex h-full flex-col rounded-2xl border border-gray-100 bg-white p-7 transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_50px_rgba(15,23,42,.11)] sm:p-8">
                                <div class="flex items-start justify-between gap-5">
                                    <div class="flex min-w-0 gap-4">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary text-sm font-extrabold text-white shadow-[0_8px_18px_rgba(15,118,110,.2)]">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        <div>
                                            <p class="text-[9px] font-bold uppercase tracking-[.16em] text-secondary">Kunjungi Panama Corner</p>
                                            <h3 class="mt-1 text-xl font-extrabold leading-7 text-gray-950">{{ $loc->name }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-7 grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-[.14em] text-gray-400">Alamat</p>
                                        <address class="mt-2 text-sm not-italic leading-6 text-gray-600">{{ $loc->address }}</address>
                                        @if($loc->phone)
                                            <a href="tel:{{ preg_replace('/[^+0-9]/', '', $loc->phone) }}" class="mt-3 inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-primary/75">
                                                <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z"/></svg>
                                                {{ $loc->phone }}
                                            </a>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-[.14em] text-gray-400">Jam Operasional</p>
                                        @if($loc->operating_hours)
                                            <dl class="mt-2 divide-y divide-gray-100 text-xs">
                                                @foreach($loc->operating_hours as $days => $hours)
                                                    <div class="flex justify-between gap-4 py-2 first:pt-0"><dt class="text-gray-500">{{ $days }}</dt><dd class="shrink-0 font-bold text-gray-800">{{ $hours }}</dd></div>
                                                @endforeach
                                            </dl>
                                        @else
                                            <p class="mt-2 text-sm text-gray-500">Hubungi cabang untuk informasi jam buka.</p>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(isset($page->widgets) && is_array($page->widgets) && count($page->widgets) > 0)
                <div class="mt-16">
                    @include('partials.page_widgets', ['widgets' => $page->widgets])
                </div>
            @endif
        </div>
    </section>

@else
    <!-- Standard Custom Page Template -->
    <section class="public-page-content flex-grow py-16 lg:py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <article class="editorial-surface home-reveal rounded-[1.75rem] p-7 prose max-w-none prose-slate prose-teal sm:p-10 md:p-12">
                {!! $page->content !!}
            </article>
        </div>

        @if(isset($page->widgets) && is_array($page->widgets) && count($page->widgets) > 0)
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-16">
                @include('partials.page_widgets', ['widgets' => $page->widgets])
            </div>
        @endif
    </section>
@endif
@endsection
