@extends('layouts.app')

@section('title', 'Tentang Kami | ' . \App\Models\SiteSetting::get('meta_title_default', 'Panama Corner'))

@section('content')
<!-- Header Page -->
<section class="bg-gray-900 py-20 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
        <h1 class="text-4xl font-extrabold font-title tracking-tight sm:text-5xl">Tentang Kami</h1>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto">Mengenal lebih dekat perjalanan Panama Corner dalam meracik cita rasa kopi terbaik nusantara.</p>
    </div>
</section>

<!-- Story Section -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12">
        <div class="lg:col-span-7 space-y-6">
            <span class="text-xs text-primary font-bold uppercase tracking-widest">Cerita Kami</span>
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 font-title sm:text-4xl">Menjaga Warisan Kopi Nusantara Sejak 2021</h2>
            <div class="text-gray-600 space-y-4 leading-relaxed">
                <p>
                    {{ $profile->description ?? 'Panama Corner berawal dari kecintaan kami terhadap kopi spesialti premium yang kaya dan beragam. Sejak berdiri, kami berkomitmen untuk melestarikan tradisi seduh berkualitas tinggi dengan sentuhan inovasi modern.' }}
                </p>
                <p>
                    Kami bekerja sama secara langsung (direct trade) dengan para petani kopi di berbagai daerah, mulai dari Aceh Gayo, Mandheling, Kerinci, Temanggung, Kintamani, hingga Flores Bajawa. Kami memastikan setiap transaksi menguntungkan petani guna mendukung ekosistem kopi yang adil, berkelanjutan, dan berkualitas tinggi.
                </p>
            </div>
        </div>

        <div class="lg:col-span-5 flex flex-col justify-center space-y-6">
            <!-- Vision & Mission card list -->
            <div class="bg-gray-50 p-8 rounded-2xl border border-gray-100 space-y-6">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 font-title mb-2 flex items-center gap-2">
                        <span class="text-primary">👁️</span> Visi
                    </h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $profile->vision ?? 'Menjadi pelopor produk kopi artisan lokal terbaik yang diakui secara nasional maupun internasional.' }}
                    </p>
                </div>

                <hr class="border-gray-200" />

                <div>
                    <h3 class="font-bold text-lg text-gray-900 font-title mb-2 flex items-center gap-2">
                        <span class="text-primary">🎯</span> Misi
                    </h3>
                    <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $profile->mission ?? "1. Menyediakan biji kopi pilihan nusantara terbaik.\n2. Menerapkan teknik pemanggangan modern.\n3. Memberdayakan petani kopi lokal." }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Legal & Trust Indicators -->
<section class="py-24 bg-gray-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="text-xs text-primary font-bold uppercase tracking-widest">Kepatuhan & Kepercayaan</span>
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 font-title sm:text-4xl">Sertifikasi & Legalitas Usaha</h2>
            <p class="text-gray-500">Seluruh proses operasional dan produk kami dijalankan sesuai standar kepatuhan regulasi pangan di Indonesia.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $docs = $profile->legal_docs ?? [
                    ['name' => 'NIB (Nomor Induk Berusaha)', 'number' => '1209831920831', 'issuer' => 'Kementerian Investasi', 'year' => '2021'],
                    ['name' => 'Sertifikat Halal', 'number' => 'ID32110000293810822', 'issuer' => 'BPJPH Kemenag', 'year' => '2022'],
                    ['name' => 'P-IRT Dinas Kesehatan', 'number' => '5103273010452-26', 'issuer' => 'Dinkes Kota Bandung', 'year' => '2021']
                ];
            @endphp
            @foreach($docs as $doc)
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center text-lg font-bold">✓</div>
                        <h3 class="font-bold text-gray-900 font-title">{{ $doc['name'] }}</h3>
                        <p class="text-sm text-gray-500 font-mono">No. {{ $doc['number'] }}</p>
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex justify-between text-xs text-gray-400">
                        <span>Penerbit: {{ $doc['issuer'] }}</span>
                        <span>Tahun: {{ $doc['year'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
