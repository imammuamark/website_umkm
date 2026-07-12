<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Manajemen Produk';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Produk')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi Lengkap')
                                    ->required()
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Section::make('Galeri Foto Produk')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('gallery')
                                    ->hiddenLabel()
                                    ->helperText('Unggah hingga 12 foto sekaligus. Seret kartu foto untuk mengatur urutan; foto pertama menjadi gambar utama katalog.')
                                    ->collection('gallery')
                                    ->conversion('thumb')
                                    ->image()
                                    ->multiple()
                                    ->appendFiles()
                                    ->reorderable()
                                    ->maxFiles(12)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(5120)
                                    ->maxParallelUploads(3)
                                    ->panelLayout('grid')
                                    ->imagePreviewHeight('160')
                                    ->imageEditor()
                                    ->imageEditorAspectRatios(['1:1', '4:3', '16:9'])
                                    ->orientImagesFromExif()
                                    ->openable()
                                    ->downloadable()
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status & Harga')
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('price')
                                    ->label('Harga (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('Misal: 85000'),

                                Forms\Components\Select::make('stock_status')
                                    ->label('Status Stok')
                                    ->options([
                                        'tersedia' => 'Tersedia',
                                        'habis' => 'Habis',
                                        'pre-order' => 'Pre-Order',
                                    ])
                                    ->default('tersedia')
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Label & Badge')
                            ->schema([
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Produk Unggulan')
                                    ->helperText('Tampilkan di halaman utama'),

                                Forms\Components\Toggle::make('is_bestseller')
                                    ->label('Terlaris (Bestseller)')
                                    ->helperText('Beri lencana terlaris'),
                            ]),

                        Forms\Components\Section::make('Digital Menu')
                            ->description('Atur penampilan produk pada Mode Menu dan QR.')
                            ->icon('heroicon-o-qr-code')
                            ->schema([
                                Forms\Components\Toggle::make('is_menu_visible')
                                    ->label('Tampilkan di Digital Menu')
                                    ->default(true),
                                Forms\Components\TextInput::make('menu_sort_order')
                                    ->label('Urutan Menu')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0),
                                Forms\Components\TextInput::make('menu_badge')
                                    ->label('Badge Khusus')
                                    ->placeholder('Contoh: Favorit')
                                    ->maxLength(40),
                                Forms\Components\Textarea::make('menu_short_description')
                                    ->label('Deskripsi Ringkas')
                                    ->helperText('Maksimal 220 karakter. Jika kosong, diambil dari deskripsi produk.')
                                    ->rows(3)
                                    ->maxLength(220),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('gallery')
                    ->label('Foto')
                    ->collection('gallery')
                    ->limit(1)
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_status')
                    ->label('Stok')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'habis' => 'danger',
                        'pre-order' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_menu_visible')
                    ->label('Digital Menu')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_bestseller')
                    ->label('Terlaris')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Dilihat')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('stock_status')
                    ->label('Status Stok')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'habis' => 'Habis',
                        'pre-order' => 'Pre-Order',
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
