<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePriceRange extends Model
{
    protected $fillable = [
        'service_id',
        'service_frequency_id',
        'min_sqm',
        'max_sqm',
        'price',
        'sort_order',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function frequency()
    {
        return $this->belongsTo(ServiceFrequency::class, 'service_frequency_id');
    }
}