<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Konten Website';

    protected static ?string $title = 'Susunan Menu';

    protected static ?string $navigationLabel = 'Susunan Menu';

    protected static ?string $modelLabel = 'Item Menu';

    protected static ?string $pluralModelLabel = 'Item Menu';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konfigurasi Link Menu')
                    ->description('Tentukan label menu dan tujuan tautan link.')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Label Menu')
                            ->placeholder('Contoh: Tentang Kami')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\Select::make('type')
                            ->label('Tipe Menu')
                            ->options([
                                'home' => 'Beranda',
                                'page' => 'Halaman Kustom',
                                'catalog' => 'Katalog Produk',
                                'articles' => 'Artikel',
                                'contact' => 'Kontak',
                                'custom' => 'Tautan Kustom',
                            ])
                            ->default('custom')
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('page_id')
                            ->label('Pilih Halaman Kustom')
                            ->options(Page::published()->pluck('title', 'id'))
                            ->searchable()
                            ->required(fn (Get $get): bool => $get('type') === 'page')
                            ->visible(fn (Get $get): bool => $get('type') === 'page')
                            ->live(),

                        Forms\Components\Placeholder::make('page_preview')
                            ->label('Preview Tautan Halaman')
                            ->visible(fn (Get $get): bool => $get('type') === 'page')
                            ->content(function (Get $get): string|HtmlString {
                                $pageId = $get('page_id');
                                if (! $pageId) {
                                    return new HtmlString('<span class="text-sm text-gray-500 italic">Pilih halaman kustom di atas untuk melihat preview tautan.</span>');
                                }

                                $page = Page::find($pageId);
                                if (! $page) {
                                    return new HtmlString('<span class="text-sm text-danger-600 font-bold">Halaman tidak ditemukan.</span>');
                                }

                                $url = url("/page/{$page->slug}");
                                $statusBadge = $page->status === 'published'
                                    ? '<span class="px-2 py-0.5 text-xs font-medium bg-emerald-50 text-emerald-700 rounded-full">Dipublikasikan</span>'
                                    : '<span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">Draft</span>';

                                return new HtmlString("
                                    <div class='rounded-xl border border-gray-150 bg-gray-50/50 p-4 shadow-sm space-y-2 max-w-xl'>
                                        <div class='flex items-center justify-between gap-3'>
                                            <span class='font-bold text-gray-950'>".e($page->title)."</span>
                                            {$statusBadge}
                                        </div>
                                        <div class='flex items-center justify-between gap-4 text-xs text-gray-500'>
                                            <span class='font-mono truncate max-w-xs'>/page/".e($page->slug)."</span>
                                            <a href='".e($url)."' target='_blank' rel='noopener noreferrer' class='font-bold text-primary hover:underline flex items-center gap-1 shrink-0'>
                                                Buka Halaman ↗
                                            </a>
                                        </div>
                                    </div>
                                ");
                            }),

                        Forms\Components\TextInput::make('url')
                            ->label('Tautan URL Kustom')
                            ->placeholder('Contoh: https://instagram.com/panamacorner')
                            ->required(fn (Get $get): bool => $get('type') === 'custom')
                            ->visible(fn (Get $get): bool => $get('type') === 'custom')
                            ->rules(['regex:/^(https:\/\/|\/[^\/])/'])
                            ->helperText('Gunakan URL HTTPS atau path internal seperti /kontak.')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Urutan')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan Menu')
                            ->helperText('Angka lebih kecil akan tampil lebih awal di menu.')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Aktifkan agar menu ini tampil ke publik.')
                            ->default(true)
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Label Menu')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'home' => 'info',
                        'page' => 'success',
                        'catalog' => 'warning',
                        'articles' => 'primary',
                        'contact' => 'gray',
                        'custom' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'home' => 'Beranda',
                        'page' => 'Halaman Kustom',
                        'catalog' => 'Katalog Produk',
                        'articles' => 'Artikel',
                        'contact' => 'Kontak',
                        'custom' => 'Tautan Kustom',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('target')
                    ->label('Tujuan Link')
                    ->state(fn (MenuItem $record): string => match ($record->type) {
                        'home' => '/',
                        'catalog' => '/produk',
                        'articles' => '/artikel',
                        'contact' => '/kontak',
                        'page' => $record->page ? '/page/'.$record->page->slug : '(Halaman tidak dipilih/dihapus)',
                        'custom' => $record->url ?? '',
                        default => '',
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
