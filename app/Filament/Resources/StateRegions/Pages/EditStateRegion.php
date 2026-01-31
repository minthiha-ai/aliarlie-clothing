<?php

namespace App\Filament\Resources\StateRegions\Pages;

use App\Filament\Resources\StateRegions\StateRegionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStateRegion extends EditRecord
{
    protected static string $resource = StateRegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
