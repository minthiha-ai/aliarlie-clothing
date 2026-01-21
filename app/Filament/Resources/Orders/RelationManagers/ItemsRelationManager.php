<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Models\ProductVariant;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Fieldset::make('Order Item')
                ->schema([
                    Select::make('product_variant_id')
                        ->label('Product Variant')
                        ->relationship('productVariant', 'sku')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->getOptionLabelFromRecordUsing(
                            fn(ProductVariant $record): string =>
                            "{$record->product->name} — {$record->sku} ({$record->size} / {$record->color})"
                        )
                        ->reactive()
                        ->afterStateUpdated(function ($state, Set $set) {
                            $variant = ProductVariant::with('stock')->find($state);

                            if ($variant) {
                                $set('price', $variant->discount_price ?? $variant->price);
                            }
                        }),

                    TextInput::make('price')
                        ->label('Unit Price')
                        ->numeric()
                        ->prefix('$')
                        ->required()
                        ->disabled()
                        ->dehydrated(),

                    TextInput::make('quantity')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->rule(function (Get $get) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                $variant = ProductVariant::with('stock')->find($get('product_variant_id'));

                                if ($variant && $variant->stock && $value > $variant->stock->quantity) {
                                    $fail('Quantity exceeds available stock.');
                                }
                            };
                        }),
                ])
                ->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('productVariant.sku')
                    ->label('SKU')
                    ->searchable(),

                TextColumn::make('productVariant.size')
                    ->label('Size'),

                TextColumn::make('productVariant.color')
                    ->label('Color'),

                TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('quantity')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()->modal()
                    ->after(function () {
                        $this->updateOrderTotal();
                    }),
            ])
            ->recordActions([
                EditAction::make()->modal()
                    ->after(function () {
                        $this->updateOrderTotal();
                    }),
                DeleteAction::make()
                    ->after(function () {
                        $this->updateOrderTotal();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(function () {
                            $this->updateOrderTotal();
                        }),
                ]),
            ]);
    }

    protected function updateOrderTotal(): void
    {
        $order = $this->getOwnerRecord();

        $order->recalculateTotalAmount();

        $this->dispatch('order-items-updated');
    }
}
