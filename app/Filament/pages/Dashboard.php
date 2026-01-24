<?php

namespace App\Filament\pages;

use App\Filament\Widgets\OrderStats;
use App\Filament\Widgets\RecentOrders;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = 0;

    public function getWidgets(): array
    {
        return [
            OrderStats::class,
            RecentOrders::class,
        ];
    }
}
