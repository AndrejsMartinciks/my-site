<?php

namespace App\Filament\Resources\WindowCleaningBookingResource\Tables;

use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

                TextColumn::make('address')
                    ->label('Address')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('postcode')
                    ->label('Postcode')
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
                        'in_progress' => 'info',
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
                        'in_progress' => 'In progress',
                        'done' => 'Done',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('cleaning_scope')
                    ->label('Scope')
                    ->options([
                        'inside' => 'Invändigt',
                        'outside' => 'Utvändigt',
                        'both' => 'In- och utvändigt',
                    ]),

                Filter::make('booking_date_range')
                    ->schema([
                        DatePicker::make('date_from')->label('Date from'),
                        DatePicker::make('date_until')->label('Date until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['date_from'] ?? null, fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '>=', $date))
                            ->when($data['date_until'] ?? null, fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '<=', $date));
                    }),

                Filter::make('price_range')
                    ->schema([
                        TextInput::make('price_min')->label('Price min')->numeric(),
                        TextInput::make('price_max')->label('Price max')->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['price_min'] ?? null, fn (Builder $query, $price): Builder => $query->where('quoted_price', '>=', (int) $price))
                            ->when($data['price_max'] ?? null, fn (Builder $query, $price): Builder => $query->where('quoted_price', '<=', (int) $price));
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([])
            ->bulkActions([]);
    }
}