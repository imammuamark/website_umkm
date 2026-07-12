<x-filament-panels::page.simple>
    <x-slot name="heading">
        <div class="space-y-4 text-center">
            <!-- Elegant Brand Logo Box -->
            <div class="inline-flex h-12 w-12 rounded-2xl bg-teal-800 flex items-center justify-center font-extrabold text-white text-lg shadow-md border border-teal-700/20 mx-auto">
                PC
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white font-title">
                Panama Corner
            </h1>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="space-y-1 text-center">
            <h2 class="text-lg font-semibold text-gray-950 dark:text-white">
                Masuk ke Sistem
            </h2>
            <p class="text-xs text-slate-400 dark:text-slate-500">
                Gunakan email dan kata sandi administrator Anda.
            </p>
        </div>

        {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

        <x-filament-panels::form wire:submit="authenticate">
            {{ $this->form }}

            <div class="pt-2">
                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </div>
        </x-filament-panels::form>

        {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}
    </div>
</x-filament-panels::page.simple>
