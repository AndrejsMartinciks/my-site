<?php

namespace App\Services;

use App\Models\BookingSlot;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class BookingSlotGenerator
{
    public function generate(
        int $serviceId,
        string $fromDate,
        string $toDate,
        string $workdayStart,
        string $workdayEnd,
        int $slotDurationMinutes,
        int $breakMinutes = 0,
        array $weekdays = [1, 2, 3, 4, 5],
        bool $overwriteExisting = false,
        bool $markAsActive = true,
        int $sortOrder = 1,
        ?string $internalNote = null,
    ): int {
        $created = 0;

        $period = CarbonPeriod::create($fromDate, $toDate);

        foreach ($period as $date) {
            if (! in_array((int) $date->dayOfWeekIso, $weekdays, true)) {
                continue;
            }

            if ($overwriteExisting) {
                BookingSlot::query()
                    ->where('service_id', $serviceId)
                    ->whereDate('booking_date', $date->toDateString())
                    ->delete();
            }

            $current = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $workdayStart);
            $end = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $workdayEnd);

            while ($current->copy()->addMinutes($slotDurationMinutes) <= $end) {
                $slotStart = $current->format('H:i:s');
                $slotEnd = $current->copy()->addMinutes($slotDurationMinutes)->format('H:i:s');

                $exists = BookingSlot::query()
                    ->where('service_id', $serviceId)
                    ->whereDate('booking_date', $date->toDateString())
                    ->where('time_from', $slotStart)
                    ->where('time_to', $slotEnd)
                    ->exists();

                if (! $exists) {
                    BookingSlot::create([
                        'service_id' => $serviceId,
                        'booking_date' => $date->toDateString(),
                        'time_from' => $slotStart,
                        'time_to' => $slotEnd,
                        'is_active' => $markAsActive,
                        'is_booked' => false,
                        'sort_order' => $sortOrder,
                        'internal_note' => $internalNote,
                    ]);

                    $created++;
                }

                $current->addMinutes($slotDurationMinutes + $breakMinutes);
            }
        }

        return $created;
    }
}