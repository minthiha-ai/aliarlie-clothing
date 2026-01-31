<?php

namespace App\Filament\Resources\Collections\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Product Assignment')
                    ->schema([
                        TextInput::make('pivot.sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first in the collection.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('images.image_path')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(url('/assets/img/photos/404.svg'))
                    ->getStateUsing(function ($record) {
                        return $record->images->where('is_primary', true)->first()?->image_path
                            ?? $record->images->first()?->image_path;
                    }),
                TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => $state !== null ? 'MMK '.number_format((float) $state, 2) : '')
                    ->sortable(),
                TextColumn::make('pivot.sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->sortable()
                    ->default(0),
                TextColumn::make('is_active')
                    ->label('Active')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => $state === '1' ? 'Active' : 'Inactive')
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first in the collection.'),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->form([
                        TextInput::make('pivot.sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first in the collection.'),
                    ]),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function ($query) {
                return $query->orderBy('collection_product.sort_order', 'asc')
                    ->orderBy('products.id', 'asc');
            });
    }
}
