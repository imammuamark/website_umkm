<?php

namespace App\Filament\Pages;

use App\Models\ActivityLog;
use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ThemeCustomizer extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationGroup = 'Profil Usaha';

    protected static ?string $title = 'Kustomisasi Tampilan';

    protected static ?string $navigationLabel = 'Kustomisasi Tampilan';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.theme-customizer';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('manage theme') === true || auth()->user()?->can('manage settings') === true;
    }

    public function mount(): void
    {
        $settings = [
            'theme_primary_color' => SiteSetting::get('theme_primary_color', '#0F766E'),
            'theme_secondary_color' => SiteSetting::get('theme_secondary_color', '#F59E0B'),
            'theme_font_title' => SiteSetting::get('theme_font_title', 'Plus Jakarta Sans'),
            'theme_font_body' => SiteSetting::get('theme_font_body', 'Inter'),
            'hero_image_upload' => SiteSetting::get('hero_image_upload'),
            'hero_image_source' => SiteSetting::get('hero_image_source', 'upload'),
            'hero_image_url' => SiteSetting::get('hero_image_url', 'https://images.unsplash.com/photo-1511537190424-bbbab87ac5eb?q=80&w=1200&auto=format&fit=crop'),
            'hero_title' => SiteSetting::get('hero_title', 'Nikmati Cita Rasa Kopi Nusantara yang Sesungguhnya.'),
            'hero_subtitle' => SiteSetting::get('hero_subtitle', 'Kami mengurasi biji kopi pilihan dari petani lokal di seluruh penjuru Indonesia dan memanggangnya segar secara presisi untuk menghadirkan kualitas terbaik di setiap cangkir kopi Anda.'),
            'theme_favicon_upload' => SiteSetting::get('theme_favicon_upload'),
        ];

        foreach (['catalog', 'articles', 'contact'] as $page) {
            foreach (['source', 'upload', 'url', 'alt', 'credit', 'credit_url'] as $field) {
                $settings["{$page}_hero_{$field}"] = SiteSetting::get(
                    "{$page}_hero_{$field}",
                    $field === 'source' ? 'url' : null
                );
            }
        }

        foreach (FooterSettings::KEYS as $key) {
            $settings[$key] = SiteSetting::get($key, FooterSettings::defaults()[$key] ?? null);
        }

        foreach (['footer_show_socials', 'footer_show_navigation', 'footer_show_legal', 'footer_show_contact', 'footer_cta_enabled'] as $key) {
            $settings[$key] = filter_var($settings[$key], FILTER_VALIDATE_BOOL);
        }

        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Design Workspace')
                    ->persistTabInQueryString('design-tab')
                    ->tabs([
                        Tabs\Tab::make('Brand & Tema')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Section::make('Palet Warna Brand')
                                    ->description('Warna yang dipilih akan digunakan di seluruh halaman website publik.')
                                    ->schema([
                                        ColorPicker::make('theme_primary_color')
                                            ->label('Warna Primer (Primary)')
                                            ->required(),
                                        ColorPicker::make('theme_secondary_color')
                                            ->label('Warna Sekunder (Secondary/Highlight)')
                                            ->required(),
                                    ])->columns(2),

                                Section::make('Favicon Website')
                                    ->description('Favicon adalah ikon kecil yang muncul pada tab browser Anda. Unggah file gambar dengan format .ico atau .png.')
                                    ->schema([
                                        FileUpload::make('theme_favicon_upload')
                                            ->label('Unggah Favicon')
                                            ->image()
                                            ->maxSize(1024)
                                            ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/vnd.microsoft.icon'])
                                            ->directory('settings')
                                            ->visibility('public')
                                            ->disk('public'),
                                    ]),

                                Section::make('Tipografi (Fonts)')
                                    ->description('Pilih kombinasi font dari Google Fonts untuk judul dan teks konten.')
                                    ->schema([
                                        Select::make('theme_font_title')
                                            ->label('Font Judul')
                                            ->options([
                                                'Poppins' => 'Poppins',
                                                'Plus Jakarta Sans' => 'Plus Jakarta Sans',
                                                'Outfit' => 'Outfit',
                                                'Playfair Display' => 'Playfair Display (Klasik)',
                                            ])
                                            ->required(),
                                        Select::make('theme_font_body')
                                            ->label('Font Teks / Isi')
                                            ->options([
                                                'Inter' => 'Inter',
                                                'Nunito Sans' => 'Nunito Sans',
                                                'Roboto' => 'Roboto',
                                                'Open Sans' => 'Open Sans',
                                            ])
                                            ->required(),
                                    ])->columns(2),

                            ]),

                        Tabs\Tab::make('Hero Beranda')
                            ->icon('heroicon-o-home')
                            ->schema([

                                Section::make('Konten & Gambar Hero Halaman Utama')
                                    ->description('Konfigurasi teks judul, subjudul, serta gambar latar belakang hero di halaman utama.')
                                    ->schema([
                                        TextInput::make('hero_title')
                                            ->label('Judul Hero')
                                            ->placeholder('Nikmati Cita Rasa Kopi Nusantara yang Sesungguhnya.')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Textarea::make('hero_subtitle')
                                            ->label('Subjudul Hero')
                                            ->placeholder('Kami mengurasi biji kopi pilihan dari petani lokal...')
                                            ->required()
                                            ->maxLength(500)
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        Select::make('hero_image_source')
                                            ->label('Sumber gambar')
                                            ->options(['upload' => 'Upload lokal', 'url' => 'URL eksternal'])
                                            ->native(false)
                                            ->live()
                                            ->required()
                                            ->columnSpanFull(),

                                        FileUpload::make('hero_image_upload')
                                            ->label('Gambar hero')
                                            ->helperText('Rekomendasi 1920 × 900 px, JPG/PNG/WebP maksimal 5 MB.')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatios(['16:9', '21:9'])
                                            ->maxSize(5120)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->directory('settings')
                                            ->visibility('public')
                                            ->disk('public')
                                            ->visible(fn (Get $get): bool => $get('hero_image_source') === 'upload'),

                                        TextInput::make('hero_image_url')
                                            ->label('URL gambar HTTPS')
                                            ->url()
                                            ->startsWith('https://')
                                            ->maxLength(2048)
                                            ->placeholder('https://images.unsplash.com/...')
                                            ->visible(fn (Get $get): bool => $get('hero_image_source') === 'url'),
                                    ])->columns(2),
                            ]),

                        Tabs\Tab::make('Hero Halaman')
                            ->icon('heroicon-o-photo')
                            ->schema([

                                Section::make('Hero Halaman Publik')
                                    ->description('Kelola visual Katalog, Artikel, dan Kontak secara terpisah. Gunakan gambar horizontal beresolusi minimal 1600 × 700 px.')
                                    ->schema([
                                        $this->systemHeroSection('catalog', 'Katalog Produk'),
                                        $this->systemHeroSection('articles', 'Artikel & Jurnal'),
                                        $this->systemHeroSection('contact', 'Kontak'),
                                    ]),
                            ]),

                        Tabs\Tab::make('Footer')
                            ->icon('heroicon-o-rectangle-stack')
                            ->schema([
                                Section::make('Satu Sumber Pengaturan')
                                    ->description('Nama dan logo mengikuti Profil Usaha, navigasi mengikuti Susunan Menu, dan dokumen legal mengikuti data profil.')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([]),
                                FooterSettings::configurationTabs(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    private function systemHeroSection(string $key, string $label): Section
    {
        return Section::make($label)
            ->description("Gambar latar khusus untuk hero halaman {$label}.")
            ->schema([
                Select::make("{$key}_hero_source")
                    ->label('Sumber gambar')
                    ->options(['url' => 'URL eksternal', 'upload' => 'Upload lokal'])
                    ->native(false)
                    ->live()
                    ->required(),
                FileUpload::make("{$key}_hero_upload")
                    ->label('Upload gambar')
                    ->helperText('JPG, PNG, atau WebP; maksimal 5 MB.')
                    ->image()
                    ->imageEditor()
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->directory('settings/page-heroes')
                    ->visibility('public')
                    ->disk('public')
                    ->hidden(fn (Get $get): bool => $get("{$key}_hero_source") !== 'upload'),
                TextInput::make("{$key}_hero_url")
                    ->label('URL gambar HTTPS')
                    ->placeholder('https://images.unsplash.com/...')
                    ->url()
                    ->startsWith('https://')
                    ->maxLength(2048)
                    ->hidden(fn (Get $get): bool => $get("{$key}_hero_source") !== 'url'),
                TextInput::make("{$key}_hero_alt")
                    ->label('Teks alternatif')
                    ->helperText('Jelaskan isi gambar secara singkat untuk aksesibilitas dan SEO.')
                    ->maxLength(180),
                TextInput::make("{$key}_hero_credit")
                    ->label('Kredit foto (opsional)')
                    ->maxLength(120),
                TextInput::make("{$key}_hero_credit_url")
                    ->label('URL kredit (opsional)')
                    ->url()
                    ->startsWith('https://')
                    ->maxLength(2048),
            ])
            ->columns(2)
            ->collapsible()
            ->collapsed($key !== 'catalog');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach ($state as $key => $value) {
            SiteSetting::set($key, $value);
        }

        ActivityLog::log('update_theme', 'Mengubah tema dan visual hero halaman publik.');

        $this->dispatch('theme-saved');
        $this->dispatch('footer-saved');

        Notification::make()
            ->title('Perubahan Disimpan')
            ->body('Tema, hero, dan footer berhasil diperbarui pada website publik.')
            ->success()
            ->send();
    }
}
