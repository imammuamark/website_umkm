<x-filament-panels::page.simple>
    <style>
        :root {
            --login-gold: #d9a936;
            --login-gold-dark: #b98722;
            --login-ink: #101615;
            --login-teal: #2f7d73;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            background-color: #f4f1e9 !important;
            background-image: linear-gradient(90deg, #f4f1e9 0%, #faf9f5 100%) !important;
        }

        body::before {
            position: fixed;
            z-index: 0;
            inset: 0 0 0 50%;
            content: '';
            opacity: 0.46;
            background-image:
                linear-gradient(135deg, transparent 46%, rgba(58, 65, 62, 0.1) 47%, rgba(58, 65, 62, 0.1) 49%, transparent 50%),
                linear-gradient(45deg, transparent 46%, rgba(58, 65, 62, 0.07) 47%, rgba(58, 65, 62, 0.07) 49%, transparent 50%);
            background-size: 72px 72px;
            pointer-events: none;
        }

        body::after {
            position: fixed;
            z-index: 0;
            inset: 0 50% 0 0;
            content: '';
            background-image:
                linear-gradient(90deg, rgba(2, 7, 6, 0.25), rgba(2, 7, 6, 0.04)),
                url("{{ asset('images/panama-roastery-hero.png') }}");
            background-position: center, center;
            background-repeat: no-repeat;
            background-size: cover, cover;
            pointer-events: none;
        }

        .fi-simple-layout {
            position: relative !important;
            z-index: 1 !important;
            display: grid !important;
            min-height: 100svh !important;
            place-items: center !important;
            padding: 74px 20px 32px !important;
        }

        .fi-simple-main-ctn {
            display: grid !important;
            width: 100% !important;
            max-width: 404px !important;
            place-items: center !important;
        }

        .fi-simple-main {
            position: relative !important;
            z-index: 10 !important;
            width: calc(100vw - 32px) !important;
            max-width: 404px !important;
            margin: 0 !important;
            padding: 54px 34px 28px !important;
            overflow: visible !important;
            transform: none !important;
            border: 1px solid rgba(255, 255, 255, 0.78) !important;
            border-radius: 24px !important;
            background:
                radial-gradient(circle at 16% 0%, rgba(255, 255, 255, 0.92), transparent 38%),
                linear-gradient(145deg, rgba(255, 255, 255, 0.76), rgba(242, 240, 233, 0.67)) !important;
            box-shadow:
                0 32px 80px rgba(5, 12, 10, 0.25),
                0 10px 28px rgba(5, 12, 10, 0.14),
                inset 0 1px 0 rgba(255, 255, 255, 0.92),
                inset 0 -1px 0 rgba(255, 255, 255, 0.48) !important;
            backdrop-filter: blur(30px) saturate(155%) !important;
            -webkit-backdrop-filter: blur(30px) saturate(155%) !important;
        }

        .fi-simple-header {
            display: none !important;
        }

        .login-stack {
            display: grid;
            gap: 24px;
        }

        .login-intro {
            position: relative;
            text-align: center;
        }

        .login-brand {
            position: absolute;
            top: -92px;
            left: 50%;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateX(-50%);
        }

        .login-brand__logo {
            position: relative;
            display: flex;
            width: 78px;
            height: 78px;
            flex: 0 0 78px;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid rgba(190, 142, 43, 0.42);
            border-radius: 999px;
            background:
                radial-gradient(circle at 34% 24%, rgba(255, 255, 255, 1), rgba(250, 247, 238, 0.98) 58%, rgba(238, 229, 207, 0.98));
            box-shadow:
                0 20px 38px rgba(5, 12, 10, 0.2),
                0 7px 14px rgba(5, 12, 10, 0.1),
                0 0 0 6px rgba(255, 255, 255, 0.48),
                0 0 0 7px rgba(185, 135, 34, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.96);
        }

        .login-brand__logo img {
            display: block !important;
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 100% !important;
            padding: 10px;
            object-fit: contain !important;
        }

        .login-title {
            margin-top: 2px;
            color: #111615;
            font-size: 25px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.035em;
        }

        .login-subtitle {
            margin-top: 8px;
            color: #596260;
            font-size: 12px;
            line-height: 1.6;
        }

        .fi-fo-field-wrp {
            gap: 7px !important;
        }

        .fi-fo-field-wrp-label label,
        .fi-fo-field-label {
            color: #26312f !important;
            font-size: 12px !important;
            font-weight: 700 !important;
        }

        .fi-input-wrp {
            min-height: 48px !important;
            overflow: hidden !important;
            border: 1px solid rgba(196, 160, 71, 0.54) !important;
            border-radius: 10px !important;
            background: rgba(255, 255, 255, 0.88) !important;
            box-shadow: 0 5px 16px rgba(15, 23, 42, 0.05), inset 0 1px 0 rgba(255, 255, 255, 0.9) !important;
            transition: border-color 160ms ease, box-shadow 160ms ease, transform 160ms ease !important;
        }

        .fi-input-wrp:focus-within {
            border-color: var(--login-gold) !important;
            box-shadow: 0 0 0 3px rgba(217, 169, 54, 0.16), 0 8px 22px rgba(15, 23, 42, 0.08) !important;
            transform: translateY(-1px);
        }

        .fi-input-wrp input {
            color: var(--login-ink) !important;
            background: transparent !important;
            font-size: 14px !important;
        }

        .fi-input-wrp input::placeholder {
            color: #7b817f !important;
        }

        .fi-btn.fi-color-primary {
            min-height: 48px !important;
            border: 1px solid rgba(255, 255, 255, 0.32) !important;
            border-radius: 10px !important;
            color: #17140d !important;
            background: linear-gradient(105deg, #e6b84b, #d39b27) !important;
            box-shadow: 0 11px 24px rgba(185, 135, 34, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.4) !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            transition: transform 160ms ease, box-shadow 160ms ease, filter 160ms ease !important;
        }

        .fi-btn.fi-color-primary:hover {
            filter: brightness(1.04) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 15px 30px rgba(185, 135, 34, 0.36), inset 0 1px 0 rgba(255, 255, 255, 0.44) !important;
        }

        .fi-checkbox-input:checked {
            background-color: var(--login-teal) !important;
        }

        .fi-link {
            color: var(--login-teal) !important;
        }

        .academic-attribution {
            color: #67706e;
            text-align: center;
        }

        .academic-attribution__meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #3f4947;
            font-size: 8px;
            font-weight: 650;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .academic-attribution__dot {
            width: 3px;
            height: 3px;
            flex: 0 0 3px;
            border-radius: 999px;
            background: var(--login-gold-dark);
            box-shadow: 0 0 0 3px rgba(185, 135, 34, 0.1);
        }

        @media (max-width: 767px) {
            body {
                background: #101715 !important;
            }

            body::before {
                display: none;
            }

            body::after {
                inset: 0;
                background-image:
                    linear-gradient(rgba(3, 8, 7, 0.62), rgba(3, 8, 7, 0.72)),
                    url("{{ asset('images/panama-roastery-hero.png') }}");
                background-position: center;
                background-size: cover;
            }

            .fi-simple-layout {
                min-height: 100svh !important;
                padding: 70px 16px 28px !important;
            }

            .fi-simple-main {
                padding: 52px 24px 26px !important;
                border-radius: 22px !important;
                background: linear-gradient(145deg, rgba(255, 255, 255, 0.9), rgba(242, 240, 233, 0.84)) !important;
            }

            .login-brand {
                top: -90px;
            }

            .login-title {
                font-size: 23px;
            }

            .academic-attribution__meta {
                flex-direction: column;
                gap: 3px;
            }

            .academic-attribution__dot {
                display: none;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .fi-input-wrp,
            .fi-btn {
                transition: none !important;
            }
        }
    </style>

    <div class="login-stack">
        @php
            $loginProfile = \App\Models\BusinessProfile::first();
            $loginLogo = $loginProfile?->getFirstMediaUrl('logo');
            $loginBusinessName = $loginProfile?->business_name ?? 'Panama Corner';
        @endphp
        <div class="login-intro">
            <div class="login-brand">
                @if($loginLogo)
                    <span class="login-brand__logo"><img src="{{ $loginLogo }}" alt="Logo {{ $loginBusinessName }}"></span>
                @else
                    @include('partials.brand-mark', ['class' => 'login-brand__logo bg-[#2f7d73] text-white'])
                @endif
            </div>
            <h1 class="login-title">Admin Portal</h1>
            <p class="login-subtitle">Kelola konten dan operasional website.</p>
        </div>

        {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

        <x-filament-panels::form wire:submit="authenticate">
            {{ $this->form }}

            <div class="pt-3">
                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </div>
        </x-filament-panels::form>

        {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}

        <footer class="academic-attribution border-t border-black/8 pt-4" aria-label="Atribusi proyek">
            <div class="academic-attribution__meta">
                <span>Proyek Kewirausahaan Kelompok 1</span>
                <span class="academic-attribution__dot" aria-hidden="true"></span>
                <span>Universitas UP45 Yogyakarta</span>
            </div>
        </footer>
    </div>
</x-filament-panels::page.simple>
