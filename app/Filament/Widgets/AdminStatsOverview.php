<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = '15s';

    protected ?string $heading = 'Översikt idag';

    protected ?string $description = 'Snabb översikt över nya leads och bokningar.';

    protected function getStats(): array
    {
        $today = Carbon::today();

        $newLeads = Lead::query()
            ->where('status', 'new')
            ->count();

        $leadsToday = Lead::query()
            ->whereDate('created_at', $today)
            ->count();

        $newBookings = Booking::query()
            ->where('status', 'new')
            ->count();

        $bookingsToday = Booking::query()
            ->whereDate('created_at', $today)
            ->count();

        return [
            Stat::make('Nya leads', (string) $newLeads)
                ->description('Obearbetade leads')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($newLeads > 0 ? 'danger' : 'success'),

            Stat::make('Leads idag', (string) $leadsToday)
                ->description('Skapade idag')
                ->descriptionIcon('heroicon-m-document-text')
                ->color($leadsToday > 0 ? 'warning' : 'gray'),

            Stat::make('Nya bokningar', (string) $newBookings)
                ->description('Obearbetade bokningar')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($newBookings > 0 ? 'danger' : 'success'),

            Stat::make('Bokningar idag', (string) $bookingsToday)
                ->description('Skapade idag')
                ->descriptionIcon('heroicon-m-clock')
                ->color($bookingsToday > 0 ? 'warning' : 'gray'),
        ];
    }
}