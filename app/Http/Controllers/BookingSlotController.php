<?php

namespace App\Http\Controllers;

use App\Models\BookingSlot;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingSlotController extends Controller
{
    public function available(Request $request): JsonResponse
    {
        $data = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'service' => ['nullable', 'string', 'max:255'],
        ]);

        $serviceSlug = $data['service'] ?? 'engangsstadning';

        $service = Service::query()
            ->where('slug', $serviceSlug)
            ->first();

        if (!$service) {
            return response()->json([
                'ok' => false,
                'message' => 'Tjänsten hittades inte.',
            ], 404);
        }

        $slots = BookingSlot::query()
            ->where('service_id', $service->id)
            ->whereDate('booking_date', $data['date'])
            ->where('is_active', true)
            ->where('is_booked', false)
            ->orderBy('booking_date')
            ->orderBy('sort_order')
            ->orderBy('time_from')
            ->get([
                'id',
                'booking_date',
                'time_from',
                'time_to',
            ])
            ->map(function (BookingSlot $slot) {
                $timeFrom = substr((string) $slot->time_from, 0, 5);
                $timeTo = substr((string) $slot->time_to, 0, 5);

                return [
                    'id' => $slot->id,
                    'date' => $slot->booking_date?->format('Y-m-d') ?? null,
                    'time_from' => $timeFrom,
                    'time_to' => $timeTo,
                    'label' => $timeFrom . '-' . $timeTo,
                ];
            })
            ->values();

        return response()->json([
            'ok' => true,
            'service' => [
                'id' => $service->id,
                'name' => $service->name,
                'slug' => $service->slug,
            ],
            'date' => $data['date'],
            'slots' => $slots,
        ]);
    }
}