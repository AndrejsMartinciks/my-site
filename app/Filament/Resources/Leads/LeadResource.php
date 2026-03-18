<?php

namespace App\Filament\Resources\Leads;

use App\Filament\Resources\Leads\Pages\ListLeads;
use App\Filament\Resources\Leads\Pages\ViewLead;
use App\Filament\Resources\Leads\Tables\LeadsTable;
use App\Models\Lead;
use BackedEnum;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Leads';

    protected static ?string $pluralModelLabel = 'Leads';

    protected static ?string $modelLabel = 'Lead';

    protected static ?int $navigationSort = 50;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()
            ->where('status', 'new')
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Nya leads';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return LeadsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Infolists\Components\TextEntry::make('id')
                    ->label('DB ID'),

                Infolists\Components\TextEntry::make('uuid')
                    ->label('UUID'),

                Infolists\Components\TextEntry::make('created_at')
    ->label('Skickad')
    ->dateTime('Y-m-d H:i:s'),

Infolists\Components\TextEntry::make('status')
    ->label('Status')
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'new' => 'danger',
        'contacted' => 'warning',
        'done' => 'success',
        default => 'gray',
    }),

Infolists\Components\TextEntry::make('source')
    ->label('Source')
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'calculator' => 'info',
        'form' => 'gray',
        default => 'gray',
    }),

Infolists\Components\TextEntry::make('contacted_at')
    ->label('Contacted at')
    ->dateTime('Y-m-d H:i:s')
    ->placeholder('—'),

Infolists\Components\TextEntry::make('done_at')
    ->label('Done at')
    ->dateTime('Y-m-d H:i:s')
    ->placeholder('—'),

Infolists\Components\TextEntry::make('service')
    ->label('Tjänst'),

                Infolists\Components\TextEntry::make('name')
                    ->label('Namn'),

                Infolists\Components\TextEntry::make('email')
                    ->label('E-post'),

                Infolists\Components\TextEntry::make('phone')
                    ->label('Telefon'),

                Infolists\Components\TextEntry::make('message')
                    ->label('Meddelande')
                    ->columnSpanFull()
                    ->html()
                    ->formatStateUsing(fn (?string $state): string => nl2br(e($state ?: '—'))),

                Infolists\Components\TextEntry::make('calculator_summary')
    ->label('Prisberäkning från kalkylatorn')
    ->columnSpanFull()
    ->html()
    ->formatStateUsing(fn (?string $state): string => nl2br(e($state ?: '—'))),

Infolists\Components\TextEntry::make('manager_note')
    ->label('Manager note')
    ->columnSpanFull()
    ->html()
    ->formatStateUsing(fn (?string $state): string => nl2br(e($state ?: '—'))),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeads::route('/'),
            'view' => ViewLead::route('/{record}'),
        ];
    }
}