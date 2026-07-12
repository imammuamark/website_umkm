<?php

namespace App\Filament\Pages;

use App\Models\ActivityLog;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class MyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Pengaturan Keamanan';

    protected static ?string $title = 'Profil Saya & 2FA';

    protected static ?string $navigationLabel = 'Profil Saya';

    protected static string $view = 'filament.pages.my-profile';

    public ?array $profileData = [];

    public ?array $passwordData = [];

    public string $twoFactorCode = '';

    public bool $showingQrCode = false;

    public bool $showingRecoveryCodes = false;

    public function mount(): void
    {
        $user = Auth::user();

        $this->profileForm->fill([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    protected function getForms(): array
    {
        return [
            'profileForm' => $this->makeForm()
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->unique(User::class, 'email', ignorable: Auth::user()),
                ])
                ->statePath('profileData'),

            'passwordForm' => $this->makeForm()
                ->schema([
                    TextInput::make('current_password')
                        ->label('Kata Sandi Saat Ini')
                        ->password()
                        ->required(),
                    TextInput::make('new_password')
                        ->label('Kata Sandi Baru')
                        ->password()
                        ->required()
                        ->rule(Password::default()),
                    TextInput::make('new_password_confirmation')
                        ->label('Konfirmasi Kata Sandi Baru')
                        ->password()
                        ->required()
                        ->same('new_password'),
                ])
                ->statePath('passwordData'),
        ];
    }

    public function updateProfile(): void
    {
        $state = $this->profileForm->getState();
        $user = Auth::user();

        $user->update([
            'name' => $state['name'],
            'email' => $state['email'],
        ]);

        ActivityLog::log('update_profile', 'Memperbarui nama/email profil.');

        Notification::make()
            ->title('Profil Diperbarui')
            ->success()
            ->send();
    }

    public function updatePassword(): void
    {
        $state = $this->passwordForm->getState();
        /** @var User $user */
        $user = Auth::user();

        if (! Hash::check($state['current_password'], $user->password)) {
            Notification::make()
                ->title('Gagal Mengubah Kata Sandi')
                ->body('Kata sandi saat ini tidak cocok.')
                ->danger()
                ->send();

            return;
        }

        $user->update([
            'password' => Hash::make($state['new_password']),
        ]);

        $this->passwordForm->fill();

        ActivityLog::log('update_password', 'Mengubah kata sandi akun.');

        Notification::make()
            ->title('Kata Sandi Diubah')
            ->body('Kata sandi Anda berhasil diperbarui.')
            ->success()
            ->send();
    }

    // 2FA Functions
    public function enableTwoFactor(): void
    {
        /** @var User $user */
        $user = Auth::user();

        app(EnableTwoFactorAuthentication::class)($user);

        $this->showingQrCode = true;

        Notification::make()
            ->title('Langkah Verifikasi Dua Faktor')
            ->body('Pindai QR Code di bawah dengan aplikasi authenticator Anda, lalu masukkan kode untuk konfirmasi.')
            ->info()
            ->send();
    }

    public function confirmTwoFactor(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if (empty($this->twoFactorCode)) {
            Notification::make()
                ->title('Kode Diperlukan')
                ->danger()
                ->send();

            return;
        }

        try {
            app(ConfirmTwoFactorAuthentication::class)($user, $this->twoFactorCode);

            $this->showingQrCode = false;
            $this->showingRecoveryCodes = true;
            $this->twoFactorCode = '';

            ActivityLog::log('enable_2fa', 'Mengaktifkan verifikasi dua langkah (2FA).');

            Notification::make()
                ->title('2FA Aktif!')
                ->body('Verifikasi dua langkah berhasil diaktifkan.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Kode Tidak Valid')
                ->body('Kode OTP yang Anda masukkan salah atau kedaluwarsa. Silakan coba lagi.')
                ->danger()
                ->send();
        }
    }

    public function disableTwoFactor(): void
    {
        /** @var User $user */
        $user = Auth::user();

        app(DisableTwoFactorAuthentication::class)($user);

        $this->showingQrCode = false;
        $this->showingRecoveryCodes = false;
        $this->twoFactorCode = '';

        ActivityLog::log('disable_2fa', 'Menonaktifkan verifikasi dua langkah (2FA).');

        Notification::make()
            ->title('2FA Dinonaktifkan')
            ->body('Verifikasi dua langkah telah dimatikan.')
            ->warning()
            ->send();
    }
}
