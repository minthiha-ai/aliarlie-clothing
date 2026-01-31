<?php

namespace App\Filament\Resources\DeliveryInfos\Pages;

use App\Filament\Resources\DeliveryInfos\DeliveryInfoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeliveryInfo extends EditRecord
{
    protected static string $resource = DeliveryInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
