@if(isset($widgets) && is_array($widgets) && count($widgets) > 0)
    @php
        $youtubeWidgets = collect($widgets)->filter(fn($w) => ($w['type'] ?? '') === 'youtube')->values();
        $linkWidgets = collect($widgets)->filter(fn($w) => in_array($w['type'] ?? '', ['social', 'custom'], true))->values();
    @endphp

    @if($youtubeWidgets->isNotEmpty() && $linkWidgets->isNotEmpty())
        <!-- Side-by-side Layout (2 Columns) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
            <!-- Left Column: Roasting Process & Brewing -->
            <div class="lg:col-span-6 space-y-6">
                <h3 class="text-xl font-extrabold text-gray-950 font-title">Roasting Process & Brewing</h3>
                @foreach($youtubeWidgets as $widget)
                    @php
                        $videoUrl = $widget['video_url'] ?? '';
                        $embedId = '';
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $videoUrl, $matches)) {
                            $embedId = $matches[1];
                        }
                    @endphp
                    @if($embedId)
                        <div class="relative w-full aspect-video rounded-[24px] overflow-hidden shadow-lg border border-white/80 bg-black">
                            <iframe
                                src="https://www.youtube-nocookie.com/embed/{{ $embedId }}"
                                title="{{ $widget['title'] ?? 'YouTube video player' }}"
                                class="absolute inset-0 w-full h-full border-0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Right Column: Connect with Panama Corner -->
            <div class="lg:col-span-6 space-y-6">
                <h3 class="text-xl font-extrabold text-gray-950 font-title">Connect with Panama Corner</h3>
                <div class="grid grid-cols-3 gap-4">
                    @foreach($linkWidgets as $widget)
                        @php
                            $type = $widget['type'] ?? '';
                            $url = $widget['url'] ?? '#';
                            $label = '';
                            $iconSvg = '';

                            if ($type === 'social') {
                                $platform = $widget['platform'] ?? '';
                                $label = ucfirst($platform);
                                if ($platform === 'instagram') {
                                    $iconSvg = '<svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>';
                                } elseif ($platform === 'tiktok') {
                                    $iconSvg = '<svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>';
                                } elseif ($platform === 'threads') {
                                    $iconSvg = '<svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M12 2a10 10 0 1 0 10 10"></path></svg>';
                                } elseif ($platform === 'twitter') {
                                    $iconSvg = '<svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>';
                                } elseif ($platform === 'facebook') {
                                    $iconSvg = '<svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>';
                                } elseif ($platform === 'youtube') {
                                    $iconSvg = '<svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"></path><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"></polygon></svg>';
                                }
                            } elseif ($type === 'custom') {
                                $label = $widget['label'] ?? 'Tautan';
                                $icon = $widget['icon'] ?? 'link';
                                if ($icon === 'globe') {
                                    $iconSvg = '<svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>';
                                } elseif ($icon === 'phone') {
                                    $iconSvg = '<svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z"></path></svg>';
                                } elseif ($icon === 'mail') {
                                    $iconSvg = '<svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
                                } elseif ($icon === 'map-pin') {
                                    $iconSvg = '<svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                                } else {
                                    $iconSvg = '<svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>';
                                }
                            }
                        @endphp
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center justify-center rounded-2xl border border-gray-150 bg-white p-5 transition duration-300 hover:-translate-y-0.5 hover:border-primary/20 hover:bg-primary/5 hover:text-primary group shadow-[0_4px_12px_rgba(0,0,0,.02)]">
                            {!! $iconSvg !!}
                            <span class="mt-2.5 text-xs font-bold text-gray-600 group-hover:text-primary text-center truncate max-w-full transition duration-300">{{ $label }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <!-- Fallback if only one column exists -->
        <div class="space-y-12">
            <!-- Render YouTube Videos -->
            @if($youtubeWidgets->isNotEmpty())
                <div class="grid gap-8 md:grid-cols-2">
                    @foreach($youtubeWidgets as $widget)
                        @php
                            $videoUrl = $widget['video_url'] ?? '';
                            $embedId = '';
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $videoUrl, $matches)) {
                                $embedId = $matches[1];
                            }
                        @endphp
                        @if($embedId)
                            <div class="rounded-[28px] border border-white/80 bg-white p-6 md:p-8 shadow-[0_20px_50px_rgba(15,23,42,.04)] space-y-4">
                                <h3 class="text-lg font-extrabold text-gray-950 font-title">{{ $widget['title'] ?? 'Tonton Video Kami' }}</h3>
                                <div class="relative w-full aspect-video rounded-2xl overflow-hidden shadow-md border border-gray-100 bg-black">
                                    <iframe
                                        src="https://www.youtube-nocookie.com/embed/{{ $embedId }}"
                                        title="{{ $widget['title'] ?? 'YouTube video player' }}"
                                        class="absolute inset-0 w-full h-full border-0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <!-- Render Social & Custom Links -->
            @if($linkWidgets->isNotEmpty())
                <div class="rounded-[28px] border border-white/80 bg-white p-8 md:p-10 shadow-[0_20px_50px_rgba(15,23,42,.04)]">
                    <h3 class="text-xl font-extrabold text-gray-950 text-center sm:text-left mb-6">Hubungkan dengan Panama Corner</h3>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                        @foreach($linkWidgets as $widget)
                            @php
                                $type = $widget['type'] ?? '';
                                $url = $widget['url'] ?? '#';
                                $label = '';
                                $iconSvg = '';

                                if ($type === 'social') {
                                    $platform = $widget['platform'] ?? '';
                                    $label = ucfirst($platform);
                                    if ($platform === 'instagram') {
                                        $iconSvg = '<svg class="h-8 w-8 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>';
                                    } elseif ($platform === 'tiktok') {
                                        $iconSvg = '<svg class="h-8 w-8 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>';
                                    } elseif ($platform === 'threads') {
                                        $iconSvg = '<svg class="h-8 w-8 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M12 2a10 10 0 1 0 10 10"></path></svg>';
                                    } elseif ($platform === 'twitter') {
                                        $iconSvg = '<svg class="h-8 w-8 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>';
                                    } elseif ($platform === 'facebook') {
                                        $iconSvg = '<svg class="h-8 w-8 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>';
                                    } elseif ($platform === 'youtube') {
                                        $iconSvg = '<svg class="h-8 w-8 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"></path><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"></polygon></svg>';
                                    }
                                } elseif ($type === 'custom') {
                                    $label = $widget['label'] ?? 'Tautan';
                                    $icon = $widget['icon'] ?? 'link';
                                    if ($icon === 'globe') {
                                        $iconSvg = '<svg class="h-8 w-8 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>';
                                    } elseif ($icon === 'phone') {
                                        $iconSvg = '<svg class="h-8 w-8 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7A2 2 0 0 1 22 16.9Z"></path></svg>';
                                    } elseif ($icon === 'mail') {
                                        $iconSvg = '<svg class="h-8 w-8 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
                                    } elseif ($icon === 'map-pin') {
                                        $iconSvg = '<svg class="h-8 w-8 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                                    } else {
                                        $iconSvg = '<svg class="h-8 w-8 text-gray-400 group-hover:text-primary transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>';
                                    }
                                }
                            @endphp
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center justify-center rounded-2xl border border-gray-100 bg-[#f4f5f3]/40 p-5 transition duration-300 hover:-translate-y-1 hover:border-primary/20 hover:bg-primary/5 hover:text-primary group">
                                {!! $iconSvg !!}
                                <span class="mt-3 text-xs font-bold text-gray-600 group-hover:text-primary text-center truncate max-w-full transition duration-300">{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
@endif
