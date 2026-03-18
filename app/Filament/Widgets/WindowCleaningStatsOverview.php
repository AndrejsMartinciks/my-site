<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WindowCleaningStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayCount = Booking::query()
            ->where('booking_type', 'window_cleaning')
            ->whereDate('created_at', today())
            ->count();

        $confirmedThisWeek = Booking::query()
            ->where('booking_type', 'window_cleaning')
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->whereBetween('booking_date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])
            ->count();

        $confirmedRevenue = (int) Booking::query()
            ->where('booking_type', 'window_cleaning')
            ->whereIn('status', ['confirmed', 'in_progress', 'done'])
            ->sum('quoted_price');

        return [
            Stat::make('Nya idag', (string) $todayCount)
                ->description('Nya fönsterputs-bokningar idag'),

            Stat::make('Bekräftade denna vecka', (string) $confirmedThisWeek)
                ->description('Confirmed + in progress'),

            Stat::make('Omsättning bekräftade', number_format($confirmedRevenue, 0, ',', ' ') . ' kr')
                ->description('Confirmed + in progress + done'),
        ];
    }
}