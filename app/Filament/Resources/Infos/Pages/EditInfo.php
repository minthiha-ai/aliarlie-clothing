<?php

namespace App\Filament\Resources\Infos\Pages;

use App\Filament\Resources\Infos\InfoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInfo extends EditRecord
{
    protected static string $resource = InfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
