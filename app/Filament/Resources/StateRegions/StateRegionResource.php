<?php

namespace App\Filament\Resources\StateRegions;

use App\Filament\Resources\StateRegions\Pages\CreateStateRegion;
use App\Filament\Resources\StateRegions\Pages\EditStateRegion;
use App\Filament\Resources\StateRegions\Pages\ListStateRegions;
use App\Filament\Resources\StateRegions\Schemas\StateRegionForm;
use App\Filament\Resources\StateRegions\Tables\StateRegionsTable;
use App\Models\StateRegion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StateRegionResource extends Resource
{
    protected static ?string $model = StateRegion::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Locations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return StateRegionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StateRegionsTable::configure($table);
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
            'index' => ListStateRegions::route('/'),
            'create' => CreateStateRegion::route('/create'),
            'edit' => EditStateRegion::route('/{record}/edit'),
        ];
    }
}
