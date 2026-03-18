<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->label('Namn')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('pricing_mode')
                    ->label('Prislogik')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'fixed' => 'Fixed',
                        'frequency' => 'Frequency',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'fixed' => 'info',
                        'frequency' => 'warning',
                        default => 'gray',
                    }),

                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Sortering')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Uppdaterad')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('pricing_mode')
                    ->options([
                        'fixed' => 'Fixed',
                        'frequency' => 'Frequency',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Aktiv'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}