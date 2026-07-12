<!-- Inject Tailwind CSS CDN to compile custom classes on guest page -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#f0fdfa',
                        100: '#ccfbf1',
                        200: '#99f6e4',
                        300: '#5eead4',
                        400: '#2dd4bf',
                        500: '#14b8a6',
                        600: '#0d9488',
                        700: '#0f766e',
                        800: '#115e59',
                        900: '#134e4a',
                        950: '#042f2e',
                    }
                },
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                    title: ['Plus Jakarta Sans', 'sans-serif'],
                }
            }
        }
    }
</script>

<div class="min-h-screen flex flex-col items-center justify-center bg-slate-50 dark:bg-gray-950 px-4 font-sans relative overflow-hidden">
    <!-- Subtle premium background gradient decorative element -->
    <div class="absolute -top-40 -left-40 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -right-40 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl"></div>

    <div class="w-full max-w-md space-y-6 relative z-10">
        <!-- Logo & Branding Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex h-12 w-12 rounded-2xl bg-teal-800 flex items-center justify-center font-extrabold text-white text-lg shadow-xl shadow-teal-900/10 border border-teal-700/20 mx-auto">
                PC
            </div>
            <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white font-title">
                Panama Corner
            </h1>
        </div>

        <!-- Login Card -->
        <div class="bg-white dark:bg-gray-900 border border-slate-100 dark:border-gray-800 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none space-y-6">
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
        
        <!-- Footer -->
        <div class="text-center">
            <p class="text-[10px] text-slate-400 dark:text-slate-500">
                &copy; {{ date('Y') }} Panama Corner. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</div>
