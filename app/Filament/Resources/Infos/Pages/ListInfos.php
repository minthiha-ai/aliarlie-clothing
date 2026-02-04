<?php

namespace App\Filament\Resources\Infos\Pages;

use App\Filament\Resources\Infos\InfoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInfos extends ListRecords
{
    protected static string $resource = InfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modal(),
        ];
    }
}
