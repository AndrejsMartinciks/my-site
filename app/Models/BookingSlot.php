<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingSlot extends Model
{
    protected $fillable = [
        'service_id',
        'booking_date',
        'time_from',
        'time_to',
        'is_active',
        'is_booked',
        'sort_order',
        'internal_note',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'is_active' => 'boolean',
            'is_booked' => 'boolean',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}