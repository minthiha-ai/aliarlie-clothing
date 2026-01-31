<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('order_code')
                    ->searchable(),
                TextColumn::make('stateRegion.name')
                    ->label('State / Region')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('township.name')
                    ->label('Township')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('delivery_fees')
                    ->label('Delivery')
                    ->formatStateUsing(fn ($state) => $state !== null ? 'MMK '.number_format((float) $state, 2) : 'MMK 0.00')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => $state !== null ? 'MMK '.number_format((float) $state, 2) : '')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->badge()
                    ->sortable(),
                TextColumn::make('payment.name')
                    ->label('Payment')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('payment_proof_photo')
                    ->label('Proof')
                    ->disk('public')
                    ->square()
                    ->size(48)
                    ->url(fn (?string $state): ?string => $state ? Storage::disk('public')->url($state) : null)
                    ->openUrlInNewTab()
                    ->extraImgAttributes(['loading' => 'lazy']),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'shipped' => 'Shipped',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('payment_method')
                    ->options([
                        'cod' => 'COD',
                        'online_payment' => 'Online payment',
                    ]),
            ])
            ->recordActions([
                EditAction::make()->modal(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
