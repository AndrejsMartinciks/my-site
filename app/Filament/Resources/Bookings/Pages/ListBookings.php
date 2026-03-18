<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'new' => Tab::make('Nya')
                ->badge(Booking::query()->where('status', 'new')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new')),

            'all' => Tab::make('Alla')
                ->badge(Booking::query()->count()),

            'contacted' => Tab::make('Contacted')
                ->badge(Booking::query()->where('status', 'contacted')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'contacted')),

            'done' => Tab::make('Done')
                ->badge(Booking::query()->where('status', 'done')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'done')),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'new';
    }
}