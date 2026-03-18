<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'company_name',
        'phone_primary',
        'phone_secondary',
        'email',
        'address',
        'postal_code',
        'city',
        'org_number',
        'hero_eyebrow',
        'hero_title',
        'hero_text',
        'hero_primary_button_text',
        'hero_secondary_button_text',
        'seo_title',
        'seo_description',
    ];
}