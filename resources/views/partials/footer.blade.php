@php($footer = $footer ?? app(\App\Support\FooterPresenter::class)->data())

<footer class="border-t border-white/8 bg-[#09121f] text-[#93a09a]" style="background-color:#071712">
    @if($footer['cta']['enabled'] && $footer['cta']['buttonUrl'])
        <div class="border-b border-white/10 bg-white/[.035]">
            <div class="mx-auto flex max-w-[1320px] flex-col gap-6 px-5 py-10 sm:px-8 md:flex-row md:items-center md:justify-between lg:px-12 xl:px-16">
                <div class="max-w-2xl">
                    <h2 class="text-xl font-semibold tracking-[-.025em] text-white sm:text-2xl">{{ $footer['cta']['title'] }}</h2>
                    @if($footer['cta']['description'])<p class="mt-2 text-sm leading-6 text-slate-400">{{ $footer['cta']['description'] }}</p>@endif
                </div>
                <a href="{{ $footer['cta']['buttonUrl'] }}" class="inline-flex min-h-11 shrink-0 items-center justify-center rounded-lg bg-secondary px-5 text-xs font-extrabold text-slate-950 transition hover:-translate-y-0.5 hover:brightness-105">{{ $footer['cta']['buttonLabel'] }}</a>
            </div>
        </div>
    @endif

    <div class="mx-auto max-w-[1320px] px-5 pb-8 pt-16 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-12">
            <div class="space-y-5 lg:col-span-4">
                <div class="flex items-center gap-3">
                    @if($footer['logoUrl'])
                        <span class="flex h-11 max-w-[92px] shrink-0 items-center"><img src="{{ $footer['logoUrl'] }}" alt="Logo {{ $footer['businessName'] }}" class="block max-h-full w-auto max-w-full object-contain" loading="lazy"></span>
                    @else
                        @include('partials.brand-mark', ['class' => 'h-11 w-11 rounded-xl border border-white/15 bg-white/8 text-white'])
                    @endif
                    <h2 class="text-lg font-extrabold tracking-tight text-white">{{ $footer['businessName'] }}</h2>
                </div>
                @if($footer['description'])<p class="max-w-sm text-sm leading-6 text-slate-400">{{ $footer['description'] }}</p>@endif
                @if($footer['showSocials'] && $footer['socials'])
                    <div class="flex flex-wrap gap-x-5 gap-y-2 text-xs font-semibold">
                        @foreach($footer['socials'] as $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" class="transition hover:text-white">{{ $social['label'] }} <span aria-hidden="true">↗</span></a>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($footer['showNavigation'] && $footer['navigation'])
                <div class="lg:col-span-2">
                    <h3 class="text-[11px] font-extrabold uppercase tracking-[.16em] text-white">{{ $footer['navigationTitle'] }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach($footer['navigation'] as $item)<li><a href="{{ $item['url'] }}" class="transition hover:text-white">{{ $item['label'] }}</a></li>@endforeach
                    </ul>
                </div>
            @endif

            @if($footer['showLegal'] && $footer['legalDocuments'])
                <div class="lg:col-span-3">
                    <h3 class="text-[11px] font-extrabold uppercase tracking-[.16em] text-white">{{ $footer['legalTitle'] }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach($footer['legalDocuments'] as $document)
                            <li><span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ $document['name'] }}</span>@if($document['number'])<span class="mt-1 block break-words font-mono text-xs text-slate-400">{{ $document['number'] }}</span>@endif</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($footer['showContact'] && ($footer['email'] || $footer['phone'] || $footer['address']))
                <div class="lg:col-span-3">
                    <h3 class="text-[11px] font-extrabold uppercase tracking-[.16em] text-white">{{ $footer['contactTitle'] }}</h3>
                    <dl class="mt-5 space-y-4 text-sm">
                        @if($footer['email'])<div><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Email</dt><dd class="mt-1 break-all"><a href="mailto:{{ $footer['email'] }}" class="transition hover:text-white">{{ $footer['email'] }}</a></dd></div>@endif
                        @if($footer['phone'])<div><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Telepon</dt><dd class="mt-1"><a href="tel:{{ $footer['phoneHref'] }}" class="transition hover:text-white">{{ $footer['phone'] }}</a></dd></div>@endif
                        @if($footer['address'])<div><dt class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Alamat</dt><dd class="mt-1 max-w-xs leading-6">{{ $footer['address'] }}</dd></div>@endif
                    </dl>
                </div>
            @endif
        </div>

        <div class="mt-12 border-t border-white/10 pt-7 text-xs text-slate-500">
            <p>{{ $footer['copyright'] }}</p>
        </div>
    </div>
</footer>
