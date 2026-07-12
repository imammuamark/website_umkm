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

<div class="dark min-h-screen flex flex-col items-center justify-center bg-[#090d16] px-4 font-sans relative overflow-hidden">
    <!-- Ambient luxury lighting effects in the background -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-tr from-teal-500/10 to-amber-500/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-teal-500/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-amber-500/5 rounded-full blur-[100px] pointer-events-none"></div>

    <!-- Fine grid pattern to add luxury texture -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#0f172a_1px,transparent_1px),linear-gradient(to_bottom,#0f172a_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] opacity-30 pointer-events-none"></div>

    <div class="w-full max-w-[440px] space-y-8 relative z-10">
        <!-- Logo & Branding Header -->
        <div class="text-center space-y-4">
            <!-- Brushed gradient logo ring -->
            <div class="relative inline-flex items-center justify-center p-[1px] rounded-2xl bg-gradient-to-tr from-teal-500 via-slate-800 to-amber-500/50 shadow-2xl">
                <div class="h-12 w-12 rounded-[15px] bg-[#0d1527] flex items-center justify-center font-black text-white text-base tracking-wider">
                    PC
                </div>
            </div>
            <div class="space-y-1">
                <h1 class="text-xs font-bold tracking-[0.25em] text-teal-400 uppercase font-sans">
                    Panama Corner
                </h1>
            </div>
        </div>

        <!-- Glassmorphic Login Card -->
        <div class="bg-[#0e1626]/80 backdrop-blur-xl border border-slate-800/80 rounded-[32px] p-8 sm:p-10 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.7)] space-y-6">
            <div class="space-y-1.5 text-center">
                <h2 class="text-xl font-bold text-white tracking-tight font-title">
                    Masuk ke Sistem
                </h2>
                <p class="text-xs text-slate-400">
                    Masukkan akun administrator Anda untuk melanjutkan.
                </p>
            </div>

            <!-- Injected custom style blocks to override raw input designs to look even more high-end -->
            <style>
                .fi-fo-text-input input {
                    background-color: #0b111e !important;
                    border-color: #1e293b !important;
                    color: #f8fafc !important;
                    border-radius: 12px !important;
                    transition: all 0.2s ease-in-out !important;
                }
                .fi-fo-text-input input:focus {
                    border-color: #14b8a6 !important;
                    box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.15) !important;
                }
                .fi-btn {
                    border-radius: 12px !important;
                    transition: all 0.2s ease-in-out !important;
                }
            </style>

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
            <p class="text-[10px] text-slate-500 tracking-wider">
                &copy; {{ date('Y') }} Panama Corner. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</div>
