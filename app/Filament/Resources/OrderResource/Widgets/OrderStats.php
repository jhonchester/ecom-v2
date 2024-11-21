<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
            Stat::make('Processing Orders', Order::query()->where('status', 'processing')->count()),
            Stat::make('Claimed Orders', Order::query()->where('status', 'claimed')->count()),
            Stat::make(
                'Average Price',
                'PHP ' . number_format(Order::query()->avg('grand_total') ?? 0, 2)
            ),
        ];
    }
}
