@php
    $heroImageUrl = $imageUrl ?? null;
    $heroImageAlt = $imageAlt ?? '';
    $heroImageCredit = $imageCredit ?? null;
    $heroImageCreditUrl = $imageCreditUrl ?? null;
@endphp

<section class="public-page-hero relative isolate overflow-hidden text-white">
    @if($heroImageUrl)
        <img
            src="{{ $heroImageUrl }}"
            alt="{{ $heroImageAlt }}"
            class="absolute inset-0 h-full w-full object-cover object-center"
            width="1920"
            height="900"
            decoding="async"
        >
    @endif
    <div class="absolute inset-0 {{ $heroImageUrl ? 'bg-[linear-gradient(90deg,rgba(4,14,11,.95),rgba(4,14,11,.72)_52%,rgba(4,14,11,.32))]' : 'bg-[linear-gradient(120deg,#071511,#123a32)]' }}"></div>
    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(0,0,0,.16),transparent_35%,rgba(0,0,0,.34))]"></div>
    <div class="absolute -right-28 -top-40 h-[32rem] w-[32rem] rounded-full border border-white/7" aria-hidden="true"></div>
    <div class="absolute -right-8 -top-16 h-72 w-72 rounded-full border border-secondary/12" aria-hidden="true"></div>
    <div class="relative mx-auto flex min-h-[390px] max-w-[1440px] items-end px-5 pb-16 pt-32 sm:px-8 sm:pb-20 lg:min-h-[440px] lg:px-12 lg:pb-20 xl:px-16">
        <div class="home-reveal max-w-[820px]">
            <div class="mb-5 flex items-center gap-4">
                <span class="h-px w-10 bg-secondary" aria-hidden="true"></span>
                <p class="text-[10px] font-semibold uppercase tracking-[.24em] text-secondary">{{ $eyebrow ?? 'Informasi' }}</p>
            </div>
            <h1 class="max-w-4xl text-4xl font-semibold leading-[1.08] tracking-[-.045em] sm:text-5xl lg:text-[3.6rem]">{{ $title }}</h1>
            @if(filled($subtitle ?? null))
                <p class="mt-6 max-w-2xl text-sm leading-7 text-white/70 sm:text-[15px]">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @if($heroImageUrl && filled($heroImageCredit))
        <div class="absolute bottom-3 right-4 z-10 text-[9px] text-white/55 sm:right-6">
            @if($heroImageCreditUrl)
                <a href="{{ $heroImageCreditUrl }}" target="_blank" rel="noopener noreferrer" class="transition hover:text-white">{{ $heroImageCredit }} ↗</a>
            @else
                <span>{{ $heroImageCredit }}</span>
            @endif
        </div>
    @endif
</section>
