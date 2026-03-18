<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceFrequency extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function priceRanges()
    {
        return $this->hasMany(ServicePriceRange::class)->orderBy('sort_order');
    }
}