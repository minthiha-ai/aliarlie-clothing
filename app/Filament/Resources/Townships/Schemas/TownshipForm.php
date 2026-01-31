<?php

namespace App\Filament\Resources\Townships\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TownshipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Township')
                    ->description('Township within a state/region.')
                    ->schema([
                        Select::make('state_region_id')
                            ->label('State / Region')
                            ->relationship('stateRegion', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('district_code')
                            ->label('District Code')
                            ->maxLength(50)
                            ->placeholder('MMR013D001'),
                        TextInput::make('code')
                            ->label('Township Code')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('MMR013001')
                            ->helperText('Unique within the state/region.'),
                        TextInput::make('name')
                            ->label('Name (English)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Hlaing'),
                        TextInput::make('name_mmr')
                            ->label('Name (Myanmar)')
                            ->maxLength(255)
                            ->placeholder('လှိုင်'),
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
