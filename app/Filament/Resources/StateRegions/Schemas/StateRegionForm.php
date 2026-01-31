<?php

namespace App\Filament\Resources\StateRegions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StateRegionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('State / Region')
                    ->description('Myanmar state or region (e.g. Yangon, Mandalay).')
                    ->schema([
                        TextInput::make('code')
                            ->label('Code')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->placeholder('MMR013')
                            ->helperText('Unique code, e.g. MMR013 for Yangon.'),
                        TextInput::make('name')
                            ->label('Name (English)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Yangon'),
                        TextInput::make('name_mmr')
                            ->label('Name (Myanmar)')
                            ->maxLength(255)
                            ->placeholder('ရန်ကုန်'),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
