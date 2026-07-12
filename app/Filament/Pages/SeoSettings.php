<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Models\ActivityLog;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class SeoSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Profil Usaha';
    protected static ?string $title = 'SEO & Integrasi Pemasaran';
    protected static ?string $navigationLabel = 'SEO & Integrasi';

    protected static string $view = 'filament.pages.seo-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'meta_title_default' => SiteSetting::get('meta_title_default'),
            'meta_description_default' => SiteSetting::get('meta_description_default'),
            'google_analytics_id' => SiteSetting::get('google_analytics_id'),
            'meta_pixel_id' => SiteSetting::get('meta_pixel_id'),
            'tiktok_pixel_id' => SiteSetting::get('tiktok_pixel_id'),
            'whatsapp_number' => SiteSetting::get('whatsapp_number'),
            'whatsapp_text_template' => SiteSetting::get('whatsapp_text_template'),
            'google_maps_embed' => SiteSetting::get('google_maps_embed'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('SEO Default Halaman Utama')
                    ->description('Meta tag default yang digunakan saat robot crawler mesin pencari memindai halaman utama.')
                    ->schema([
                        TextInput::make('meta_title_default')
                            ->label('Meta Title Default')
                            ->required(),
                        Textarea::make('meta_description_default')
                            ->label('Meta Description Default')
                            ->required()
                            ->rows(3),
                    ]),

                Section::make('Kanal Akuisisi & Kontak')
                    ->description('Konfigurasi tombol hubungi kami via WhatsApp.')
                    ->schema([
                        TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp (Gunakan Kode Negara, tanpa + atau 0 di depan)')
                            ->placeholder('Misal: 6281234567890')
                            ->required(),
                        Textarea::make('whatsapp_text_template')
                            ->label('Template Pesan Otomatis WhatsApp')
                            ->helperText('Gunakan tag {product_name} untuk produk yang dipesan.')
                            ->required()
                            ->rows(2),
                        Textarea::make('google_maps_embed')
                            ->label('Kode Embed Peta Google Maps (Iframe HTML)')
                            ->helperText('Salin kode HTML dari menu Bagikan > Sematkan Peta di Google Maps.')
                            ->rows(4),
                    ]),

                Section::make('Integrasi Script Pelacakan (Tracking Analytics)')
                    ->description('Kode ID untuk Google Analytics 4, Meta/Facebook Pixel, dan TikTok Pixel.')
                    ->schema([
                        TextInput::make('google_analytics_id')
                            ->label('Google Analytics 4 Measurement ID (GA4)')
                            ->placeholder('Misal: G-XXXXXXXXXX'),
                        TextInput::make('meta_pixel_id')
                            ->label('Meta / Facebook Pixel ID')
                            ->placeholder('Misal: 123456789012345'),
                        TextInput::make('tiktok_pixel_id')
                            ->label('TikTok Pixel ID')
                            ->placeholder('Misal: CXXXXXXXXXXXXXXXXXXX'),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
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

        ActivityLog::log('update_seo', 'Mengubah pengaturan SEO dan integrasi pemasaran.');

        Notification::make()
            ->title('Pengaturan Disimpan')
            ->body('Pengaturan SEO, kontak, dan integrasi analitik telah diperbarui.')
            ->success()
            ->send();
    }
}
