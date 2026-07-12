<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessProfileResource\Pages;
use App\Models\BusinessProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

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
                                Forms\Components\Grid::make(3)
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
                                                ->required(),
                                        ])->columnSpan(2),
                                        Forms\Components\Section::make('Logo Brand')
                                            ->schema([
                                                SpatieMediaLibraryFileUpload::make('logo')
                                                    ->label('')
                                                    ->collection('logo')
                                                    ->avatar()
                                                    ->alignCenter()
                                                    ->imageResizeMode('force')
                                                    ->imageCropAspectRatio('1:1')
                                                    ->imageResizeTargetWidth('200')
                                                    ->imageResizeTargetHeight('200'),
                                            ])->columnSpan(1),
                                    ]),
                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi Usaha (Storytelling Brand)')
                                    ->placeholder('Tuliskan kisah perjalanan roastery dan filosofi bisnis Anda...')
                                    ->required(),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Visi & Misi')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Textarea::make('vision')
                                            ->label('Visi Perusahaan')
                                            ->placeholder('Tuliskan visi jangka panjang brand...')
                                            ->rows(5)
                                            ->required(),
                                        Forms\Components\Textarea::make('mission')
                                            ->label('Misi Perusahaan')
                                            ->placeholder('Tuliskan langkah-langkah misi yang dijalankan...')
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
                    ])->columnSpanFull()
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
