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
        'hero_point_1',
        'hero_point_2',
        'hero_point_3',
        'hero_badge_1',
        'hero_badge_2',
        'hero_badge_3',
        'trust_eyebrow',
        'trust_title',
        'trust_item_1_title',
        'trust_item_1_text',
        'trust_item_2_title',
        'trust_item_2_text',
        'trust_item_3_title',
        'trust_item_3_text',
        'trust_item_4_title',
        'trust_item_4_text',
        'about_eyebrow',
        'about_title',
        'about_text_1',
        'about_text_2',
        'about_check_title',
        'about_check_1',
        'about_check_2',
        'about_check_3',
        'about_check_4',
        'rut_eyebrow',
        'rut_title',
        'rut_text_1',
        'rut_text_2',
        'footer_text',
        'seo_title',
        'seo_description',
    ];
}