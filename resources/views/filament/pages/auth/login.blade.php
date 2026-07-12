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

<div class="grid min-h-screen grid-cols-1 lg:grid-cols-12 bg-white dark:bg-gray-950 font-sans">
    <!-- Left Panel: Premium Brand Section (Hidden on mobile) -->
    <div class="relative hidden lg:flex lg:col-span-5 flex-col justify-between bg-teal-950 text-white p-12 overflow-hidden">
        <!-- Abstract gradient overlays -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.3),transparent)]"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-teal-900/40 via-teal-950/90 to-teal-950"></div>
        
        <!-- Top branding logo & name -->
        <div class="relative z-10 flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-teal-700/80 flex items-center justify-center font-extrabold text-white text-base shadow-lg border border-teal-500/20">
                PC
            </div>
            <span class="font-extrabold text-lg tracking-tight font-title">
                Panama Corner
            </span>
        </div>

        <!-- Middle marketing copy & dynamic feel -->
        <div class="relative z-10 space-y-6 my-auto">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-800/40 border border-teal-700/30 text-xs text-teal-300">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                Sistem Operasional Terintegrasi
            </div>
            <h1 class="text-4xl font-extrabold font-title leading-tight tracking-tight">
                Specialty Coffee & <br>Roastery Management
            </h1>
            <p class="text-teal-200/70 text-sm leading-relaxed max-w-sm font-light">
                Kelola katalog kopi pilihan, publikasi artikel edukasi, informasi outlet, serta tindak lanjuti pesan pelanggan dalam satu ekosistem digital enterprise.
            </p>
        </div>

        <!-- Bottom copyright & trust indicators -->
        <div class="relative z-10 flex items-center justify-between text-xs text-teal-300/40 border-t border-teal-850 pt-6">
            <span>&copy; {{ date('Y') }} Panama Corner.</span>
            <span>Enterprise Grade v3.0</span>
        </div>
    </div>

    <!-- Right Panel: Login Form (Centered) -->
    <div class="col-span-1 lg:col-span-7 flex flex-col justify-center px-6 py-12 sm:px-16 lg:px-24 bg-gray-50 dark:bg-gray-950">
        <div class="mx-auto w-full max-w-md space-y-8">
            <div class="space-y-3">
                <!-- Mobile brand header -->
                <div class="flex lg:hidden items-center gap-2 mb-6">
                    <div class="h-9 w-9 rounded-xl bg-teal-700 flex items-center justify-center font-bold text-white text-sm">
                        PC
                    </div>
                    <span class="font-extrabold text-base tracking-tight text-gray-900 dark:text-white">
                        Panama Corner
                    </span>
                </div>
                
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white font-title">
                    Selamat Datang Kembali
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Masukkan kredensial administrator untuk masuk ke sistem dashboard.
                </p>
            </div>

            <!-- Custom Form Wrapper with Filament's fields -->
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800/80 rounded-3xl p-8 shadow-xl shadow-gray-200/40 dark:shadow-none space-y-6">
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
        </div>
    </div>
</div>
