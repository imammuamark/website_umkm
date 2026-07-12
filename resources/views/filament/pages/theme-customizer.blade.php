<x-filament-panels::page>
    <x-filament::section
        heading="Preview Website Aktual"
        description="Preview memakai halaman dan stylesheet publik yang sama. Simpan perubahan untuk memuat ulang preview."
        icon="heroicon-o-eye"
        collapsible
        collapsed
    >
        <div
            x-data
            x-on:theme-saved.window="$refs.sitePreview.src = '{{ route('home') }}?preview=' + Date.now()"
            style="overflow: hidden; border: 1px solid rgb(229 231 235); border-radius: 16px; background: #f8fafc;"
        >
            <iframe
                x-ref="sitePreview"
                src="{{ route('home') }}?preview=1"
                title="Preview aktual website publik"
                loading="lazy"
                style="display:block;width:100%;height:720px;border:0;background:#fff;"
            ></iframe>
        </div>
    </x-filament::section>

    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getFormActions()"
        />
    </x-filament-panels::form>
</x-filament-panels::page>
