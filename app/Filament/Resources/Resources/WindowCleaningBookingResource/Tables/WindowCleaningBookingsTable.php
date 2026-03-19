<?php

namespace App\Filament\Resources\WindowCleaningBookingResource\Tables;

use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
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
            ->defaultSort('booking_date', 'desc')
            ->columns([
                TextColumn::make('booking_date')
                    ->label('Datum')
                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('time_from')
                    ->label('Från')
                    ->formatStateUsing(fn ($state) => substr((string) $state, 0, 5))
                    ->sortable(),

                TextColumn::make('time_to')
                    ->label('Till')
                    ->formatStateUsing(fn ($state) => substr((string) $state, 0, 5))
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Kund')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->email),

                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('window_count')
                    ->label('Fönster')
                    ->sortable(),

                TextColumn::make('cleaning_scope')
                    ->label('Typ')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'inside' => 'Invändigt',
                        'outside' => 'Utvändigt',
                        'both' => 'In- och utvändigt',
                        default => (string) $state,
                    }),

                TextColumn::make('quoted_price')
                    ->label('Pris')
                    ->formatStateUsing(fn ($state) => number_format((int) $state, 0, ',', ' ') . ' kr')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        'confirmed' => 'success',
                        'in_progress' => 'primary',
                        'done' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Skapad')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'Ny',
                        'contacted' => 'Kontaktad',
                        'confirmed' => 'Bekräftad',
                        'in_progress' => 'Pågående',
                        'done' => 'Slutförd',
                        'cancelled' => 'Avbokad',
                    ])
                    ->native(false),

                Filter::make('booking_date')
                    ->label('Datum')
                    ->form([
                        DatePicker::make('from')->label('Från'),
                        DatePicker::make('until')->label('Till'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '<=', $date)
                            );
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Öppna'),
            ])
            ->toolbarActions([]);
    }
}