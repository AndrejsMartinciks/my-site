<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('editManagerNote')
                ->label('Redigera anteckning')
                ->icon('heroicon-o-pencil-square')
                ->fillForm(fn (): array => [
                    'manager_note' => $this->record->manager_note,
                ])
                ->form([
                    \Filament\Forms\Components\Textarea::make('manager_note')
                        ->label('Manager note')
                        ->rows(6)
                        ->maxLength(5000),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'manager_note' => $data['manager_note'] ?? null,
                    ]);

                    $this->record->refresh();

                    Notification::make()
                        ->title('Anteckning sparad')
                        ->success()
                        ->send();
                }),

            Action::make('markContacted')
                ->label('Mark contacted')
                ->icon('heroicon-o-phone')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn (): bool => in_array($this->record->status, ['new'], true))
                ->action(function (): void {
                    $this->record->update([
                        'status' => 'contacted',
                        'contacted_at' => $this->record->contacted_at ?? now(),
                    ]);

                    $this->record->refresh();

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
                ->visible(fn (): bool => in_array($this->record->status, ['new', 'contacted'], true))
                ->action(function (): void {
                    $this->record->update([
                        'status' => 'done',
                        'contacted_at' => $this->record->contacted_at ?? now(),
                        'done_at' => now(),
                    ]);

                    $this->record->refresh();

                    Notification::make()
                        ->title('Booking marked as done')
                        ->success()
                        ->send();
                }),

            Action::make('back')
                ->label('Tillbaka till listan')
                ->url(BookingResource::getUrl('index')),
        ];
    }
}