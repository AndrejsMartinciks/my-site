<?php

namespace App\Http\Controllers;

use App\Models\BookingSlot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingSlotController extends Controller
{
    public function available(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $slots = BookingSlot::query()
            ->with('service:id,slug')
            ->whereDate('booking_date', $validated['date'])
            ->where('is_active', true)
            ->where('is_booked', false)
            ->whereHas('service', function ($query) use ($validated) {
                $query->where('slug', $validated['service']);
            })
            ->orderBy('time_from')
            ->get()
            ->map(function (BookingSlot $slot) {
                $timeFrom = substr((string) $slot->time_from, 0, 5);
                $timeTo = substr((string) $slot->time_to, 0, 5);

                return [
                    'id' => $slot->id,
                    'date' => optional($slot->booking_date)->format('Y-m-d'),
                    'time_from' => $timeFrom,
                    'time_to' => $timeTo,
                    'label' => $timeFrom . ' - ' . $timeTo,
                ];
            })
            ->values();

        return response()->json([
            'ok' => true,
            'slots' => $slots,
        ]);
    }
}