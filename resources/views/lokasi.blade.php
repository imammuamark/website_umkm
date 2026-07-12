@extends('layouts.app')

@section('title', 'Lokasi Toko & Jam Buka | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))

@section('content')
@include('partials.page_hero', ['eyebrow' => 'Temui Kami', 'title' => 'Lokasi & Jam Operasional', 'subtitle' => 'Kunjungi roastery dan experience bar kami untuk menikmati kopi yang diseduh langsung oleh tim Panama Corner.'])

<section class="public-page-content flex-grow bg-[#f4f5f3] py-20 lg:py-24">
    <div class="mx-auto max-w-7xl space-y-16 px-4 sm:px-6 lg:px-8">
        <div class="grid overflow-hidden rounded-[28px] border border-white/80 bg-white shadow-[0_28px_80px_rgba(15,23,42,.11)] lg:grid-cols-[1.65fr_.75fr]">
            <div class="relative min-h-[390px] bg-[#e7ebe8] lg:min-h-[520px]">
                @if($mapsEmbedUrl)
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
                <p class="mt-4 text-5xl font-extrabold tracking-tight">{{ $locations->count() }}</p>
                <h2 class="mt-2 text-xl font-bold">Lokasi aktif</h2>
                <p class="mt-4 text-sm leading-7 text-white/60">Setiap lokasi dirancang untuk menghadirkan pengalaman kopi yang konsisten—dari proses roasting hingga penyajian.</p>

                <div class="mt-10 space-y-5 border-t border-white/10 pt-8">
                    <div class="flex items-center gap-3 text-sm text-white/75"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/8 text-secondary">✓</span><span>Roastery & coffee experience</span></div>
                    <div class="flex items-center gap-3 text-sm text-white/75"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/8 text-secondary">✓</span><span>Manual brew tersedia</span></div>
                    <div class="flex items-center gap-3 text-sm text-white/75"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/8 text-secondary">✓</span><span>Akses petunjuk arah langsung</span></div>
                </div>

                <a href="{{ route('kontak') }}" class="mt-10 inline-flex min-h-11 items-center justify-center rounded-xl border border-white/20 px-5 text-xs font-bold text-white transition hover:border-secondary hover:bg-secondary hover:text-[#17130b]">Hubungi Tim Kami</a>
            </aside>
        </div>

        <div>
            <div class="mb-9 flex flex-col gap-4 border-b border-gray-200 pb-7 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[.2em] text-primary">Direktori Cabang</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-gray-950">Pilih lokasi terdekat</h2>
                </div>
                <p class="max-w-md text-sm leading-6 text-gray-500">Periksa alamat dan jam operasional sebelum berkunjung agar kami dapat melayani Anda dengan optimal.</p>
            </div>

            @if($locations->isNotEmpty())
                <div class="grid gap-6 lg:grid-cols-2">
                    @foreach($locations as $loc)
                        <article class="premium-surface group flex h-full flex-col rounded-2xl border border-gray-100 bg-white p-7 transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_50px_rgba(15,23,42,.11)] sm:p-8">
                            <div class="flex items-start justify-between gap-5">
                                <div class="flex min-w-0 gap-4">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary text-sm font-extrabold text-white shadow-[0_8px_18px_rgba(15,118,110,.2)]">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div>
                                        <p class="text-[9px] font-bold uppercase tracking-[.16em] text-secondary">Cabang Panama Corner</p>
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

                            <div class="mt-auto flex flex-col gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-[11px] text-gray-400">Pastikan tujuan sebelum membuka navigasi.</p>
                                @if($loc->latitude && $loc->longitude)
                                    <a href="https://www.google.com/maps/dir/?api=1&amp;destination={{ rawurlencode($loc->latitude . ',' . $loc->longitude) }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-lg bg-secondary px-4 text-xs font-bold text-[#211b10] transition hover:-translate-y-0.5 hover:brightness-105">
                                        Petunjuk Arah <span aria-hidden="true">↗</span>
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center"><h3 class="font-bold text-gray-800">Informasi lokasi sedang diperbarui</h3><p class="mt-2 text-sm text-gray-500">Silakan hubungi tim kami untuk mendapatkan alamat terbaru.</p></div>
            @endif
        </div>
    </div>
</section>
@endsection
