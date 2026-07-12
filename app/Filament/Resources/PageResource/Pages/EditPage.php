<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Lihat Halaman')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (): string => route('page.detail', $this->record->slug))
                ->openUrlInNewTab()
                ->visible(fn (): bool => $this->record->status === 'published'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
