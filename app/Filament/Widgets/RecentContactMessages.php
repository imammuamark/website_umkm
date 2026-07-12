<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentContactMessages extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Pesan Pelanggan Baru Belum Dibaca (Leads)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContactMessage::query()->latest()->where('is_completed', false)->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Masuk')
                    ->dateTime('d M Y H:i')
                    ->width('150px'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengirim'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon/WA'),
                Tables\Columns\TextColumn::make('message')
                    ->label('Isi Pesan')
                    ->limit(80)
                    ->wrap(),
            ])
            ->paginated(false)
            ->emptyStateHeading('Semua pesan pelanggan telah dibaca')
            ->emptyStateDescription('Tidak ada leads baru yang belum ditindaklanjuti.');
    }
}
