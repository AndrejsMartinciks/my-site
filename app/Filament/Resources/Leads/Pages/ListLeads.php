<?php

namespace App\Filament\Resources\Leads\Pages;

use App\Filament\Resources\Leads\LeadResource;
use App\Models\Lead;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'new' => Tab::make('Nya')
                ->badge(Lead::query()->where('status', 'new')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new')),

            'all' => Tab::make('Alla')
                ->badge(Lead::query()->count()),

            'contacted' => Tab::make('Contacted')
                ->badge(Lead::query()->where('status', 'contacted')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'contacted')),

            'done' => Tab::make('Done')
                ->badge(Lead::query()->where('status', 'done')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'done')),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'new';
    }
}