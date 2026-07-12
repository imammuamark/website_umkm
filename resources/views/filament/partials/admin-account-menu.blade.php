@php($adminUser = filament()->auth()->user())

<div class="admin-topbar-actions">
    <a href="/" target="_blank" rel="noopener" class="admin-site-link">
        <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="h-4 w-4" />
        <span>Lihat Website</span>
    </a>

    <div
        x-data="{
            open: false,
            theme: localStorage.getItem('theme') || 'system',
            setTheme(value) {
                this.theme = value
                localStorage.setItem('theme', value)
                this.$dispatch('theme-changed', value)
            },
        }"
        x-on:keydown.escape.window="open = false"
        class="admin-account-menu"
    >
        <button
            type="button"
            class="admin-account-trigger"
            aria-label="Buka menu akun"
            aria-controls="admin-account-dropdown"
            x-bind:aria-expanded="open.toString()"
            x-on:click.stop="open = ! open"
        >
            <x-filament-panels::avatar.user :user="$adminUser" />
            <span class="admin-account-trigger__copy">
                <strong>{{ $adminUser->name }}</strong>
                <small>Administrator</small>
            </span>
            <x-filament::icon icon="heroicon-m-chevron-down" class="admin-account-trigger__chevron h-4 w-4" x-bind:class="{ 'rotate-180': open }" />
        </button>

        <div
            id="admin-account-dropdown"
            x-cloak
            x-show="open"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 translate-y-1 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-1 scale-95"
            x-on:click.outside="open = false"
            class="admin-account-dropdown"
        >
            <div class="admin-account-dropdown__identity">
                <strong>{{ $adminUser->name }}</strong>
                <span>{{ $adminUser->email }}</span>
            </div>

            <a href="{{ route('filament.admin.pages.my-profile') }}" class="admin-account-dropdown__item" x-on:click="open = false">
                <x-filament::icon icon="heroicon-o-user-circle" class="h-5 w-5" />
                <span>Profil & Keamanan</span>
            </a>

            <div class="admin-account-dropdown__theme">
                <span>Tampilan</span>
                <div class="admin-account-theme-options" role="group" aria-label="Pilih tema dashboard">
                    <button type="button" aria-label="Gunakan tema terang" x-on:click.stop="setTheme('light')" x-bind:class="{ 'is-active': theme === 'light' }">
                        <x-filament::icon icon="heroicon-m-sun" class="h-4 w-4" />
                    </button>
                    <button type="button" aria-label="Gunakan tema gelap" x-on:click.stop="setTheme('dark')" x-bind:class="{ 'is-active': theme === 'dark' }">
                        <x-filament::icon icon="heroicon-m-moon" class="h-4 w-4" />
                    </button>
                    <button type="button" aria-label="Gunakan tema sistem" x-on:click.stop="setTheme('system')" x-bind:class="{ 'is-active': theme === 'system' }">
                        <x-filament::icon icon="heroicon-m-computer-desktop" class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ filament()->getLogoutUrl() }}">
                @csrf
                <button type="submit" class="admin-account-dropdown__item admin-account-dropdown__logout">
                    <x-filament::icon icon="heroicon-o-arrow-left-on-rectangle" class="h-5 w-5" />
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</div>
