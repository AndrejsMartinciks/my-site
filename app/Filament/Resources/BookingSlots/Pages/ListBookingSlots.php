<?php

namespace App\Filament\Resources\BookingSlots\Pages;

use App\Filament\Resources\BookingSlots\BookingSlotResource;
use App\Models\BookingSlot;
use App\Models\Service;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListBookingSlots extends ListRecords
{
    protected static string $resource = BookingSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateDaySlots')
                ->label('Generate day slots')
                ->icon('heroicon-o-calendar-days')
                ->color('warning')
                ->form([
                    Select::make('service_id')
                        ->label('Tjänst')
                        ->options(fn () => Service::query()->orderBy('sort_order')->pluck('name', 'id')->all())
                        ->default(fn () => Service::query()->where('slug', 'engangsstadning')->value('id'))
                        ->searchable()
                        ->required(),

                    DatePicker::make('booking_date')
                        ->label('Datum')
                        ->required(),

                    TimePicker::make('start_time')
                        ->label('Från')
                        ->seconds(false)
                        ->default('08:00')
                        ->required(),

                    TimePicker::make('end_time')
                        ->label('Till')
                        ->seconds(false)
                        ->default('15:00')
                        ->required(),

                    TextInput::make('slot_minutes')
                        ->label('Slot length (minutes)')
                        ->numeric()
                        ->default(60)
                        ->minValue(15)
                        ->step(15)
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Aktiv')
                        ->default(true),
                ])
                ->action(function (array $data): void {
                    $serviceId = (int) $data['service_id'];
                    $bookingDate = $data['booking_date'];
                    $slotMinutes = (int) $data['slot_minutes'];
                    $isActive = (bool) ($data['is_active'] ?? true);

                    $start = CarbonImmutable::parse($bookingDate . ' ' . $data['start_time']);
                    $end = CarbonImmutable::parse($bookingDate . ' ' . $data['end_time']);

                    if ($end->lessThanOrEqualTo($start)) {
                        Notification::make()
                            ->title('Fel tidsintervall')
                            ->body('Sluttiden måste vara senare än starttiden.')
                            ->danger()
                            ->send();

                        return;
                    }

                    if ($slotMinutes < 15) {
                        Notification::make()
                            ->title('Fel slot length')
                            ->body('Minsta slotlängd är 15 minuter.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $created = 0;
                    $skipped = 0;
                    $sortOrder = 1;

                    for ($cursor = $start; $cursor->lt($end); $cursor = $cursor->addMinutes($slotMinutes)) {
                        $slotEnd = $cursor->addMinutes($slotMinutes);

                        if ($slotEnd->gt($end)) {
                            break;
                        }

                        $slot = BookingSlot::firstOrCreate(
                            [
                                'service_id' => $serviceId,
                                'booking_date' => $bookingDate,
                                'time_from' => $cursor->format('H:i:s'),
                                'time_to' => $slotEnd->format('H:i:s'),
                            ],
                            [
                                'is_active' => $isActive,
                                'is_booked' => false,
                                'sort_order' => $sortOrder,
                            ]
                        );

                        if ($slot->wasRecentlyCreated) {
                            $created++;
                        } else {
                            $skipped++;
                        }

                        $sortOrder++;
                    }

                    Notification::make()
                        ->title('Slots generated')
                        ->body("Skapade: {$created}. Hoppade över dubletter: {$skipped}.")
                        ->success()
                        ->send();
                }),

            CreateAction::make(),
        ];
    }
}