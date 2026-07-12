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
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Dasar UMKM')
                            ->schema([
                                Forms\Components\TextInput::make('business_name')
                                    ->label('Nama Usaha / Brand')
                                    ->required(),

                                Forms\Components\TextInput::make('founded_year')
                                    ->label('Tahun Berdiri')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi Usaha (Storytelling Brand)')
                                    ->required()
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Section::make('Visi & Misi')
                            ->schema([
                                Forms\Components\Textarea::make('vision')
                                    ->label('Visi')
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('mission')
                                    ->label('Misi')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                    ])->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Branding')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('logo')
                                    ->label('Logo Usaha')
                                    ->collection('logo')
                                    ->avatar()
                                    ->imageResizeMode('force')
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth('200')
                                    ->imageResizeTargetHeight('200'),
                            ]),

                        Forms\Components\Section::make('Sertifikasi & Legalitas')
                            ->schema([
                                Forms\Components\Repeater::make('legal_docs')
                                    ->label('Dokumen Legal / Sertifikasi')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Dokumen')
                                            ->placeholder('Misal: Sertifikat Halal')
                                            ->required(),
                                        Forms\Components\TextInput::make('number')
                                            ->label('Nomor Dokumen')
                                            ->placeholder('Misal: ID3211...')
                                            ->required(),
                                        Forms\Components\TextInput::make('issuer')
                                            ->label('Penerbit')
                                            ->placeholder('BPJPH Kemenag')
                                            ->required(),
                                        Forms\Components\TextInput::make('year')
                                            ->label('Tahun Terbit')
                                            ->numeric()
                                            ->required(),
                                    ])
                                    ->defaultItems(1)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                            ])
                    ])->columnSpan(1)
            ])->columns(3);
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
