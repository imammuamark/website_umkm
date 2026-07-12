@extends('layouts.app')

@section('title', 'Tentang Kami | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))
@section('meta_description', 'Mengenal perjalanan, visi, misi, dan legalitas Panama Corner sebagai artisan coffee roastery Indonesia.')

@section('content')
@include('partials.page_hero', ['eyebrow' => 'Cerita & Nilai Kami', 'title' => 'Tentang Panama Corner', 'subtitle' => 'Mengenal perjalanan kami dalam mengkurasi, memanggang, dan meracik cita rasa kopi terbaik Nusantara.'])

@php
    $businessName = $profile?->business_name ?: 'Panama Corner';
    $foundedYear = $profile?->founded_year;
    $description = \Illuminate\Support\Str::of(strip_tags((string) $profile?->description))->squish();
    $vision = \Illuminate\Support\Str::of(strip_tags((string) $profile?->vision))->squish();
    $mission = trim(strip_tags((string) $profile?->mission));
    $logoUrl = $profile?->getFirstMediaUrl('logo');
    $legalDocuments = collect(is_array($profile?->legal_docs) ? $profile->legal_docs : [])
        ->filter(fn ($document) => is_array($document) && filled(data_get($document, 'name')))
        ->values();
@endphp

<section class="public-page-content bg-white py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-[1fr_1.05fr] lg:gap-20">
            <div class="relative">
                <div class="relative aspect-[4/5] overflow-hidden rounded-[30px] bg-[#0b1714] shadow-[0_30px_70px_rgba(15,23,42,.2)] sm:aspect-[5/4] lg:aspect-[4/5]">
                    <img src="{{ asset('images/panama-roastery-hero.png') }}" alt="Proses roasting kopi di {{ $businessName }}" class="h-full w-full object-cover object-[62%_center]" loading="lazy" decoding="async">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/5 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-7 text-white sm:p-9">
                        <p class="text-[10px] font-bold uppercase tracking-[.2em] text-secondary">Artisan Coffee Roastery</p>
                        <p class="mt-2 max-w-sm text-xl font-extrabold leading-7">Dedikasi pada kualitas, konsistensi, dan karakter asli kopi Indonesia.</p>
                    </div>
                </div>

                @if($foundedYear)
                    <div class="absolute -bottom-6 -right-2 rounded-2xl border border-white/75 bg-white/82 p-5 shadow-[0_20px_45px_rgba(15,23,42,.16)] backdrop-blur-xl sm:-right-7 sm:p-6">
                        <p class="text-[9px] font-bold uppercase tracking-[.17em] text-primary">Berdiri Sejak</p>
                        <p class="mt-1 text-3xl font-extrabold tracking-tight text-gray-950">{{ $foundedYear }}</p>
                    </div>
                @endif
            </div>

            <div class="pt-7 lg:pt-0">
                <div class="flex items-center gap-3"><span class="h-px w-9 bg-secondary"></span><p class="text-[10px] font-bold uppercase tracking-[.2em] text-primary">Cerita Kami</p></div>
                <h2 class="mt-5 text-3xl font-extrabold leading-tight tracking-[-.025em] text-gray-950 sm:text-4xl">Membangun pengalaman kopi yang jujur dari hulu ke cangkir.</h2>
                <div class="mt-6 space-y-5 text-sm leading-7 text-gray-600 sm:text-base">
                    <p>{{ $description->isNotEmpty() ? $description : 'Panama Corner hadir dari kecintaan pada keragaman kopi Indonesia dan komitmen untuk menyajikannya dengan standar yang konsisten.' }}</p>
                    <p>Setiap keputusan—mulai dari pemilihan biji, profil roasting, hingga penyajian—kami arahkan untuk menjaga karakter asli kopi sekaligus menghadirkan pengalaman yang relevan bagi penikmat modern.</p>
                </div>

                <div class="mt-9 grid grid-cols-3 gap-3 border-t border-gray-100 pt-7">
                    <div><p class="text-2xl font-extrabold text-gray-950">{{ $profileStats['years'] ? $profileStats['years'] . '+' : '—' }}</p><p class="mt-1 text-[10px] font-semibold leading-4 text-gray-400">Tahun perjalanan</p></div>
                    <div><p class="text-2xl font-extrabold text-gray-950">{{ $profileStats['products'] }}+</p><p class="mt-1 text-[10px] font-semibold leading-4 text-gray-400">Produk dikurasi</p></div>
                    <div><p class="text-2xl font-extrabold text-gray-950">{{ $profileStats['locations'] }}</p><p class="mt-1 text-[10px] font-semibold leading-4 text-gray-400">Lokasi aktif</p></div>
                </div>

                <a href="{{ route('produk') }}" class="mt-9 inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-primary px-6 text-sm font-bold text-white shadow-[0_10px_24px_rgba(15,118,110,.22)] transition hover:-translate-y-0.5 hover:brightness-105">Jelajahi Produk <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f4f5f3] py-20 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 max-w-2xl">
            <p class="text-[10px] font-bold uppercase tracking-[.2em] text-primary">Cara Kami Bekerja</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-gray-950">Prinsip yang menjaga kualitas</h2>
        </div>
        <div class="grid gap-5 md:grid-cols-3">
            @foreach([
                ['01', 'Kurasi yang Bertanggung Jawab', 'Memilih kopi berdasarkan mutu, konsistensi, dan keterlacakan asalnya.'],
                ['02', 'Roasting yang Presisi', 'Mengembangkan profil roasting untuk menonjolkan karakter terbaik setiap biji.'],
                ['03', 'Pengalaman yang Konsisten', 'Menjaga standar produk dan pelayanan di setiap titik interaksi pelanggan.'],
            ] as [$number, $title, $copy])
                <article class="premium-surface rounded-2xl border border-white bg-white p-7 sm:p-8">
                    <div class="flex items-center justify-between"><span class="text-3xl font-extrabold text-secondary">{{ $number }}</span><span class="h-px w-12 bg-gray-200"></span></div>
                    <h3 class="mt-7 text-lg font-extrabold text-gray-950">{{ $title }}</h3>
                    <p class="mt-3 text-sm leading-6 text-gray-500">{{ $copy }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-20 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-[28px] bg-[#0b2420] shadow-[0_28px_75px_rgba(15,23,42,.18)]">
            <div class="grid lg:grid-cols-2">
                <div class="relative isolate p-8 text-white sm:p-12 lg:p-14">
                    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_0%_0%,rgba(245,158,11,.2),transparent_36%)]"></div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-secondary text-[#211b10]"><svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/><path d="m17.5 6.5 2-2"/></svg></div>
                    <p class="mt-7 text-[10px] font-bold uppercase tracking-[.2em] text-secondary">Visi</p>
                    <h2 class="mt-3 text-2xl font-extrabold leading-9">Arah jangka panjang kami</h2>
                    <p class="mt-5 text-sm leading-7 text-white/68">{{ $vision->isNotEmpty() ? $vision : 'Menjadi usaha kopi artisan Indonesia yang dikenal melalui kualitas produk dan pengalaman pelanggan yang konsisten.' }}</p>
                </div>
                <div class="border-t border-white/10 bg-white/5 p-8 text-white sm:p-12 lg:border-l lg:border-t-0 lg:p-14">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/15 bg-white/8 text-secondary"><svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3 3 8l9 5 9-5-9-5Z"/><path d="m3 12 9 5 9-5M3 16l9 5 9-5"/></svg></div>
                    <p class="mt-7 text-[10px] font-bold uppercase tracking-[.2em] text-secondary">Misi</p>
                    <h2 class="mt-3 text-2xl font-extrabold leading-9">Langkah yang kami jalankan</h2>
                    <p class="mt-5 whitespace-pre-line text-sm leading-7 text-white/68">{{ filled($mission) ? $mission : "1. Menghadirkan kopi pilihan dengan standar konsisten.\n2. Mengembangkan proses roasting secara berkelanjutan.\n3. Memberikan pengalaman layanan yang dapat dipercaya." }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-t border-gray-100 bg-[#f4f5f3] py-20 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-5 border-b border-gray-200 pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="text-[10px] font-bold uppercase tracking-[.2em] text-primary">Legalitas Usaha</p><h2 class="mt-3 text-3xl font-extrabold tracking-tight text-gray-950">Dokumen & sertifikasi</h2></div>
            <p class="max-w-md text-sm leading-6 text-gray-500">Informasi legalitas yang dikelola oleh administrator melalui profil usaha.</p>
        </div>

        @if($legalDocuments->isNotEmpty())
            <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($legalDocuments as $document)
                    <article class="premium-surface flex min-h-60 flex-col rounded-2xl border border-white bg-white p-7">
                        <div class="flex items-start justify-between gap-4"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/8 text-primary"><svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 3h7l4 4v14H7z"/><path d="M14 3v5h5M10 13l1.5 1.5L15 11"/></svg></span><span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider text-emerald-700">Tercatat</span></div>
                        <h3 class="mt-6 text-base font-extrabold leading-6 text-gray-950">{{ data_get($document, 'name', 'Dokumen Legalitas') }}</h3>
                        @if(filled(data_get($document, 'number')))<p class="mt-2 break-all font-mono text-xs leading-5 text-gray-500">{{ data_get($document, 'number') }}</p>@endif
                        <div class="mt-auto flex items-end justify-between gap-4 border-t border-gray-100 pt-5 text-[10px] text-gray-400"><span>{{ data_get($document, 'issuer', 'Instansi penerbit') }}</span><span class="shrink-0 font-bold text-gray-600">{{ data_get($document, 'year', '—') }}</span></div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="mt-8 rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center"><h3 class="font-bold text-gray-800">Informasi legalitas sedang dilengkapi</h3><p class="mt-2 text-sm text-gray-500">Dokumen resmi akan ditampilkan setelah diverifikasi dan ditambahkan oleh administrator.</p></div>
        @endif
    </div>
</section>
@endsection
