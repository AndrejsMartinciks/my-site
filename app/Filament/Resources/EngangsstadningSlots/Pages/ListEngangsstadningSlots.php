<?php

namespace App\Filament\Resources\EngangsstadningSlots\Pages;

use App\Filament\Resources\EngangsstadningSlots\EngangsstadningSlotResource;
use App\Models\Service;
use App\Services\BookingSlotGenerator;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListEngangsstadningSlots extends ListRecords
{
    protected static string $resource = EngangsstadningSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('generateSlots')
                ->label('Generate Slots')
                ->icon('heroicon-o-plus-circle')
                ->form([
                    DatePicker::make('from_date')
                        ->label('From date')
                        ->required(),

                    DatePicker::make('to_date')
                        ->label('To date')
                        ->required(),

                    TimePicker::make('workday_start')
                        ->label('Start time')
                        ->seconds(false)
                        ->default('08:00')
                        ->required(),

                    TimePicker::make('workday_end')
                        ->label('End time')
                        ->seconds(false)
                        ->default('17:00')
                        ->required(),

                    TextInput::make('slot_duration_minutes')
                        ->label('Slot duration (minutes)')
                        ->numeric()
                        ->default(60)
                        ->required(),

                    TextInput::make('break_minutes')
                        ->label('Break between slots (minutes)')
                        ->numeric()
                        ->default(0)
                        ->required(),

                    CheckboxList::make('weekdays')
                        ->label('Weekdays')
                        ->options([
                            1 => 'Monday',
                            2 => 'Tuesday',
                            3 => 'Wednesday',
                            4 => 'Thursday',
                            5 => 'Friday',
                            6 => 'Saturday',
                            7 => 'Sunday',
                        ])
                        ->default([1, 2, 3, 4, 5])
                        ->columns(2)
                        ->required(),

                    Toggle::make('overwrite_existing')
                        ->label('Overwrite existing slots for selected dates')
                        ->default(false),

                    Toggle::make('mark_as_active')
                        ->label('Mark slots as active')
                        ->default(true),

                    TextInput::make('sort_order')
                        ->label('Sort order')
                        ->numeric()
                        ->default(1)
                        ->required(),

                    Textarea::make('internal_note')
                        ->label('Internal note')
                        ->rows(2),
                ])
                ->action(function (array $data): void {
                    $serviceId = Service::query()
                        ->where('slug', 'engangsstadning')
                        ->value('id');

                    if (! $serviceId) {
                        Notification::make()
                            ->title('Service engangsstadning not found')
                            ->danger()
                            ->send();

                        return;
                    }

                    $created = app(BookingSlotGenerator::class)->generate(
                        serviceId: $serviceId,
                        fromDate: $data['from_date'],
                        toDate: $data['to_date'],
                        workdayStart: $data['workday_start'],
                        workdayEnd: $data['workday_end'],
                        slotDurationMinutes: (int) $data['slot_duration_minutes'],
                        breakMinutes: (int) $data['break_minutes'],
                        weekdays: array_map('intval', $data['weekdays']),
                        overwriteExisting: (bool) $data['overwrite_existing'],
                        markAsActive: (bool) $data['mark_as_active'],
                        sortOrder: (int) $data['sort_order'],
                        internalNote: $data['internal_note'] ?? null,
                    );

                    Notification::make()
                        ->title("Generated {$created} Engångsstädning slots")
                        ->success()
                        ->send();
                }),
        ];
    }
}