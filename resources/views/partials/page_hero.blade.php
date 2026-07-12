<section class="public-page-hero relative isolate overflow-hidden text-white">
    <img src="{{ asset('images/panama-roastery-hero.png') }}" alt="" class="absolute inset-0 h-full w-full object-cover object-center" aria-hidden="true" decoding="async">
    <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(4,9,8,.96),rgba(4,9,8,.76)_48%,rgba(4,9,8,.58))]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_75%_15%,rgba(245,158,11,.18),transparent_35%)]"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8">
        <div class="max-w-3xl">
            <div class="mb-4 flex items-center gap-3">
                <span class="h-px w-9 bg-secondary" aria-hidden="true"></span>
                <p class="text-[11px] font-bold uppercase tracking-[.22em] text-secondary">{{ $eyebrow ?? 'Panama Corner' }}</p>
            </div>
            <h1 class="text-4xl font-extrabold leading-tight tracking-[-.03em] sm:text-5xl">{{ $title }}</h1>
            <p class="mt-5 max-w-2xl text-sm leading-7 text-white/75 sm:text-base">{{ $subtitle }}</p>
        </div>
    </div>
</section>
