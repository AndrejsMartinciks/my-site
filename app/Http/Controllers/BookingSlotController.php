<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingSlotController extends Controller
{
    public function available(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'duration' => ['nullable', 'integer', 'min:30', 'max:480'],
        ]);

        $duration = (int) ($validated['duration'] ?? 120);
        $date = $validated['date'];

        $slotStarts = [
            '08:00', '09:00', '10:00', '11:00', '12:00',
            '13:00', '14:00', '15:00', '16:00', '17:00',
        ];

        $bookedRanges = Booking::query()
            ->whereDate('booking_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->get(['time_from', 'time_to'])
            ->map(function ($booking) {
                return [
                    'from' => Carbon::createFromFormat('H:i:s', $booking->time_from),
                    'to' => Carbon::createFromFormat('H:i:s', $booking->time_to),
                ];
            });

        $available = collect($slotStarts)
            ->map(function (string $start) use ($duration, $bookedRanges) {
                $from = Carbon::createFromFormat('H:i', $start);
                $to = (clone $from)->addMinutes($duration);

                $isBusy = $bookedRanges->contains(function (array $range) use ($from, $to) {
                    return $from->lt($range['to']) && $to->gt($range['from']);
                });

                return [
                    'time' => $from->format('H:i'),
                    'end_time' => $to->format('H:i'),
                    'available' => ! $isBusy,
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'date' => $date,
            'duration' => $duration,
            'slots' => $available,
        ]);
    }
}