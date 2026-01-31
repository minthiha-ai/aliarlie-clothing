<?php

namespace App\Filament\Resources\StateRegions\Pages;

use App\Filament\Resources\StateRegions\StateRegionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStateRegions extends ListRecords
{
    protected static string $resource = StateRegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modal(),
        ];
    }
}
