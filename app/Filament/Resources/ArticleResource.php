<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Rules\TrustedArticleVideoUrl;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Manajemen Artikel';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Artikel';

    protected static ?string $pluralModelLabel = 'Artikel';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Ruang Kerja Konten')
                            ->description('Susun artikel yang terstruktur, mudah dibaca, dan siap tampil di berbagai ukuran layar.')
                            ->icon('heroicon-o-pencil-square')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Judul Artikel')
                                            ->placeholder('Tulis judul yang jelas dan spesifik')
                                            ->required()
                                            ->maxLength(160)
                                            ->live(debounce: 500)
                                            ->afterStateUpdated(function (Set $set, ?string $state, ?string $old, Get $get): void {
                                                // Sync slug in real-time if blank or matches old title slug
                                                $oldSlug = Str::slug((string) $old);
                                                if (blank($get('slug')) || $get('slug') === $oldSlug) {
                                                    $set('slug', Str::slug((string) $state));
                                                }
                                                // Sync meta title in real-time if blank or matches old title
                                                $oldMetaTitle = Str::limit(strip_tags((string) $old), 70);
                                                if (blank($get('meta_title')) || $get('meta_title') === $oldMetaTitle) {
                                                    $set('meta_title', Str::limit(strip_tags((string) $state), 70));
                                                }
                                            })
                                            ->columnSpan(2),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->prefix('/')
                                            ->placeholder('auto-slug')
                                            ->helperText('Dibuat otomatis jika dikosongkan.')
                                            ->maxLength(180)
                                            ->alphaDash()
                                            ->unique(ignoreRecord: true)
                                            ->columnSpan(1),
                                    ])
                                    ->columnSpanFull(),

                                Forms\Components\ToggleButtons::make('editor_mode')
                                    ->label('Mode Penulisan')
                                    ->options([
                                        'visual' => 'Editor Visual',
                                        'plain' => 'Teks Biasa',
                                        'html' => 'HTML',
                                    ])
                                    ->icons([
                                        'visual' => 'heroicon-o-document-text',
                                        'plain' => 'heroicon-o-bars-3-bottom-left',
                                        'html' => 'heroicon-o-code-bracket',
                                    ])
                                    ->default('visual')
                                    ->inline()
                                    ->required()
                                    ->live()
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make('content')
                                    ->label('Isi Artikel')
                                    ->helperText('Gunakan Heading 2 dan Heading 3 untuk membentuk daftar isi otomatis. Gambar dapat disisipkan langsung melalui tombol lampiran.')
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'bold', 'italic', 'underline', 'strike',
                                        'h2', 'h3', 'blockquote',
                                        'bulletList', 'orderedList', 'codeBlock',
                                        'link', 'undo', 'redo',
                                    ])
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('articles/inline')
                                    ->fileAttachmentsVisibility('public')
                                    ->saveUploadedFileAttachmentsUsing(function (TemporaryUploadedFile $file): string {
                                        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                                        if (! in_array($file->getMimeType(), $allowed, true) || $file->getSize() > 5 * 1024 * 1024) {
                                            throw ValidationException::withMessages([
                                                'content' => 'Gambar inline harus JPG, PNG, WebP, atau GIF dengan ukuran maksimal 5 MB.',
                                            ]);
                                        }

                                        $extension = strtolower($file->guessExtension() ?: 'jpg');

                                        return $file->storePubliclyAs(
                                            'articles/inline/'.now()->format('Y/m'),
                                            Str::uuid().'.'.$extension,
                                            'public',
                                        );
                                    })
                                    ->disableGrammarly()
                                    ->required(fn (Get $get): bool => $get('editor_mode') === 'visual')
                                    ->visible(fn (Get $get): bool => $get('editor_mode') === 'visual')
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('content_plain')
                                    ->label('Isi Artikel — Teks Biasa')
                                    ->helperText('Baris kosong akan dibuat menjadi paragraf. Seluruh karakter HTML akan dianggap sebagai teks.')
                                    ->rows(22)
                                    ->required(fn (Get $get): bool => $get('editor_mode') === 'plain')
                                    ->visible(fn (Get $get): bool => $get('editor_mode') === 'plain')
                                    ->dehydrated(fn (Get $get): bool => $get('editor_mode') === 'plain')
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('content_html')
                                    ->label('Isi Artikel — HTML Aman')
                                    ->helperText('HTML akan disanitasi saat disimpan. Script, iframe, event handler, style, dan URL berbahaya akan dihapus.')
                                    ->rows(24)
                                    ->required(fn (Get $get): bool => $get('editor_mode') === 'html')
                                    ->visible(fn (Get $get): bool => $get('editor_mode') === 'html')
                                    ->dehydrated(fn (Get $get): bool => $get('editor_mode') === 'html')
                                    ->extraInputAttributes([
                                        'class' => 'font-mono text-sm',
                                        'spellcheck' => 'false',
                                    ])
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('excerpt')
                                    ->label('Ringkasan Artikel')
                                    ->placeholder('Ringkasan singkat untuk kartu artikel dan hasil pencarian')
                                    ->helperText('Jika kosong, sistem membuat ringkasan dari isi artikel.')
                                    ->rows(4)
                                    ->maxLength(300)
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (Set $set, ?string $state, ?string $old, Get $get): void {
                                        // Sync meta description in real-time if blank or matches old excerpt
                                        $oldMetaDescription = Str::limit(trim(preg_replace('/\s+/', ' ', (string) $old)), 160);
                                        if (blank($get('meta_description')) || $get('meta_description') === $oldMetaDescription) {
                                            $set('meta_description', Str::limit(trim(preg_replace('/\s+/', ' ', (string) $state)), 160));
                                        }
                                    })
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make('Pratinjau Pencarian')
                            ->description('Optimalkan tampilan artikel pada mesin pencari dan media sosial.')
                            ->icon('heroicon-o-magnifying-glass')
                            ->collapsed()
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->placeholder('Default: judul artikel')
                                    ->maxLength(70)
                                    ->live(onBlur: true)
                                    ->helperText(fn (?string $state): string => 'Dibuat otomatis jika dikosongkan. '.mb_strlen((string) $state).'/70 karakter'),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->placeholder('Default: ringkasan artikel')
                                    ->rows(3)
                                    ->maxLength(170)
                                    ->live(onBlur: true)
                                    ->helperText(fn (?string $state): string => 'Dibuat otomatis jika dikosongkan. '.mb_strlen((string) $state).'/170 karakter'),

                                Forms\Components\Placeholder::make('search_preview')
                                    ->label('Preview Hasil Pencarian')
                                    ->content(fn (Get $get): string => ($get('meta_title') ?: $get('title') ?: 'Judul Artikel').' — '.Str::limit($get('meta_description') ?: $get('excerpt') ?: 'Ringkasan artikel akan tampil di sini.', 155))
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Workflow & Publikasi')
                            ->icon('heroicon-o-rocket-launch')
                            ->schema([
                                Forms\Components\Select::make('workflow_status')
                                    ->label('Status Editorial')
                                    ->options([
                                        'draft' => 'Draft',
                                        'in_review' => 'Menunggu Review',
                                        'scheduled' => 'Terjadwal',
                                        'published' => 'Dipublikasikan',
                                        'archived' => 'Diarsipkan',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->live()
                                    ->disableOptionWhen(fn (string $value): bool => in_array($value, ['scheduled', 'published', 'archived'], true) && ! auth()->user()?->can('publish articles')),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label(fn (Get $get): string => $get('workflow_status') === 'scheduled' ? 'Jadwal Publikasi' : 'Tanggal Publikasi')
                                    ->seconds(false)
                                    ->native(false)
                                    ->required(fn (Get $get): bool => $get('workflow_status') === 'scheduled')
                                    ->after('now')
                                    ->visible(fn (Get $get): bool => in_array($get('workflow_status'), ['scheduled', 'published'], true)),

                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')->required()->maxLength(100),
                                        Forms\Components\TextInput::make('slug')->required()->maxLength(120)->unique(ArticleCategory::class, 'slug'),
                                    ])
                                    ->createOptionUsing(fn (array $data): int => ArticleCategory::create([
                                        'name' => $data['name'],
                                        'slug' => Str::slug($data['slug']),
                                    ])->getKey()),

                                Forms\Components\Placeholder::make('author_display')
                                    ->label('Penulis')
                                    ->content(fn (?Article $record): string => $record?->author?->name ?? auth()->user()?->name ?? 'Pengguna aktif'),

                                Forms\Components\Placeholder::make('revision_display')
                                    ->label('Versi Konten')
                                    ->content(fn (?Article $record): string => 'Revisi '.($record?->revision ?? 1)),

                                Forms\Components\Hidden::make('expected_lock_version'),

                                Forms\Components\Placeholder::make('content_check')
                                    ->label('Checklist Konten')
                                    ->content(fn (Get $get): string => filled($get('content')) || filled($get('content_plain')) || filled($get('content_html')) ? '✓ Konten tersedia' : '○ Konten belum tersedia'),

                                Forms\Components\Placeholder::make('seo_check')
                                    ->label('Checklist SEO')
                                    ->content(fn (Get $get): string => filled($get('meta_title')) && filled($get('meta_description')) ? '✓ Metadata lengkap' : '○ Menggunakan nilai default'),
                            ]),

                        Forms\Components\Section::make('Featured Image')
                            ->description('Rasio yang disarankan 1200 × 630 piksel.')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('featured_image')
                                    ->label('Gambar Utama')
                                    ->collection('featured_image')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->responsiveImages()
                                    ->required(fn (Get $get): bool => in_array($get('workflow_status'), ['scheduled', 'published'], true)),
                            ]),

                        Forms\Components\Section::make('Media Artikel')
                            ->description('Kelola galeri pendukung dan video tanpa menempelkan kode embed.')
                            ->icon('heroicon-o-photo')
                            ->collapsible()
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('content_images')
                                    ->label('Galeri Gambar')
                                    ->collection('content_images')
                                    ->multiple()
                                    ->reorderable()
                                    ->appendFiles()
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxFiles(12)
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->responsiveImages()
                                    ->panelLayout('grid')
                                    ->helperText('Maksimal 12 gambar, masing-masing 5 MB. Seret kartu untuk mengatur urutan.'),

                                Forms\Components\Repeater::make('video_urls')
                                    ->label('Video Pendukung')
                                    ->addActionLabel('Tambahkan video')
                                    ->defaultItems(0)
                                    ->maxItems(5)
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): string => ($state['title'] ?? null) ?: ($state['url'] ?? 'Video baru'))
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Judul Video')
                                            ->placeholder('Contoh: Teknik menuang air')
                                            ->maxLength(120),
                                        Forms\Components\TextInput::make('url')
                                            ->label('URL YouTube / Vimeo')
                                            ->placeholder('https://www.youtube.com/watch?v=...')
                                            ->required()
                                            ->maxLength(500)
                                            ->rule(new TrustedArticleVideoUrl),
                                    ])
                                    ->columns(1),
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')->label('Gambar')->collection('featured_image')->square(),
                Tables\Columns\TextColumn::make('title')->label('Judul Artikel')->searchable()->sortable()->wrap(),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori')->sortable(),
                Tables\Columns\TextColumn::make('author.name')->label('Penulis')->sortable(),
                Tables\Columns\TextColumn::make('workflow_status')
                    ->label('Workflow')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'in_review' => 'warning',
                        'scheduled' => 'info',
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in_review' => 'Menunggu Review',
                        'scheduled' => 'Terjadwal',
                        'published' => 'Dipublikasikan',
                        'archived' => 'Diarsipkan',
                        default => 'Draft',
                    }),
                Tables\Columns\TextColumn::make('published_at')->label('Publikasi')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('revision')->label('Revisi')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')->label('Kategori')->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('workflow_status')->label('Workflow')->options([
                    'draft' => 'Draft',
                    'in_review' => 'Menunggu Review',
                    'scheduled' => 'Terjadwal',
                    'published' => 'Dipublikasikan',
                    'archived' => 'Diarsipkan',
                ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
