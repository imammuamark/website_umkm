<?php

namespace App\Filament\Resources\BusinessProfileResource\Pages;

use App\Filament\Resources\BusinessProfileResource;
use App\Models\BusinessProfile;
use Filament\Resources\Pages\ListRecords;

class ListBusinessProfiles extends ListRecords
{
    protected static string $resource = BusinessProfileResource::class;

    public function mount(): void
    {
        $profile = BusinessProfile::first();

        if ($profile) {
            $this->redirect($this->getResource()::getUrl('edit', ['record' => $profile->id]));
            return;
        }

        $this->redirect($this->getResource()::getUrl('create'));
    }
}
