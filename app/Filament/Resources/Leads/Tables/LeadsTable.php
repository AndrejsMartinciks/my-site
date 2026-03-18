<?php

namespace App\Filament\Resources\Leads\Tables;

use App\Models\Lead;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Skickad')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'danger',
                        'contacted' => 'warning',
                        'done' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('source')
    ->label('Source')
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'calculator' => 'info',
        'form' => 'gray',
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

                Tables\Columns\TextColumn::make('name')
                    ->label('Namn')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-post')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('service')
                    ->label('Tjänst')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('message')
                    ->label('Meddelande')
                    ->limit(40),

                Tables\Columns\TextColumn::make('calculator_summary')
                    ->label('Kalkylator')
                    ->limit(40),

                Tables\Columns\TextColumn::make('manager_note')
                    ->label('Manager note')
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

    Tables\Filters\SelectFilter::make('source')
        ->label('Source')
        ->options([
            'form' => 'Form',
            'calculator' => 'Calculator',
        ]),
])
            ->headerActions([
                Action::make('exportCsv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        $fileName = 'leads-' . now()->format('Y-m-d-H-i-s') . '.csv';

                        return response()->streamDownload(function () {
                            $handle = fopen('php://output', 'w');

                            fwrite($handle, "\xEF\xBB\xBF");

                            fputcsv($handle, [
    'DB ID',
    'UUID',
    'Created At',
    'Status',
    'Source',
    'Contacted At',
    'Done At',
    'Name',
    'Email',
    'Phone',
    'Service',
    'Message',
    'Calculator Summary',
    'Manager Note',
], ';');

                            Lead::query()
                                ->orderByDesc('created_at')
                                ->chunk(200, function ($leads) use ($handle) {
                                    foreach ($leads as $lead) {
                                        fputcsv($handle, [
                                            $lead->id,
$lead->uuid,
optional($lead->created_at)?->format('Y-m-d H:i:s'),
$lead->status,
$lead->source,
optional($lead->contacted_at)?->format('Y-m-d H:i:s'),
optional($lead->done_at)?->format('Y-m-d H:i:s'),
                                            $lead->name,
                                            $lead->email,
                                            $lead->phone,
                                            $lead->service,
                                            str_replace(["\r\n", "\r", "\n"], ' | ', $lead->message ?? ''),
                                            str_replace(["\r\n", "\r", "\n"], ' | ', $lead->calculator_summary ?? ''),
                                            str_replace(["\r\n", "\r", "\n"], ' | ', $lead->manager_note ?? ''),
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
                            ->title('Lead marked as contacted')
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
                            ->title('Lead marked as done')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
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
                            ->title('Selected leads marked as contacted')
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
                            ->title('Selected leads marked as done')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}