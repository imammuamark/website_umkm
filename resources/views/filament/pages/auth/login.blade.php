<x-filament-panels::page.simple>
    <!-- Elegant Inline Styles for Premium Light Theme Overrides -->
    <style>
        body {
            background-color: #f8fafc !important;
            background-image: radial-gradient(ellipse 60% 50% at 50% 50%, #f0fdfa 0%, #f8fafc 100%) !important;
        }

        .fi-simple-main {
            border-radius: 28px !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #ffffff !important;
            box-shadow: 0 20px 40px -15px rgba(15, 118, 110, 0.07), 0 1px 3px rgba(0, 0, 0, 0.01) !important;
            padding: 40px !important;
        }

        /* High-end form inputs styling */
        .fi-fo-text-input input {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            border-radius: 12px !important;
            font-size: 14px !important;
            transition: all 0.2s ease-in-out !important;
        }

        .fi-fo-text-input input:focus {
            border-color: #0f766e !important;
            box-shadow: 0 0 0 2px rgba(15, 118, 110, 0.08) !important;
        }

        /* Primary action button (teal with hover transition effects) */
        .fi-btn {
            background-color: #0f766e !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            transition: all 0.2s ease-in-out !important;
        }

        .fi-btn:hover {
            background-color: #115e59 !important;
            transform: translateY(-0.5px) !important;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.12) !important;
        }

        /* Form labels */
        .fi-fo-field-label {
            font-weight: 600 !important;
            color: #334155 !important;
            font-size: 13px !important;
        }
    </style>

    <x-slot name="heading">
        <div class="space-y-4 text-center">
            <!-- Elegant Brand Logo Card -->
            <div class="relative inline-flex items-center justify-center p-[1px] rounded-2xl bg-gradient-to-tr from-teal-500 via-slate-100 to-amber-500/40 shadow-sm">
                <div class="h-12 w-12 rounded-[15px] bg-white flex items-center justify-center font-black text-teal-800 text-base tracking-wider border border-slate-50">
                    PC
                </div>
            </div>
            <div class="space-y-1">
                <h1 class="text-xs font-bold tracking-[0.25em] text-teal-800 uppercase font-sans">
                    Panama Corner
                </h1>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="space-y-1.5 text-center">
            <h2 class="text-xl font-bold text-slate-900 tracking-tight font-title">
                Masuk ke Sistem
            </h2>
            <p class="text-xs text-slate-400">
                Gunakan akun administrator Anda untuk melanjutkan.
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
