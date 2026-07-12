<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Location;
use App\Models\Product;
use App\Models\Article;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $unreadCount = ContactMessage::where('is_completed', false)->count();

        return [
            Stat::make('Total Produk Kopi', Product::count())
                ->description('Produk aktif terdaftar')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            Stat::make('Pesan Masuk (Leads)', ContactMessage::count())
                ->description($unreadCount . ' pesan belum dibaca')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($unreadCount > 0 ? 'warning' : 'success'),
            Stat::make('Artikel Publikasi', Article::count())
                ->description('Edukasi & tips kopi aktif')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
            Stat::make('Cabang Roastery', Location::count())
                ->description('Lokasi operasional aktif')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('success'),
        ];
    }
}
