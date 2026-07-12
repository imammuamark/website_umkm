<x-filament-panels::page>
    <x-filament::section
        heading="Preview Aktual"
        description="Preview menggunakan komponen dan stylesheet yang sama persis dengan footer pada website publik. Simpan perubahan untuk memperbarui preview."
        icon="heroicon-o-eye"
    >
        <div
            x-data
            x-on:footer-saved.window="$refs.footerPreview.src = '{{ route('admin.footer-preview') }}?t=' + Date.now()"
            style="overflow: hidden; border: 1px solid rgb(229 231 235); border-radius: 16px; background: #09121f;"
        >
            <iframe
                x-ref="footerPreview"
                src="{{ route('admin.footer-preview') }}"
                title="Preview aktual footer website"
                loading="eager"
                style="display: block; width: 100%; height: 620px; border: 0; background: #09121f;"
            ></iframe>
        </div>
    </x-filament::section>

    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}
        <x-filament-panels::form.actions :actions="$this->getFormActions()" />
    </x-filament-panels::form>
</x-filament-panels::page>
