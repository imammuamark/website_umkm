<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivity extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Log Aktivitas Terbaru (Audit Trail)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ActivityLog::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->width('150px'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna/Admin')
                    ->placeholder('Sistem'),
                Tables\Columns\TextColumn::make('action')
                    ->label('Kategori Aksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'login' => 'success',
                        'update_profile', 'update_password' => 'info',
                        'update_theme', 'update_seo' => 'warning',
                        'create_product', 'create_article' => 'success',
                        'delete_product', 'delete_article' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi Aktivitas')
                    ->wrap(),
            ])
            ->paginated(false);
    }
}
