<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Profile Info Form -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Profil</h2>
            
            <form wire:submit="updateProfile" class="space-y-4">
                {{ $this->profileForm }}
                
                <div class="pt-2">
                    <x-filament::button type="submit" color="primary">
                        Simpan Profil
                    </x-filament::button>
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ubah Kata Sandi</h2>
            
            <form wire:submit="updatePassword" class="space-y-4">
                {{ $this->passwordForm }}
                
                <div class="pt-2">
                    <x-filament::button type="submit" color="primary">
                        Ubah Kata Sandi
                    </x-filament::button>
                </div>
            </form>
        </div>

        <!-- 2FA Controls -->
        <div class="col-span-1 md:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Autentikasi Dua Faktor (2FA)</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tambahkan keamanan ekstra untuk akun Anda dengan verifikasi dua langkah TOTP.</p>
                </div>
                
                <div>
                    @if(auth()->user()->two_factor_confirmed_at)
                        <span class="inline-flex items-center gap-x-1.5 rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-x-1.5 rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                            Tidak Aktif
                        </span>
                    @endif
                </div>
            </div>

            <hr class="border-gray-200 dark:border-gray-700 my-4" />

            <div class="space-y-4">
                @if(auth()->user()->two_factor_confirmed_at)
                    <!-- 2FA is Active -->
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Verifikasi dua faktor sedang aktif. Setiap kali Anda masuk, Anda perlu memasukkan kode verifikasi dari aplikasi authenticator (Google Authenticator, Microsoft Authenticator, Authy, dll).
                        </p>

                        <div class="flex flex-wrap gap-3">
                            <x-filament::button wire:click="disableTwoFactor" color="danger" wire:confirm="Apakah Anda yakin ingin menonaktifkan 2FA?">
                                Nonaktifkan 2FA
                            </x-filament::button>

                            <x-filament::button wire:click="$toggle('showingRecoveryCodes')" color="gray">
                                {{ $showingRecoveryCodes ? 'Sembunyikan Recovery Codes' : 'Lihat Recovery Codes' }}
                            </x-filament::button>
                        </div>

                        @if($showingRecoveryCodes)
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Recovery Codes Cadangan</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Simpan kode cadangan ini di tempat yang aman. Kode ini dapat digunakan untuk masuk jika Anda kehilangan akses ke perangkat authenticator Anda.</p>
                                <div class="grid grid-cols-2 gap-2 max-w-xs font-mono text-sm">
                                    @foreach(auth()->user()->recoveryCodes() as $code)
                                        <div class="p-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded text-center text-gray-800 dark:text-gray-200">
                                            {{ $code }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                @elseif($showingQrCode)
                    <!-- 2FA is enabling, awaiting confirmation -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div class="flex justify-center p-4 bg-white rounded-lg border border-gray-200">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>
                        
                        <div class="space-y-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Konfirmasi Pengaktifan 2FA</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                1. Pindai kode QR di samping menggunakan aplikasi verifikasi Anda.<br/>
                                2. Masukkan kode 6 digit dari aplikasi Anda di bawah ini untuk memverifikasi.
                            </p>
                            
                            <div class="space-y-2">
                                <label for="twoFactorCode" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Kode OTP 6-Digit</label>
                                <input type="text" id="twoFactorCode" wire:model="twoFactorCode" class="block w-full max-w-xs rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="000000" />
                            </div>

                            <div class="flex gap-2">
                                <x-filament::button wire:click="confirmTwoFactor" color="success">
                                    Verifikasi & Aktifkan
                                </x-filament::button>
                                <x-filament::button wire:click="disableTwoFactor" color="gray">
                                    Batalkan
                                </x-filament::button>
                            </div>
                        </div>
                    </div>

                @else
                    <!-- 2FA is inactive -->
                    <div class="space-y-3">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Anda belum mengaktifkan verifikasi dua langkah. Aktifkan fitur ini untuk melindungi akun Anda dari peretasan dan akses tidak sah.
                        </p>
                        <x-filament::button wire:click="enableTwoFactor" color="primary">
                            Aktifkan 2FA
                        </x-filament::button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
