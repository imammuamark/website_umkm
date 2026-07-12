<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessProfileResource\Pages;
use App\Models\BusinessProfile;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BusinessProfileResource extends Resource
{
    protected static ?string $model = BusinessProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Profil Usaha';

    protected static ?string $navigationLabel = 'Profil Usaha';

    protected static ?string $modelLabel = 'Profil Usaha';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Profil Bisnis')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Identitas Usaha')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        Forms\Components\Group::make([
                                            Forms\Components\TextInput::make('business_name')
                                                ->label('Nama Usaha / Brand')
                                                ->placeholder('Contoh: Panama Corner')
                                                ->required(),
                                            Forms\Components\TextInput::make('founded_year')
                                                ->label('Tahun Berdiri')
                                                ->numeric()
                                                ->placeholder('Contoh: 2021')
                                                ->minValue(1900)
                                                ->maxValue((int) date('Y'))
                                                ->helperText('Opsional. Isi hanya jika tahun berdiri sudah terverifikasi.'),
                                        ])->columnSpan(2),
                                        Forms\Components\Section::make('Logo Brand')
                                            ->schema([
                                                SpatieMediaLibraryFileUpload::make('logo')
                                                    ->label('')
                                                    ->collection('logo')
                                                    ->avatar()
                                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                    ->maxSize(2048)
                                                    ->alignCenter()
                                                    ->imageResizeMode('force')
                                                    ->imageCropAspectRatio('1:1')
                                                    ->imageResizeTargetWidth('200')
                                                    ->imageResizeTargetHeight('200'),
                                            ])->columnSpan(1),
                                        Forms\Components\Section::make('Foto Banner Tentang')
                                            ->schema([
                                                SpatieMediaLibraryFileUpload::make('about_image')
                                                    ->label('')
                                                    ->collection('about_image')
                                                    ->image()
                                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                    ->maxSize(5120)
                                                    ->imageResizeMode('cover')
                                                    ->imageCropAspectRatio('16:9')
                                                    ->alignCenter(),
                                            ])->columnSpan(1),
                                    ]),
                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi Usaha (Storytelling Brand)')
                                    ->placeholder('Tuliskan profil singkat, karakter, dan pengalaman yang ditawarkan usaha Anda...')
                                    ->required(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Arah & Prinsip')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Textarea::make('vision')
                                            ->label('Arah / Tujuan Usaha')
                                            ->placeholder('Tuliskan tujuan atau pengalaman yang ingin diwujudkan...')
                                            ->helperText('Ditampilkan pada kartu pertama di halaman profil.')
                                            ->rows(5)
                                            ->required(),
                                        Forms\Components\Textarea::make('mission')
                                            ->label('Prinsip / Komitmen')
                                            ->placeholder('Tuliskan satu prinsip per baris...')
                                            ->helperText('Setiap baris akan ditampilkan sebagai butir terpisah pada kartu kedua.')
                                            ->rows(5)
                                            ->required(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Sertifikasi & Legalitas')
                            ->icon('heroicon-o-document-check')
                            ->schema([
                                Forms\Components\Repeater::make('legal_docs')
                                    ->label('Daftar Dokumen Resmi & Sertifikasi')
                                    ->helperText('Tambahkan izin resmi seperti Sertifikat Halal, NIB, PIRT, dll.')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nama Sertifikat')
                                                    ->placeholder('Misal: Sertifikasi Halal MUI')
                                                    ->required(),
                                                Forms\Components\TextInput::make('number')
                                                    ->label('Nomor Lisensi/Sertifikat')
                                                    ->placeholder('Misal: ID3211000...')
                                                    ->required(),
                                                Forms\Components\TextInput::make('issuer')
                                                    ->label('Instansi Penerbit')
                                                    ->placeholder('BPJPH Kementerian Agama')
                                                    ->required(),
                                                Forms\Components\TextInput::make('year')
                                                    ->label('Tahun Penerbitan')
                                                    ->numeric()
                                                    ->placeholder('2024')
                                                    ->required(),
                                            ]),
                                    ])
                                    ->defaultItems(1)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Dokumen Legalitas')
                                    ->grid(2),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_name')
                    ->label('Nama Usaha'),
                Tables\Columns\TextColumn::make('founded_year')
                    ->label('Tahun Berdiri'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBusinessProfiles::route('/'),
            'create' => Pages\CreateBusinessProfile::route('/create'),
            'edit' => Pages\EditBusinessProfile::route('/{record}/edit'),
        ];
    }
}
