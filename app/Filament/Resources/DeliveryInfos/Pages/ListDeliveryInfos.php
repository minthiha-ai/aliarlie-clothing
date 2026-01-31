<?php

namespace App\Filament\Resources\DeliveryInfos\Pages;

use App\Filament\Resources\DeliveryInfos\DeliveryInfoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryInfos extends ListRecords
{
    protected static string $resource = DeliveryInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modal(),
        ];
    }
}
