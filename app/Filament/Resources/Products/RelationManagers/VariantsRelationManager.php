<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset as ComponentsFieldset;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsFieldset::make('Variant Info')
                    ->schema([
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),

                        TextInput::make('size')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('color')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0),

                        TextInput::make('discount_price')
                            ->numeric()
                            ->prefix('$')
                            ->lt('price')
                            ->minValue(0)
                            ->helperText('Must be less than price'),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),

                ComponentsFieldset::make('Stock')
                    ->relationship('stock')
                    ->schema([
                        TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->default(0)
                            ->label('Available Quantity'),

                        TextInput::make('reserved')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->default(0)
                            ->label('Reserved'),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('size')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('color')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->money()
                    ->sortable(),

                TextColumn::make('discount_price')
                    ->money()
                    ->sortable(),

                TextColumn::make('stock.quantity')
                    ->label('Stock')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()->modal(),
            ])
            ->recordActions([
                EditAction::make()->modal(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
