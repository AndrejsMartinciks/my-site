<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\WindowCleaningStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getHeaderWidgets(): array
    {
        return [
            WindowCleaningStatsOverview::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 3;
    }
}