<?php

namespace App\Filament\Resources\DeliveryInfos\Schemas;

use App\Models\Township;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class DeliveryInfoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Delivery Fee')
                    ->description('Set delivery fee for a township.')
                    ->schema([
                        Select::make('state_region_id')
                            ->label('State / Region')
                            ->relationship('stateRegion', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (callable $set): mixed => $set('township_id', null)),
                        Select::make('township_id')
                            ->label('Township')
                            ->options(function (Get $get): array {
                                $stateRegionId = $get('state_region_id');
                                if (! $stateRegionId) {
                                    return [];
                                }

                                return Township::query()
                                    ->where('state_region_id', $stateRegionId)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->all();
                            })
                            ->required()
                            ->live()
                            ->searchable()
                            ->preload(),
                        TextInput::make('delivery_fees')
                            ->label('Delivery Fee (MMK)')
                            ->numeric()
                            ->prefix('MMK ')
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
