<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('booking_date', 'desc')
            ->recordClasses(fn (Booking $record) => match ($record->status) {
                'new' => 'bg-red-50 dark:bg-red-950/20',
                default => null,
            })
            ->columns([
                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Datum')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time_from')
                    ->label('Från')
                    ->formatStateUsing(fn ($state) => $state ? substr((string) $state, 0, 5) : '—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time_to')
                    ->label('Till')
                    ->formatStateUsing(fn ($state) => $state ? substr((string) $state, 0, 5) : '—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'danger',
                        'contacted' => 'warning',
                        'done' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('contacted_at')
                    ->label('Contacted')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('done_at')
                    ->label('Done')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Kund')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('personnummer_last4')
                    ->label('PNR')
                    ->formatStateUsing(fn (?string $state) => $state ? '******-' . $state : '—'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-post')
                    ->searchable(),

                Tables\Columns\TextColumn::make('service.name')
                    ->label('Tjänst')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('address')
                    ->label('Adress')
                    ->limit(40),

                Tables\Columns\TextColumn::make('manager_note')
                    ->label('Manager note')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('calculator_summary')
                    ->label('Kalkylator')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'done' => 'Done',
                    ]),

                Tables\Filters\SelectFilter::make('service_id')
                    ->label('Tjänst')
                    ->relationship('service', 'name'),
            ])
            ->headerActions([
                Action::make('exportCsv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        $fileName = 'bookings-' . now()->format('Y-m-d-H-i-s') . '.csv';

                        return response()->streamDownload(function () {
                            $handle = fopen('php://output', 'w');

                            fwrite($handle, "\xEF\xBB\xBF");

                            fputcsv($handle, [
                                'DB ID',
                                'Booking Date',
                                'Time From',
                                'Time To',
                                'Status',
                                'Contacted At',
                                'Done At',
                                'Customer Name',
                                'Personnummer Masked',
                                'Phone',
                                'Email',
                                'Service',
                                'Address',
                                'Manager Note',
                                'Calculator Summary',
                                'Created At',
                            ], ';');

                            Booking::query()
                                ->with('service')
                                ->orderByDesc('booking_date')
                                ->orderByDesc('time_from')
                                ->chunk(200, function ($bookings) use ($handle) {
                                    foreach ($bookings as $booking) {
                                        fputcsv($handle, [
                                            $booking->id,
                                            optional($booking->booking_date)?->format('Y-m-d'),
                                            $booking->time_from ? substr((string) $booking->time_from, 0, 5) : '',
                                            $booking->time_to ? substr((string) $booking->time_to, 0, 5) : '',
                                            $booking->status,
                                            optional($booking->contacted_at)?->format('Y-m-d H:i:s'),
                                            optional($booking->done_at)?->format('Y-m-d H:i:s'),
                                            $booking->customer_name,
                                            $booking->personnummer_last4 ? '******-' . $booking->personnummer_last4 : '',
                                            $booking->phone,
                                            $booking->email,
                                            $booking->service?->name ?? '',
                                            str_replace(["\r\n", "\r", "\n"], ' | ', $booking->address ?? ''),
                                            str_replace(["\r\n", "\r", "\n"], ' | ', $booking->manager_note ?? ''),
                                            str_replace(["\r\n", "\r", "\n"], ' | ', $booking->calculator_summary ?? ''),
                                            optional($booking->created_at)?->format('Y-m-d H:i:s'),
                                        ], ';');
                                    }
                                });

                            fclose($handle);
                        }, $fileName);
                    }),
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('markContacted')
                    ->label('Mark contacted')
                    ->icon('heroicon-o-phone')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record): bool => in_array($record->status, ['new'], true))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => 'contacted',
                            'contacted_at' => $record->contacted_at ?? now(),
                        ]);

                        Notification::make()
                            ->title('Booking marked as contacted')
                            ->success()
                            ->send();
                    }),

                Action::make('markDone')
                    ->label('Mark done')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record): bool => in_array($record->status, ['new', 'contacted'], true))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => 'done',
                            'contacted_at' => $record->contacted_at ?? now(),
                            'done_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Booking marked as done')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('bulkMarkContacted')
                        ->label('Mark selected as contacted')
                        ->icon('heroicon-o-phone')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'status' => 'contacted',
                                    'contacted_at' => $record->contacted_at ?? now(),
                                ]);
                            }

                            Notification::make()
                                ->title('Selected bookings marked as contacted')
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('bulkMarkDone')
                        ->label('Mark selected as done')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'status' => 'done',
                                    'contacted_at' => $record->contacted_at ?? now(),
                                    'done_at' => $record->done_at ?? now(),
                                ]);
                            }

                            Notification::make()
                                ->title('Selected bookings marked as done')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }
}