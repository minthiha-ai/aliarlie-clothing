<?php

namespace App\Filament\Resources\Townships\Pages;

use App\Filament\Resources\Townships\TownshipResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTownship extends EditRecord
{
    protected static string $resource = TownshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
