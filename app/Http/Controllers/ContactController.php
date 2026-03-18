<?php

namespace App\Http\Controllers;

use App\Mail\BookingSubmittedMail;
use App\Mail\LeadSubmittedMail;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'min:6', 'max:50'],
            'service' => ['required', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
            'calculator_summary' => ['nullable', 'string', 'max:5000'],

            'booking_slot_id' => ['nullable', 'integer', 'exists:booking_slots,id'],
            'booking_date' => ['nullable', 'date_format:Y-m-d'],
            'booking_time_from' => ['nullable', 'date_format:H:i'],
            'booking_time_to' => ['nullable', 'date_format:H:i'],

            'personnummer' => ['nullable', 'string', 'max:32'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $isBookingRequest = ($data['service'] ?? '') === 'Engångsstädning';

        if ($isBookingRequest) {
            $request->validate([
                'booking_slot_id' => ['required', 'integer', 'exists:booking_slots,id'],
                'personnummer' => ['required', 'string', 'max:32'],
                'address' => ['required', 'string', 'max:255'],
            ]);

            $data['booking_slot_id'] = (int) $request->input('booking_slot_id');
            $data['personnummer'] = (string) $request->input('personnummer');
            $data['address'] = (string) $request->input('address');

            $normalizedPersonnummer = $this->normalizePersonnummer($data['personnummer']);
            $this->assertValidSwedishPersonnummer($normalizedPersonnummer);

            $data['personnummer_normalized'] = $normalizedPersonnummer;
        }

        $source = filled($data['calculator_summary'] ?? null) ? 'calculator' : 'form';

        $lead = null;
        $booking = null;

        DB::transaction(function () use (&$lead, &$booking, &$data, $isBookingRequest, $source): void {
            if ($isBookingRequest) {
                $slot = BookingSlot::query()
                    ->with('service')
                    ->lockForUpdate()
                    ->find($data['booking_slot_id']);

                if (
                    !$slot ||
                    !$slot->is_active ||
                    $slot->is_booked ||
                    ($slot->service?->slug !== 'engangsstadning')
                ) {
                    throw ValidationException::withMessages([
                        'booking_slot_id' => 'Den valda tiden är inte längre ledig. Välj en annan tid.',
                    ]);
                }

                $normalizedPersonnummer = $data['personnummer_normalized'];
                $personnummerLast4 = substr($normalizedPersonnummer, -4);

                $booking = Booking::create([
                    'service_id' => $slot->service_id,
                    'booking_slot_id' => $slot->id,
                    'booking_date' => $slot->booking_date,
                    'time_from' => $slot->time_from,
                    'time_to' => $slot->time_to,
                    'customer_name' => $data['name'],
                    'personnummer' => $normalizedPersonnummer,
                    'personnummer_last4' => $personnummerLast4,
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'calculator_summary' => $data['calculator_summary'] ?? '',
                    'status' => 'new',
                ]);

                $slot->update([
                    'is_booked' => true,
                ]);

                $data['booking_date'] = optional($slot->booking_date)->format('Y-m-d');
                $data['booking_time_from'] = substr((string) $slot->time_from, 0, 5);
                $data['booking_time_to'] = substr((string) $slot->time_to, 0, 5);
            }

            $lead = Lead::create([
                'uuid' => (string) Str::uuid(),
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'service' => $data['service'],
                'message' => $data['message'] ?? '',
                'calculator_summary' => $data['calculator_summary'] ?? '',
                'status' => 'new',
                'source' => $source,
            ]);
        });

        $record = [
            'id' => $lead->uuid,
            'db_id' => $lead->id,
            'booking_id' => $booking?->id,
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'service' => $lead->service,
            'message' => $lead->message ?? '',
            'calculator_summary' => $lead->calculator_summary ?? '',
            'status' => $lead->status,
            'source' => $lead->source,
            'booking_date' => $data['booking_date'] ?? '',
            'booking_time_from' => $data['booking_time_from'] ?? '',
            'booking_time_to' => $data['booking_time_to'] ?? '',
            'created_at' => $lead->created_at?->toIso8601String(),
        ];

        $path = storage_path('app/leads.json');

        if (!File::exists($path)) {
            File::put($path, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $leads = json_decode(File::get($path), true);

        if (!is_array($leads)) {
            $leads = [];
        }

        $leads[] = $record;

        File::put(
            $path,
            json_encode($leads, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        try {
            if ($booking) {
                Mail::to(env('LEADS_TO_EMAIL', 'info@cleansource.se'))
                    ->send(new BookingSubmittedMail($booking));
            } else {
                Mail::to(env('LEADS_TO_EMAIL', 'info@cleansource.se'))
                    ->send(new LeadSubmittedMail($record));
            }
        } catch (\Throwable $e) {
            Log::error('Lead/booking email send failed', [
                'message' => $e->getMessage(),
                'lead_id' => $record['id'] ?? null,
                'db_id' => $record['db_id'] ?? null,
                'booking_id' => $booking?->id,
            ]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Tack! Din förfrågan har skickats.',
            'record' => $record,
        ], 201);
    }

    private function normalizePersonnummer(string $value): string
    {
        return preg_replace('/\D+/', '', trim($value)) ?? '';
    }

    private function assertValidSwedishPersonnummer(string $digits): void
{
    if (!in_array(strlen($digits), [10, 12], true)) {
        throw ValidationException::withMessages([
            'personnummer' => 'Ange ett giltigt personnummer i format YYYYMMDDXXXX eller YYMMDDXXXX.',
        ]);
    }

    if (app()->isLocal()) {
        return;
    }

    $tenDigits = strlen($digits) === 12 ? substr($digits, -10) : $digits;

    if (!$this->passesLuhn($tenDigits)) {
        throw ValidationException::withMessages([
            'personnummer' => 'Personnumret verkar inte vara giltigt.',
        ]);
    }
}

    private function passesLuhn(string $digits): bool
    {
        if (!preg_match('/^\d{10}$/', $digits)) {
            return false;
        }

        $sum = 0;

        foreach (str_split($digits) as $index => $char) {
            $number = (int) $char;

            if ($index % 2 === 0) {
                $number *= 2;

                if ($number > 9) {
                    $number -= 9;
                }
            }

            $sum += $number;
        }

        return $sum % 10 === 0;
    }
}