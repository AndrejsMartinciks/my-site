<?php

namespace App\Filament\Resources\WindowCleaningBookingResource\Pages;

use App\Filament\Resources\WindowCleaningBookingResource;
use App\Filament\Widgets\WindowCleaningStatsOverview;
use App\Models\Booking;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListWindowCleaningBookings extends ListRecords
{
    protected static string $resource = WindowCleaningBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WindowCleaningStatsOverview::class,
        ];
    }

    public function getTabs(): array
    {
        $baseQuery = Booking::query()->where('booking_type', 'window_cleaning');

        return [
            'all' => Tab::make('Alla')
                ->badge($baseQuery->count()),

            'new' => Tab::make('New')
                ->badge((clone $baseQuery)->where('status', 'new')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new')),

            'contacted' => Tab::make('Contacted')
                ->badge((clone $baseQuery)->where('status', 'contacted')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'contacted')),

            'confirmed' => Tab::make('Confirmed')
                ->badge((clone $baseQuery)->where('status', 'confirmed')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'confirmed')),

            'in_progress' => Tab::make('In progress')
                ->badge((clone $baseQuery)->where('status', 'in_progress')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in_progress')),

            'done' => Tab::make('Done')
                ->badge((clone $baseQuery)->where('status', 'done')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'done')),

            'cancelled' => Tab::make('Cancelled')
                ->badge((clone $baseQuery)->where('status', 'cancelled')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled')),
        ];
    }
}