<?php

namespace App\Filament\Resources\DeliveryInfos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DeliveryInfosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('stateRegion.name')
                    ->label('State / Region')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('township.name')
                    ->label('Township')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('delivery_fees')
                    ->label('Delivery Fee')
                    ->formatStateUsing(fn ($state) => $state !== null ? 'MMK '.number_format((float) $state, 2) : '')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('state_region_id')
                    ->relationship('stateRegion', 'name')
                    ->label('State / Region')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()->modal(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('state_region_id');
    }
}
