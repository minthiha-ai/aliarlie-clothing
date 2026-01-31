<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentOrders extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Order::query()->latest()->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('order_code')
                ->label('Order')
                ->searchable(),
            TextColumn::make('customer.name')
                ->label('Customer')
                ->searchable(),
            TextColumn::make('status')
                ->badge(),
            TextColumn::make('total_amount')
                ->formatStateUsing(fn ($state) => $state !== null ? 'MMK '.number_format((float) $state, 2) : ''),
            TextColumn::make('created_at')
                ->label('Placed')
                ->dateTime(),
        ];
    }
}
