@extends('layouts.app')

@section('title', 'Lokasi Toko & Jam Buka | ' . \App\Models\SiteSetting::get('meta_title_default', 'Aromatica Coffee'))

@section('content')
<!-- Header Page -->
<section class="bg-gray-900 py-16 text-white relative">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-3">
        <h1 class="text-3xl font-bold font-title tracking-tight sm:text-4xl">Lokasi & Jam Operasional</h1>
        <p class="text-gray-300 max-w-xl mx-auto">Kunjungi roastery kami untuk mencicipi kopi seduh manual secara langsung.</p>
    </div>
</section>

<!-- Locations Section -->
<section class="py-24 bg-white flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Iframe Embed Map -->
        @if($mapsEmbed)
            <div class="rounded-3xl overflow-hidden shadow-lg border border-gray-100 bg-gray-50 h-96 relative">
                {!! $mapsEmbed !!}
            </div>
        @endif

        <!-- Branches Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            @foreach($locations as $loc)
                <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between space-y-8 hover:shadow-md transition duration-300">
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <span class="inline-flex items-center gap-x-1.5 rounded-md bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">
                                Cabang Roastery
                            </span>
                            <h2 class="text-2xl font-bold text-gray-900 font-title">{{ $loc->name }}</h2>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $loc->address }}</p>
                        </div>

                        <!-- Schedule List -->
                        @if($loc->operating_hours)
                            <div class="space-y-2 pt-2">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Jam Operasional</h3>
                                <div class="divide-y divide-gray-200/50 text-sm">
                                    @foreach($loc->operating_hours as $days => $hours)
                                        <div class="flex justify-between py-2 text-gray-600">
                                            <span>{{ $days }}</span>
                                            <span class="font-semibold text-gray-900">{{ $hours }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-4 items-center justify-between">
                        <div class="text-sm text-gray-500">
                            📞 {{ $loc->phone }}
                        </div>
                        
                        @if($loc->latitude && $loc->longitude)
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $loc->latitude }},{{ $loc->longitude }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-primary hover:bg-primary/95 transition">
                                Petunjuk Arah &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
@endsection
