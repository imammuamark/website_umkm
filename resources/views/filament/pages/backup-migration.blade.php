<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]">
        <div class="min-w-0 space-y-6">
            <x-filament::section
                heading="Paket Portabel Website"
                description="Pindahkan konten dan media antarserver tanpa menyalin akun, kata sandi, sesi, log aktivitas, maupun konfigurasi rahasia."
                icon="heroicon-o-archive-box-arrow-down"
            >
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Data CMS</p>
                        <p class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">{{ number_format($summary['records'] ?? 0) }}</p>
                        <p class="mt-1 text-xs text-gray-500">record siap diekspor</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Media</p>
                        <p class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">{{ number_format(($summary['media_bytes'] ?? 0) / 1048576, 1) }} MB</p>
                        <p class="mt-1 text-xs text-gray-500">{{ number_format($summary['media_files'] ?? 0) }} file</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Paket Media</p>
                        <p class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">{{ number_format($summary['media_parts'] ?? 0) }}</p>
                        <p class="mt-1 text-xs text-gray-500">maks. ±7 MB per paket</p>
                    </div>
                </div>

                @if(!($summary['zip_available'] ?? false))
                    <div class="mt-4 rounded-xl border border-danger-200 bg-danger-50 p-4 text-sm text-danger-700 dark:border-danger-900/50 dark:bg-danger-950/30 dark:text-danger-300">
                        Ekstensi PHP ZIP belum aktif. Manifest data tetap dapat digunakan, tetapi ekspor dan impor media memerlukan ZIP.
                    </div>
                @elseif(($summary['media_parts'] ?? 0) > 0)
                    <div class="mt-5 flex flex-wrap gap-2">
                        @for($part = 1; $part <= $summary['media_parts']; $part++)
                            <a href="{{ route('admin.portability.media', $part) }}" target="_blank" rel="noopener" class="inline-flex min-h-9 items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-primary-500 hover:text-primary-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                                <x-filament::icon icon="heroicon-o-arrow-down-tray" class="h-4 w-4" />
                                Paket {{ str_pad((string) $part, 2, '0', STR_PAD_LEFT) }}
                            </a>
                        @endfor
                    </div>
                @endif
            </x-filament::section>

            <form wire:submit.prevent="importData">
                {{ $this->form }}

                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <x-filament::button type="button" color="gray" icon="heroicon-o-magnifying-glass" wire:click="inspectManifest" wire:loading.attr="disabled">
                        Periksa Manifest
                    </x-filament::button>
                    <x-filament::button type="submit" icon="heroicon-o-circle-stack" wire:loading.attr="disabled">
                        Impor Data
                    </x-filament::button>
                    <x-filament::button type="button" color="gray" icon="heroicon-o-photo" wire:click="importMedia" wire:loading.attr="disabled">
                        Impor Media
                    </x-filament::button>
                </div>
            </form>
        </div>

        <aside class="space-y-6 xl:sticky xl:top-6 xl:self-start">
            <x-filament::section heading="Urutan Migrasi" icon="heroicon-o-list-bullet">
                <ol class="space-y-4 text-sm text-gray-600 dark:text-gray-300">
                    @foreach(['Unduh manifest data dan semua paket .umkm-media.', 'Siapkan aplikasi baru, database, APP_KEY, dan storage link.', 'Impor data dengan mode Gabungkan, lalu periksa hasilnya.', 'Unggah paket media tanpa mengekstraknya, lalu uji halaman publik.'] as $step)
                        <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary-50 text-xs font-bold text-primary-700 dark:bg-primary-950 dark:text-primary-300">{{ $loop->iteration }}</span><span>{{ $step }}</span></li>
                    @endforeach
                </ol>
            </x-filament::section>

            @if($inspection)
                <x-filament::section heading="Hasil Pemeriksaan" icon="heroicon-o-check-badge">
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-gray-500">Dibuat</dt><dd class="text-right font-semibold">{{ $inspection['generated_at'] }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-gray-500">Database asal</dt><dd class="font-semibold uppercase">{{ $inspection['source_driver'] }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-gray-500">Total record</dt><dd class="font-semibold">{{ number_format(array_sum($inspection['counts'])) }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-gray-500">Media tercatat</dt><dd class="font-semibold">{{ number_format($inspection['media']['files'] ?? 0) }}</dd></div>
                    </dl>
                </x-filament::section>
            @endif

            @if($result)
                <x-filament::section heading="Impor Terakhir" icon="heroicon-o-check-circle">
                    @if($result['type'] === 'data')
                        <p class="text-sm text-gray-600 dark:text-gray-300">Mode <strong>{{ $result['mode'] }}</strong> selesai untuk {{ number_format(array_sum($result['tables'])) }} record.</p>
                    @else
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ number_format($result['files']) }} file dari {{ number_format($result['archives']) }} paket berhasil dipulihkan.</p>
                    @endif
                </x-filament::section>
            @endif

            <div class="rounded-xl border border-warning-200 bg-warning-50 p-4 text-sm text-warning-800 dark:border-warning-900/50 dark:bg-warning-950/30 dark:text-warning-200">
                <strong>Catatan:</strong> checksum mendeteksi file rusak atau berubah, bukan membuktikan asal file. Simpan paket backup di lokasi privat.
            </div>
        </aside>
    </div>
</x-filament-panels::page>
