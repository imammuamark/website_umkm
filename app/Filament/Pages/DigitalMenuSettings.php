<?php

namespace App\Filament\Pages;

use App\Models\ActivityLog;
use App\Models\DigitalMenuAccessPoint;
use App\Models\DigitalMenuSetting;
use App\Models\ProductCategory;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DigitalMenuSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationGroup = 'Manajemen Produk';

    protected static ?string $navigationLabel = 'Digital Menu';

    protected static ?string $title = 'Digital Menu Workspace';

    protected static ?string $slug = 'digital-menu';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.digital-menu-settings';

    protected ?string $maxContentWidth = 'full';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('manage digital menu') === true;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Preview Menu')
                ->icon('heroicon-o-device-phone-mobile')
                ->color('gray')
                ->modalHeading('Preview Digital Menu')
                ->modalDescription('Preview menggunakan halaman publik dan data menu yang sama.')
                ->modalContent(fn () => view('filament.pages.digital-menu-preview-modal'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->modalWidth('md'),
            Action::make('openMenu')
                ->label('Buka Menu')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(route('digital-menu.index'))
                ->openUrlInNewTab()
                ->color('gray'),
            Action::make('saveHeader')
                ->label('Simpan Konfigurasi')
                ->icon('heroicon-o-check')
                ->action('save')
                ->keyBindings(['mod+s']),
        ];
    }

    public function mount(): void
    {
        $settings = DigitalMenuSetting::current()->toArray();
        $settings['access_points'] = DigitalMenuAccessPoint::orderBy('label')->get()->map(fn ($point) => [
            'id' => $point->id,
            'label' => $point->label,
            'type' => $point->type,
            'category_id' => $point->category_id,
            'is_active' => $point->is_active,
        ])->all();
        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Digital Menu Workspace')->persistTabInQueryString('menu-tab')->tabs([
                Forms\Components\Tabs\Tab::make('Publikasi')->icon('heroicon-o-globe-alt')->schema([
                    Forms\Components\Section::make('Status & Identitas')->description('Atur identitas halaman menu yang dibuka melalui link atau QR.')->schema([
                        Forms\Components\Toggle::make('is_enabled')->label('Aktifkan Digital Menu')->helperText('Jika dimatikan, URL /menu mengembalikan halaman 404.')->live(),
                        Forms\Components\TextInput::make('title')->label('Judul Menu')->required()->maxLength(100),
                        Forms\Components\Textarea::make('subtitle')->label('Deskripsi Singkat')->rows(2)->maxLength(220)->columnSpanFull(),
                        Forms\Components\Toggle::make('allow_indexing')->label('Izinkan mesin pencari mengindeks')->helperText('Default nonaktif untuk mencegah duplikasi dengan katalog website.'),
                    ])->columns(2),
                    Forms\Components\Section::make('Aksi Pengunjung')->schema([
                        Forms\Components\Toggle::make('cta_enabled')->label('Tampilkan tombol aksi')->live(),
                        Forms\Components\TextInput::make('cta_label')->label('Label Tombol')->maxLength(60)->visible(fn (Forms\Get $get) => $get('cta_enabled')),
                        Forms\Components\TextInput::make('cta_url')->label('URL Tujuan')->placeholder('/kontak atau https://wa.me/...')->maxLength(500)->visible(fn (Forms\Get $get) => $get('cta_enabled'))->columnSpanFull(),
                    ])->columns(2),
                ]),
                Forms\Components\Tabs\Tab::make('Tampilan')->icon('heroicon-o-swatch')->schema([
                    Forms\Components\Section::make('Pengalaman Menu')->schema([
                        Forms\Components\ToggleButtons::make('layout')
                            ->label('Mode Tampilan Default')
                            ->options(['visual' => 'Visual', 'balanced' => 'Seimbang', 'compact' => 'Ringkas'])
                            ->icons(['visual' => 'heroicon-o-photo', 'balanced' => 'heroicon-o-rectangle-group', 'compact' => 'heroicon-o-bars-3'])
                            ->helperText('Pengunjung tetap dapat mengganti mode. Pilihan mereka disimpan pada perangkat.')
                            ->inline()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('show_search')->label('Pencarian'),
                        Forms\Components\Toggle::make('show_images')->label('Foto Produk'),
                        Forms\Components\Toggle::make('show_descriptions')->label('Deskripsi Singkat'),
                        Forms\Components\Toggle::make('show_stock')->label('Status Ketersediaan'),
                        Forms\Components\Toggle::make('show_badges')->label('Badge Menu'),
                        Forms\Components\Toggle::make('show_unavailable')->label('Tetap Tampilkan yang Habis'),
                    ])->columns(3),
                    Forms\Components\Section::make('Warna')->schema([
                        Forms\Components\Toggle::make('use_theme_colors')->label('Ikuti warna tema website')->live(),
                        Forms\Components\ColorPicker::make('primary_color')->label('Warna Primer')->visible(fn (Forms\Get $get) => ! $get('use_theme_colors')),
                        Forms\Components\ColorPicker::make('accent_color')->label('Warna Aksen')->visible(fn (Forms\Get $get) => ! $get('use_theme_colors')),
                    ])->columns(3),
                ]),
                Forms\Components\Tabs\Tab::make('QR & Titik Akses')->icon('heroicon-o-qr-code')->schema([
                    Forms\Components\Section::make('Titik Akses')->description('Setiap titik menghasilkan QR dan statistik pemindaian sendiri. Simpan terlebih dahulu sebelum mengunduh QR baru.')->schema([
                        Forms\Components\Placeholder::make('access_context_help')
                            ->label('Cara memilih konteks')
                            ->content('Menu Umum membuka seluruh menu. Meja dan Area juga membuka seluruh menu, tetapi nama titik ditampilkan agar sumber pemindaian dapat dibedakan. Kategori langsung membuka kategori tertentu.')
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('access_points')->hiddenLabel()->addActionLabel('Tambah Titik QR')->reorderable(false)->collapsible()->itemLabel(fn (array $state): string => $state['label'] ?? 'Titik akses baru')->schema([
                            Forms\Components\Hidden::make('id'),
                            Forms\Components\TextInput::make('label')->label('Nama Titik')->placeholder('Contoh: Meja A1')->required()->maxLength(100),
                            Forms\Components\Select::make('type')->label('Konteks QR')->options(['general' => 'Menu Umum — seluruh menu', 'table' => 'Meja — seluruh menu + label meja', 'area' => 'Area — seluruh menu + label area', 'category' => 'Kategori — langsung terfilter'])->helperText('Konteks membantu membedakan penempatan dan statistik setiap QR.')->required()->live(),
                            Forms\Components\Select::make('category_id')->label('Kategori Awal')->options(fn () => ProductCategory::orderBy('name')->pluck('name', 'id'))->searchable()->visible(fn (Forms\Get $get) => $get('type') === 'category')->required(fn (Forms\Get $get) => $get('type') === 'category'),
                            Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
                        ])->columns(2),
                    ]),
                ]),
            ])->columnSpanFull(),
        ])->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [Action::make('save')->label('Simpan Konfigurasi')->icon('heroicon-o-check')->submit('save')];
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $points = $state['access_points'] ?? [];
        unset($state['access_points'], $state['id'], $state['created_at'], $state['updated_at']);

        if (($state['cta_enabled'] ?? false) && ! $this->isSafeUrl($state['cta_url'] ?? null)) {
            throw ValidationException::withMessages(['data.cta_url' => 'Gunakan path internal yang diawali / atau URL HTTPS.']);
        }

        DB::transaction(function () use ($state, $points): void {
            DigitalMenuSetting::current()->update($state);
            $kept = [];
            foreach ($points as $point) {
                $record = filled($point['id'] ?? null) ? DigitalMenuAccessPoint::find($point['id']) : null;
                $record ??= new DigitalMenuAccessPoint;
                $record->fill([
                    'label' => $point['label'], 'type' => $point['type'],
                    'category_id' => $point['type'] === 'category' ? ($point['category_id'] ?? null) : null,
                    'is_active' => (bool) ($point['is_active'] ?? false),
                ])->save();
                $kept[] = $record->id;
            }
            DigitalMenuAccessPoint::whereNotIn('id', $kept ?: [0])->delete();
        });

        ActivityLog::log('update_digital_menu', 'Memperbarui konfigurasi dan titik akses Digital Menu.');
        Notification::make()->title('Digital Menu diperbarui')->body('Konfigurasi, tampilan, dan titik QR berhasil disimpan.')->success()->send();
        $this->mount();
        $this->dispatch('digital-menu-saved');
    }

    public function getAccessPointsProperty()
    {
        return DigitalMenuAccessPoint::with('category')->orderBy('label')->get();
    }

    private function isSafeUrl(?string $url): bool
    {
        $url = trim((string) $url);

        return ($url !== '' && str_starts_with($url, '/') && ! str_starts_with($url, '//'))
            || (filter_var($url, FILTER_VALIDATE_URL) && parse_url($url, PHP_URL_SCHEME) === 'https');
    }
}
