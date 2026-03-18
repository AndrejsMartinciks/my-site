<?php

namespace App\Filament\Resources\Leads\Pages;

use App\Filament\Resources\Leads\LeadResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewLead extends ViewRecord
{
    protected static string $resource = LeadResource::class;

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

            Action::make('back')
                ->label('Tillbaka till listan')
                ->url(LeadResource::getUrl('index')),
        ];
    }
}