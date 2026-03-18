<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'pricing_mode',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function frequencies()
    {
        return $this->hasMany(ServiceFrequency::class)->orderBy('sort_order');
    }

    public function priceRanges()
    {
        return $this->hasMany(ServicePriceRange::class)->orderBy('sort_order');
    }

    public function addons()
    {
        return $this->hasMany(ServiceAddon::class)->orderBy('sort_order');
    }
}