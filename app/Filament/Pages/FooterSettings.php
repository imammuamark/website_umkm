<?php

namespace App\Filament\Pages;

use App\Models\ActivityLog;
use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class FooterSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Profil Usaha';

    protected static ?string $navigationLabel = 'Pengaturan Footer';

    protected static ?string $title = 'Pengaturan Footer';

    protected static ?int $navigationSort = 20;

    protected static string $view = 'filament.pages.footer-settings';

    /** @var list<string> */
    public const KEYS = [
        'footer_description', 'footer_show_socials', 'instagram_url', 'facebook_url', 'tiktok_url',
        'footer_show_navigation', 'footer_navigation_title', 'footer_show_legal', 'footer_legal_title',
        'footer_legal_limit', 'footer_show_contact', 'footer_contact_title', 'email_address', 'office_phone',
        'footer_address', 'footer_cta_enabled', 'footer_cta_title', 'footer_cta_description',
        'footer_cta_button_label', 'footer_cta_button_url', 'footer_copyright',
    ];

    public ?array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('manage settings') === true;
    }

    public function mount(): void
    {
        $this->redirect('/admin/theme-customizer?design-tab=footer');
    }

    public function form(Form $form): Form
    {
        return $form->schema([self::configurationTabs()])->statePath('data');
    }

    public static function configurationTabs(): Tabs
    {
        return Tabs::make('Konfigurasi Footer')
            ->persistTabInQueryString('footer-tab')
            ->tabs([
                Tabs\Tab::make('Brand & Sosial')
                    ->icon('heroicon-o-building-storefront')
                    ->schema([
                        Section::make('Identitas Brand')
                            ->description('Nama dan logo otomatis mengikuti Profil Usaha. Di sini Anda hanya mengatur deskripsi singkat footer.')
                            ->schema([
                                Textarea::make('footer_description')
                                    ->label('Deskripsi singkat')
                                    ->helperText('Gunakan 1–2 kalimat yang menjelaskan usaha secara faktual.')
                                    ->rows(3)
                                    ->minLength(20)
                                    ->maxLength(320)
                                    ->live(onBlur: true)
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Media Sosial')
                            ->description('Tautan dibuka di tab baru dan hanya URL HTTPS yang diterima.')
                            ->schema([
                                Toggle::make('footer_show_socials')->label('Tampilkan media sosial')->live()->columnSpanFull(),
                                TextInput::make('instagram_url')->label('Instagram')->prefixIcon('heroicon-o-camera')->placeholder('https://instagram.com/namausaha')->url()->startsWith('https://')->maxLength(2048)->live(onBlur: true)->visible(fn (Get $get): bool => (bool) $get('footer_show_socials')),
                                TextInput::make('facebook_url')->label('Facebook')->prefixIcon('heroicon-o-globe-alt')->placeholder('https://facebook.com/namausaha')->url()->startsWith('https://')->maxLength(2048)->live(onBlur: true)->visible(fn (Get $get): bool => (bool) $get('footer_show_socials')),
                                TextInput::make('tiktok_url')->label('TikTok')->prefixIcon('heroicon-o-musical-note')->placeholder('https://tiktok.com/@namausaha')->url()->startsWith('https://')->maxLength(2048)->live(onBlur: true)->visible(fn (Get $get): bool => (bool) $get('footer_show_socials')),
                            ])->columns(3),
                    ]),

                Tabs\Tab::make('Kolom Footer')
                    ->icon('heroicon-o-view-columns')
                    ->schema([
                        Section::make('Navigasi')
                            ->description('Daftar link otomatis mengikuti Susunan Menu yang aktif; tidak perlu memasukkan link ulang.')
                            ->schema([
                                Toggle::make('footer_show_navigation')->label('Tampilkan kolom navigasi')->live(),
                                TextInput::make('footer_navigation_title')->label('Judul kolom')->maxLength(80)->live(onBlur: true)->required()->visible(fn (Get $get): bool => (bool) $get('footer_show_navigation')),
                            ])->columns(2),
                        Section::make('Informasi Legal')
                            ->description('Isi dokumen dikelola melalui Profil Usaha. Pilih berapa dokumen yang ditampilkan.')
                            ->schema([
                                Toggle::make('footer_show_legal')->label('Tampilkan informasi legal')->live(),
                                TextInput::make('footer_legal_title')->label('Judul kolom')->maxLength(80)->live(onBlur: true)->required()->visible(fn (Get $get): bool => (bool) $get('footer_show_legal')),
                                Select::make('footer_legal_limit')->label('Maksimum dokumen')->options([1 => '1 dokumen', 2 => '2 dokumen', 3 => '3 dokumen', 4 => '4 dokumen', 5 => '5 dokumen', 6 => '6 dokumen'])->native(false)->required()->visible(fn (Get $get): bool => (bool) $get('footer_show_legal')),
                            ])->columns(3),
                    ]),

                Tabs\Tab::make('Kontak')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Section::make('Informasi Kontak')
                            ->description('Hanya informasi yang terisi dan valid yang akan ditampilkan di footer.')
                            ->schema([
                                Toggle::make('footer_show_contact')->label('Tampilkan kolom kontak')->live()->columnSpanFull(),
                                TextInput::make('footer_contact_title')->label('Judul kolom')->maxLength(80)->live(onBlur: true)->required()->visible(fn (Get $get): bool => (bool) $get('footer_show_contact')),
                                TextInput::make('email_address')->label('Email')->prefixIcon('heroicon-o-envelope')->email()->maxLength(150)->live(onBlur: true)->visible(fn (Get $get): bool => (bool) $get('footer_show_contact')),
                                TextInput::make('office_phone')->label('Telepon')->prefixIcon('heroicon-o-phone')->tel()->regex('/^[0-9+() .-]{6,30}$/')->maxLength(30)->live(onBlur: true)->visible(fn (Get $get): bool => (bool) $get('footer_show_contact')),
                                Textarea::make('footer_address')->label('Alamat')->rows(3)->maxLength(300)->live(onBlur: true)->visible(fn (Get $get): bool => (bool) $get('footer_show_contact'))->columnSpanFull(),
                            ])->columns(3),
                    ]),

                Tabs\Tab::make('CTA & Copyright')
                    ->icon('heroicon-o-megaphone')
                    ->schema([
                        Section::make('Ajakan Bertindak (CTA)')
                            ->description('CTA bersifat opsional dan tampil sebagai panel di atas footer utama.')
                            ->schema([
                                Toggle::make('footer_cta_enabled')->label('Aktifkan CTA footer')->live()->columnSpanFull(),
                                TextInput::make('footer_cta_title')->label('Judul CTA')->maxLength(100)->live(onBlur: true)->required()->visible(fn (Get $get): bool => (bool) $get('footer_cta_enabled')),
                                Textarea::make('footer_cta_description')->label('Deskripsi CTA')->rows(2)->maxLength(240)->live(onBlur: true)->visible(fn (Get $get): bool => (bool) $get('footer_cta_enabled'))->columnSpanFull(),
                                TextInput::make('footer_cta_button_label')->label('Label tombol')->maxLength(60)->live(onBlur: true)->required()->visible(fn (Get $get): bool => (bool) $get('footer_cta_enabled')),
                                TextInput::make('footer_cta_button_url')->label('Tujuan tombol')->prefixIcon('heroicon-o-link')->helperText('Path internal seperti /kontak atau URL HTTPS.')->rules(['regex:/^(https:\/\/|\/[^\/])/'])->maxLength(2048)->live(onBlur: true)->required()->visible(fn (Get $get): bool => (bool) $get('footer_cta_enabled')),
                            ])->columns(2),
                        Section::make('Hak Cipta')
                            ->schema([
                                TextInput::make('footer_copyright')->label('Teks copyright')->helperText('Placeholder tersedia: {year} dan {business_name}.')->maxLength(240)->live(onBlur: true)->required()->columnSpanFull(),
                            ]),
                    ]),
            ])
            ->columnSpanFull();
    }

    protected function getFormActions(): array
    {
        return [Action::make('save')->label('Simpan Footer')->submit('save')->color('primary')];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (self::KEYS as $key) {
            SiteSetting::set($key, $state[$key] ?? null);
        }

        ActivityLog::log('update_footer', 'Memperbarui konfigurasi footer website.');

        $this->dispatch('footer-saved');

        Notification::make()->title('Footer berhasil diperbarui')->body('Perubahan langsung diterapkan pada halaman publik.')->success()->send();
    }

    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        return [
            'footer_description' => 'Tempat menikmati pilihan makanan, camilan, dan minuman dalam suasana yang nyaman.',
            'footer_show_socials' => true,
            'footer_show_navigation' => true,
            'footer_navigation_title' => 'Navigasi',
            'footer_show_legal' => true,
            'footer_legal_title' => 'Informasi Legal',
            'footer_legal_limit' => 3,
            'footer_show_contact' => true,
            'footer_contact_title' => 'Kontak',
            'footer_address' => 'Jl. Merdeka No. 56, Bandung',
            'footer_cta_enabled' => false,
            'footer_cta_title' => 'Ada yang ingin ditanyakan?',
            'footer_cta_description' => 'Hubungi tim kami untuk informasi menu dan pemesanan.',
            'footer_cta_button_label' => 'Hubungi Kami',
            'footer_cta_button_url' => '/kontak',
            'footer_copyright' => '© {year} {business_name}. Hak cipta dilindungi.',
        ];
    }
}
