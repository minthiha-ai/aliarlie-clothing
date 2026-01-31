<?php

namespace App\Filament\Resources\Townships;

use App\Filament\Resources\Townships\Pages\CreateTownship;
use App\Filament\Resources\Townships\Pages\EditTownship;
use App\Filament\Resources\Townships\Pages\ListTownships;
use App\Filament\Resources\Townships\Schemas\TownshipForm;
use App\Filament\Resources\Townships\Tables\TownshipsTable;
use App\Models\Township;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TownshipResource extends Resource
{
    protected static ?string $model = Township::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Locations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return TownshipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TownshipsTable::configure($table);
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
            'index' => ListTownships::route('/'),
            'create' => CreateTownship::route('/create'),
            'edit' => EditTownship::route('/{record}/edit'),
        ];
    }
}
