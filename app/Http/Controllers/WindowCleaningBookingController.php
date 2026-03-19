<?php

namespace App\Http\Controllers;

use App\Mail\WindowCleaningAdminNotification;
use App\Mail\WindowCleaningCustomerConfirmation;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Service;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class WindowCleaningBookingController extends Controller
{
    private const SCOPE_MULTIPLIERS = [
        'inside' => 1.0,
        'outside' => 1.0,
        'both' => 1.8,
    ];

    public function store(Request $request)
    {
        $validated = $request->validate([
            'window_count' => ['required', 'integer', 'min:1', 'max:100'],
            'cleaning_scope' => ['required', 'in:inside,outside,both'],
            'addon_ids' => ['nullable', 'array'],
            'addon_ids.*' => ['integer'],
            'booking_slot_id' => ['required', 'integer', 'exists:booking_slots,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:20'],
            'personnummer' => ['required', 'regex:/^\d{6,8}-?\d{4}$/'],
            'message' => ['nullable', 'string', 'max:2000'],
        ], [
            'personnummer.regex' => 'Ange ett giltigt personnummer i format YYYYMMDDXXXX eller YYMMDDXXXX.',
        ]);

        $service = Service::query()
            ->where('slug', 'fonsterputsning')
            ->where('is_active', true)
            ->with([
                'priceRanges' => fn ($query) => $query->orderBy('sort_order'),
                'addons' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order'),
            ])
            ->firstOrFail();

        $selectedAddonIds = collect($validated['addon_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $addons = $service->addons
            ->whereIn('id', $selectedAddonIds)
            ->values();

        $basePrice = $this->resolveBasePrice($service, (int) $validated['window_count']);
        $scopeMultiplier = self::SCOPE_MULTIPLIERS[$validated['cleaning_scope']] ?? 1.0;
        $scopeAdjustedBasePrice = (int) round($basePrice * $scopeMultiplier);
        $addonsTotal = (int) $addons->sum('price');
        $quotedPrice = $scopeAdjustedBasePrice + $addonsTotal;

        $bookingDate = Carbon::parse($validated['booking_date'])->format('Y-m-d');

        $personnummerDigits = preg_replace('/\D+/', '', $validated['personnummer']);
        $personnummerLast4 = substr($personnummerDigits, -4);

        $scopeLabels = [
            'inside' => 'Invändigt',
            'outside' => 'Utvändigt',
            'both' => 'In- och utvändigt',
        ];

        $booking = DB::transaction(function () use (
            $validated,
            $service,
            $bookingDate,
            $addons,
            $basePrice,
            $scopeMultiplier,
            $scopeAdjustedBasePrice,
            $addonsTotal,
            $quotedPrice,
            $personnummerLast4,
            $scopeLabels
        ) {
            $slot = BookingSlot::query()
                ->whereKey($validated['booking_slot_id'])
                ->whereDate('booking_date', $bookingDate)
                ->where('service_id', $service->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (!$slot || $slot->is_booked) {
                throw ValidationException::withMessages([
                    'booking_slot_id' => 'Vald tid är inte längre tillgänglig. Välj en annan tid.',
                ]);
            }

            $timeFrom = Carbon::parse($slot->time_from);
            $timeTo = Carbon::parse($slot->time_to);

            $conflictExists = Booking::query()
                ->whereDate('booking_date', $bookingDate)
                ->whereNotIn('status', ['cancelled'])
                ->where('time_from', '<', $timeTo->format('H:i:s'))
                ->where('time_to', '>', $timeFrom->format('H:i:s'))
                ->lockForUpdate()
                ->exists();

            if ($conflictExists) {
                throw ValidationException::withMessages([
                    'booking_slot_id' => 'Vald tid är inte längre tillgänglig. Välj en annan tid.',
                ]);
            }

            $booking = Booking::create([
                'service_id' => $service->id,
                'booking_slot_id' => $slot->id,
                'booking_type' => 'window_cleaning',
                'booking_date' => $bookingDate,
                'time_from' => $timeFrom->format('H:i:s'),
                'time_to' => $timeTo->format('H:i:s'),
                'customer_name' => $validated['customer_name'],
                'personnummer' => $validated['personnummer'],
                'personnummer_last4' => $personnummerLast4,
                'address' => trim($validated['address']),
                'postcode' => $validated['postcode'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'sqm' => (int) $validated['window_count'],
                'window_count' => (int) $validated['window_count'],
                'cleaning_scope' => $validated['cleaning_scope'],
                'quoted_price' => $quotedPrice,
                'addons' => $addons->map(fn ($addon) => [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => (int) $addon->price,
                ])->values()->all(),
                'calculator_summary' => json_encode([
                    'service' => $service->name,
                    'window_count' => (int) $validated['window_count'],
                    'cleaning_scope' => $scopeLabels[$validated['cleaning_scope']] ?? $validated['cleaning_scope'],
                    'base_price' => $basePrice,
                    'scope_multiplier' => $scopeMultiplier,
                    'scope_adjusted_base_price' => $scopeAdjustedBasePrice,
                    'addons_total' => $addonsTotal,
                    'quoted_price' => $quotedPrice,
                    'booking_date' => $bookingDate,
                    'time_from' => $timeFrom->format('H:i'),
                    'time_to' => $timeTo->format('H:i'),
                    'addons' => $addons->pluck('name')->values()->all(),
                    'message' => $validated['message'] ?? null,
                ], JSON_UNESCAPED_UNICODE),
                'status' => 'new',
            ]);

            $slot->update([
                'is_booked' => true,
            ]);

            return $booking;
        });

        $siteSettings = SiteSetting::query()->latest()->first();
        $adminEmail = $siteSettings?->email ?: config('mail.from.address');

        try {
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new WindowCleaningAdminNotification($booking));
            }

            Mail::to($booking->email)->send(new WindowCleaningCustomerConfirmation($booking));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('window-cleaning')
            ->with('success', 'Bokningsförfrågan har skickats. Vi kontaktar dig snart.');
    }

    private function resolveBasePrice(Service $service, int $windowCount): int
    {
        $ranges = $service->priceRanges
            ->sortBy('sort_order')
            ->values();

        foreach ($ranges as $range) {
            if ($windowCount >= (int) $range->min_sqm && $windowCount <= (int) $range->max_sqm) {
                return (int) $range->price;
            }
        }

        if ($ranges->isNotEmpty() && $windowCount > (int) $ranges->last()->max_sqm) {
            return (int) $ranges->last()->price;
        }

        return 0;
    }
}