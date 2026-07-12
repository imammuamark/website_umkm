<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}
    </x-filament-panels::form>

    @if($this->accessPoints->isNotEmpty())
        <x-filament::section heading="File QR Siap Cetak" description="PNG cocok untuk desain digital dan percetakan. PDF menggunakan ukuran A4 dan dapat langsung dicetak." icon="heroicon-o-printer">
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach($this->accessPoints as $point)
                    <article class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0"><p class="truncate text-sm font-bold">{{ $point->label }}</p><p class="mt-1 text-xs text-gray-500">{{ number_format($point->scans_count) }} pemindaian · {{ ucfirst($point->type) }}</p></div>
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $point->is_active ? 'bg-success-500' : 'bg-gray-300' }}"></span>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.digital-menu.qr', [$point, 'png']) }}" class="inline-flex min-h-9 items-center justify-center rounded-lg border border-gray-200 px-3 text-xs font-bold hover:border-primary-500 hover:text-primary-600 dark:border-gray-700">PNG</a>
                            <a href="{{ route('admin.digital-menu.qr', [$point, 'pdf']) }}" class="inline-flex min-h-9 items-center justify-center rounded-lg bg-primary-600 px-3 text-xs font-bold text-white hover:bg-primary-500">PDF A4</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
