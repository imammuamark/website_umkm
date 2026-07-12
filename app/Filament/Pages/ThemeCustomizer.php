<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Models\ActivityLog;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class ThemeCustomizer extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationGroup = 'Profil Usaha';
    protected static ?string $title = 'Kustomisasi Tampilan';
    protected static ?string $navigationLabel = 'Kustomisasi Tampilan';

    protected static string $view = 'filament.pages.theme-customizer';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'theme_primary_color' => SiteSetting::get('theme_primary_color', '#0F766E'),
            'theme_secondary_color' => SiteSetting::get('theme_secondary_color', '#F59E0B'),
            'theme_font_title' => SiteSetting::get('theme_font_title', 'Plus Jakarta Sans'),
            'theme_font_body' => SiteSetting::get('theme_font_body', 'Inter'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
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
                    ])->columns(2)
            ])
            ->statePath('data');
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

        ActivityLog::log('update_theme', 'Mengubah warna dan tipografi tema website.');

        Notification::make()
            ->title('Perubahan Disimpan')
            ->body('Tampilan tema baru Anda berhasil diperbarui ke seluruh halaman publik.')
            ->success()
            ->send();
    }
}
