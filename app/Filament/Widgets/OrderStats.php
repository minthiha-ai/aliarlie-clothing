<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalOrders = Order::query()->count();
        $pendingOrders = Order::query()->where('status', 'pending')->count();
        $totalRevenue = (float) Order::query()->sum('total_amount');

        return [
            Stat::make('Total Orders', number_format($totalOrders))
                ->description('All time'),
            Stat::make('Pending Orders', number_format($pendingOrders))
                ->description('Need action'),
            Stat::make('Revenue', 'MMK '.number_format($totalRevenue, 2))
                ->description('All time'),
        ];
    }
}
