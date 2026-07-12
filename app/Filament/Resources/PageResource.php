<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Konten Website';

    protected static ?string $title = 'Halaman Kustom';

    protected static ?string $navigationLabel = 'Halaman Kustom';

    protected static ?string $modelLabel = 'Halaman Kustom';

    protected static ?string $pluralModelLabel = 'Halaman Kustom';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Konten Halaman')
                            ->description('Tulis judul dan isi lengkap dari halaman kustom Anda.')
                            ->icon('heroicon-o-pencil-square')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Judul Halaman')
                                            ->placeholder('Contoh: Tanya Jawab (FAQ)')
                                            ->required()
                                            ->maxLength(160)
                                            ->live(debounce: 500)
                                            ->afterStateUpdated(function (Set $set, ?string $state, ?string $old, Get $get): void {
                                                // Sync slug
                                                $oldSlug = Str::slug((string) $old);
                                                if (blank($get('slug')) || $get('slug') === $oldSlug) {
                                                    $set('slug', Str::slug((string) $state));
                                                }
                                                // Sync meta title
                                                $oldMetaTitle = Str::limit(strip_tags((string) $old), 70);
                                                if (blank($get('meta_title')) || $get('meta_title') === $oldMetaTitle) {
                                                    $set('meta_title', Str::limit(strip_tags((string) $state), 70));
                                                }
                                            })
                                            ->columnSpan(2),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->prefix('/page/')
                                            ->placeholder('auto-slug')
                                            ->helperText('Dibuat otomatis jika dikosongkan.')
                                            ->maxLength(180)
                                            ->alphaDash()
                                            ->unique(ignoreRecord: true)
                                            ->columnSpan(1),
                                    ])
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make('content')
                                    ->label('Isi Konten Halaman')
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'h2', 'h3', 'blockquote',
                                        'bulletList', 'orderedList',
                                        'link', 'undo', 'redo',
                                    ])
                                    ->required()
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make('Presentasi & Visual')
                            ->description('Atur layout, hero, dan gambar pendukung tanpa mengubah template Blade.')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Select::make('template')
                                    ->label('Template Halaman')
                                    ->options([
                                        'standard' => 'Halaman Standar',
                                        'about' => 'Profil / Tentang Usaha',
                                        'locations' => 'Lokasi & Cabang',
                                    ])
                                    ->default('standard')
                                    ->required()
                                    ->live(),

                                Forms\Components\TextInput::make('eyebrow')
                                    ->label('Label di Atas Judul')
                                    ->placeholder('Contoh: Cerita & Nilai Kami')
                                    ->maxLength(80),

                                Forms\Components\Textarea::make('subtitle')
                                    ->label('Subjudul Hero')
                                    ->placeholder('Kalimat singkat yang menjelaskan halaman ini.')
                                    ->rows(3)
                                    ->maxLength(220)
                                    ->columnSpanFull(),

                                Forms\Components\ToggleButtons::make('hero_source')
                                    ->label('Sumber Gambar Hero')
                                    ->options([
                                        'upload' => 'Upload Media',
                                        'url' => 'URL Eksternal',
                                    ])
                                    ->icons([
                                        'upload' => 'heroicon-o-arrow-up-tray',
                                        'url' => 'heroicon-o-link',
                                    ])
                                    ->default('upload')
                                    ->inline()
                                    ->required()
                                    ->live()
                                    ->helperText('Pilih satu sumber aktif. File dan URL lama tetap disimpan saat Anda berganti sumber.')
                                    ->columnSpanFull(),

                                SpatieMediaLibraryFileUpload::make('hero_image')
                                    ->label('Gambar Hero')
                                    ->collection('hero_image')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(6144)
                                    ->imageEditor()
                                    ->imageEditorAspectRatios(['16:9', '21:9'])
                                    ->helperText('Rekomendasi 1920 × 900 px, orientasi landscape.')
                                    ->visible(fn (Get $get): bool => $get('hero_source') === 'upload')
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('hero_image_url')
                                    ->label('URL Gambar Hero')
                                    ->placeholder('https://images.unsplash.com/...')
                                    ->helperText('Gunakan URL HTTPS langsung ke gambar. Unsplash, CDN, dan object storage didukung.')
                                    ->url()
                                    ->startsWith('https://')
                                    ->required(fn (Get $get): bool => $get('hero_source') === 'url')
                                    ->visible(fn (Get $get): bool => $get('hero_source') === 'url')
                                    ->live(onBlur: true)
                                    ->columnSpanFull(),

                                Forms\Components\Placeholder::make('hero_url_preview')
                                    ->label('Preview URL Hero')
                                    ->content(function (Get $get): HtmlString|string {
                                        $url = $get('hero_image_url');

                                        if (! is_string($url) || ! str_starts_with($url, 'https://')) {
                                            return 'Masukkan URL HTTPS untuk menampilkan preview.';
                                        }

                                        return new HtmlString('<div style="overflow:hidden;border-radius:16px;border:1px solid #e5e7eb;background:#f3f4f6;aspect-ratio:16/7"><img src="'.e($url).'" alt="Preview hero" style="width:100%;height:100%;object-fit:cover" loading="lazy"></div>');
                                    })
                                    ->visible(fn (Get $get): bool => $get('hero_source') === 'url')
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('hero_alt')
                                    ->label('Alt Text Gambar Hero')
                                    ->maxLength(180)
                                    ->helperText('Jelaskan isi gambar untuk aksesibilitas dan SEO.')
                                    ->required(fn (Get $get): bool => filled($get('hero_image_url')) || $get('hero_source') === 'upload')
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('hero_credit')
                                    ->label('Kredit Foto')
                                    ->placeholder('Contoh: Foto oleh Nama Fotografer / Unsplash')
                                    ->maxLength(120)
                                    ->visible(fn (Get $get): bool => $get('hero_source') === 'url'),

                                Forms\Components\TextInput::make('hero_credit_url')
                                    ->label('URL Kredit')
                                    ->placeholder('https://unsplash.com/...')
                                    ->url()
                                    ->startsWith('https://')
                                    ->visible(fn (Get $get): bool => $get('hero_source') === 'url'),

                                SpatieMediaLibraryFileUpload::make('content_image')
                                    ->label('Gambar Cerita / Konten')
                                    ->collection('content_image')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->helperText('Digunakan oleh template Profil / Tentang Usaha.')
                                    ->visible(fn (Get $get): bool => $get('template') === 'about')
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('content_image_alt')
                                    ->label('Alt Text Gambar Konten')
                                    ->maxLength(180)
                                    ->visible(fn (Get $get): bool => $get('template') === 'about')
                                    ->columnSpanFull(),

                                Forms\Components\Section::make('Label Bagian Profil')
                                    ->description('Sesuaikan istilah dengan karakter usaha. Konten utama tetap dikelola dari Profil Bisnis.')
                                    ->icon('heroicon-o-adjustments-horizontal')
                                    ->visible(fn (Get $get): bool => $get('template') === 'about')
                                    ->schema([
                                        Forms\Components\TextInput::make('about_values_title')
                                            ->label('Judul Bagian')
                                            ->placeholder('Contoh: Yang Kami Jaga')
                                            ->helperText('Default: Arah & Prinsip Usaha')
                                            ->maxLength(100)
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('about_primary_label')
                                            ->label('Label Kartu Pertama')
                                            ->placeholder('Contoh: Tujuan Kami')
                                            ->helperText('Menampilkan isi kolom arah/tujuan dari Profil Bisnis.')
                                            ->maxLength(80),

                                        Forms\Components\TextInput::make('about_secondary_label')
                                            ->label('Label Kartu Kedua')
                                            ->placeholder('Contoh: Cara Kami Melayani')
                                            ->helperText('Menampilkan daftar prinsip atau komitmen dari Profil Bisnis.')
                                            ->maxLength(80),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('SEO & Social Preview')
                            ->description('Optimalkan tampilan halaman ini pada mesin pencari.')
                            ->icon('heroicon-o-magnifying-glass')
                            ->collapsed()
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->placeholder('Default: judul halaman')
                                    ->maxLength(70)
                                    ->live(onBlur: true)
                                    ->helperText(fn (?string $state): string => 'Dibuat otomatis jika dikosongkan. '.mb_strlen((string) $state).'/70 karakter'),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->placeholder('Default: cuplikan isi konten')
                                    ->rows(3)
                                    ->maxLength(170)
                                    ->live(onBlur: true)
                                    ->helperText(fn (?string $state): string => 'Dibuat otomatis jika dikosongkan. '.mb_strlen((string) $state).'/170 karakter'),

                                Forms\Components\Placeholder::make('search_preview')
                                    ->label('Preview Hasil Pencarian')
                                    ->content(fn (Get $get): string => ($get('meta_title') ?: $get('title') ?: 'Judul Halaman').' — '.Str::limit(strip_tags($get('meta_description') ?: $get('content') ?: 'Konten halaman akan tampil di sini.'), 155))
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Repeater::make('widgets')
                            ->label('Widget Aktif Halaman')
                            ->itemLabel(fn (array $state): ?string => match ($state['type'] ?? '') {
                                'social' => 'Media Sosial: '.ucfirst($state['platform'] ?? ''),
                                'youtube' => 'Video YouTube: '.($state['title'] ?? 'Tonton Video'),
                                'custom' => 'Tautan Kustom: '.($state['label'] ?? 'Link'),
                                default => 'Widget',
                            })
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Tipe Widget')
                                    ->options([
                                        'social' => 'Media Sosial (Instagram, TikTok, dll.)',
                                        'youtube' => 'Video YouTube',
                                        'custom' => 'Tautan Kustom',
                                    ])
                                    ->default('social')
                                    ->required()
                                    ->live()
                                    ->columnSpanFull(),

                                // Social Widget Fields
                                Forms\Components\Select::make('platform')
                                    ->label('Pilih Platform')
                                    ->options([
                                        'instagram' => 'Instagram',
                                        'tiktok' => 'TikTok',
                                        'threads' => 'Threads',
                                        'twitter' => 'X / Twitter',
                                        'facebook' => 'Facebook',
                                        'youtube' => 'YouTube',
                                    ])
                                    ->required()
                                    ->visible(fn (Get $get): bool => $get('type') === 'social'),

                                Forms\Components\TextInput::make('url')
                                    ->label('Tautan URL')
                                    ->url()
                                    ->required()
                                    ->placeholder('https://...')
                                    ->visible(fn (Get $get): bool => in_array($get('type'), ['social', 'custom'], true)),

                                // YouTube Widget Fields
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Video')
                                    ->default('Tonton Video Kami')
                                    ->required()
                                    ->visible(fn (Get $get): bool => $get('type') === 'youtube'),

                                Forms\Components\TextInput::make('video_url')
                                    ->label('YouTube Video URL')
                                    ->url()
                                    ->required()
                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                    ->visible(fn (Get $get): bool => $get('type') === 'youtube'),

                                // Custom Link Fields
                                Forms\Components\TextInput::make('label')
                                    ->label('Label Tautan')
                                    ->required()
                                    ->placeholder('Contoh: Website Partner')
                                    ->visible(fn (Get $get): bool => $get('type') === 'custom'),

                                Forms\Components\Select::make('icon')
                                    ->label('Preset Ikon')
                                    ->options([
                                        'link' => 'Rantai / Link',
                                        'globe' => 'Bola Dunia / Web',
                                        'phone' => 'Telepon',
                                        'mail' => 'Email',
                                        'map-pin' => 'Lokasi',
                                    ])
                                    ->default('link')
                                    ->required()
                                    ->visible(fn (Get $get): bool => $get('type') === 'custom'),
                            ])
                            ->collapsible()
                            ->collapsed()
                            ->cloneable()
                            ->columns(2)
                            ->createItemButtonLabel('Tambah Widget Baru'),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Pengaturan Menu & Status')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status Halaman')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Dipublikasikan',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                Forms\Components\Toggle::make('is_in_navigation')
                                    ->label('Tampilkan di Menu Navigasi')
                                    ->helperText('Tampilkan tautan halaman ini pada header menu di atas.')
                                    ->default(false)
                                    ->live(),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan Tampilan Menu')
                                    ->helperText('Angka lebih kecil tampil lebih awal.')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->visible(fn (Get $get): bool => (bool) $get('is_in_navigation')),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Halaman')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('URL Slug')
                    ->prefix('/page/')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'published' => 'Dipublikasikan',
                        default => 'Draft',
                    }),

                Tables\Columns\IconColumn::make('is_in_navigation')
                    ->label('Tampil di Menu')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Dipublikasikan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
