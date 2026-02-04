<?php

namespace App\Filament\Resources\Infos;

use App\Filament\Resources\Infos\Pages\CreateInfo;
use App\Filament\Resources\Infos\Pages\EditInfo;
use App\Filament\Resources\Infos\Pages\ListInfos;
use App\Filament\Resources\Infos\Schemas\InfoForm;
use App\Filament\Resources\Infos\Tables\InfosTable;
use App\Models\Info;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InfoResource extends Resource
{
    protected static ?string $model = Info::class;

    protected static ?string $navigationLabel = 'Infos';

    protected static ?string $modelLabel = 'Info';

    protected static ?string $pluralModelLabel = 'Infos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return InfoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InfosTable::configure($table);
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
            'index' => ListInfos::route('/'),
            'create' => CreateInfo::route('/create'),
            'edit' => EditInfo::route('/{record}/edit'),
        ];
    }
}
