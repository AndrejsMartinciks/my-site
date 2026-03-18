<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'service_id',
        'booking_slot_id',
        'booking_type',
        'booking_date',
        'time_from',
        'time_to',
        'customer_name',
        'personnummer',
        'personnummer_last4',
        'address',
        'postcode',
        'phone',
        'email',
        'sqm',
        'window_count',
        'cleaning_scope',
        'quoted_price',
        'addons',
        'calculator_summary',
        'status',
        'manager_note',
        'contacted_at',
        'done_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'addons' => 'array',
        'personnummer' => 'encrypted',
        'contacted_at' => 'datetime',
        'done_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}