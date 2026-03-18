<?php

namespace App\Filament\Resources\WindowCleaningBookingResource\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WindowCleaningBookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('booking_date')
                    ->label('Date')
                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('time_from')
                    ->label('From')
                    ->sortable(),

                TextColumn::make('window_count')
                    ->label('Windows')
                    ->sortable(),

                TextColumn::make('cleaning_scope')
                    ->label('Scope')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'inside' => 'Invändigt',
                        'outside' => 'Utvändigt',
                        'both' => 'In- och utvändigt',
                        default => (string) $state,
                    }),

                TextColumn::make('quoted_price')
                    ->label('Price')
                    ->suffix(' kr')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'primary',
                        'contacted' => 'warning',
                        'confirmed' => 'success',
                        'done' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'confirmed' => 'Confirmed',
                        'done' => 'Done',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([])
            ->bulkActions([]);
    }
}