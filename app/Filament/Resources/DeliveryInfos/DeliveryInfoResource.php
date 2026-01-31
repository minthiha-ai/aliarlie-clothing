<?php

namespace App\Filament\Resources\DeliveryInfos;

use App\Filament\Resources\DeliveryInfos\Pages\CreateDeliveryInfo;
use App\Filament\Resources\DeliveryInfos\Pages\EditDeliveryInfo;
use App\Filament\Resources\DeliveryInfos\Pages\ListDeliveryInfos;
use App\Filament\Resources\DeliveryInfos\Schemas\DeliveryInfoForm;
use App\Filament\Resources\DeliveryInfos\Tables\DeliveryInfosTable;
use App\Models\DeliveryInfo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeliveryInfoResource extends Resource
{
    protected static ?string $model = DeliveryInfo::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Locations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return DeliveryInfoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeliveryInfosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeliveryInfos::route('/'),
            'create' => CreateDeliveryInfo::route('/create'),
            'edit' => EditDeliveryInfo::route('/{record}/edit'),
        ];
    }
}
