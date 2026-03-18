<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InitialSiteContentSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'Clean Source AB',
                'phone_primary' => '070 741 37 72',
                'phone_secondary' => '08-838-538',
                'email' => 'info@cleansource.se',
                'address' => 'Ångermannagatan 121',
                'postal_code' => '162 64',
                'city' => 'Vällingby',
                'org_number' => '556988-2722',
                'hero_eyebrow' => 'Hemstädning • Flyttstädning • Fönsterputs',
                'hero_title' => 'Rent hemma, mer tid över för livet.',
                'hero_text' => 'Clean Source AB hjälper hushåll i Stockholm med noggrann städning, tydliga priser och snabb återkoppling.',
                'hero_primary_button_text' => 'Få kostnadsfri offert',
                'hero_secondary_button_text' => 'Se våra tjänster',
                'seo_title' => 'Clean Source AB | Hemstädning i Stockholm',
                'seo_description' => 'Professionell hemstädning, flyttstädning, byggstädning, fönsterputsning och storstädning i Stockholm.',
            ]
        );

        $hem = Service::updateOrCreate(
            ['slug' => 'hemstadning'],
            [
                'name' => 'Hemstädning',
                'description' => 'Återkommande hemstädning.',
                'pricing_mode' => 'frequency',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $stor = Service::updateOrCreate(
            ['slug' => 'storstadning'],
            [
                'name' => 'Storstädning',
                'description' => 'Djupare städning vid behov.',
                'pricing_mode' => 'fixed',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $flytt = Service::updateOrCreate(
            ['slug' => 'flyttstadning'],
            [
                'name' => 'Flyttstädning',
                'description' => 'Noggrann slutstädning inför flytt.',
                'pricing_mode' => 'fixed',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        $visning = Service::updateOrCreate(
            ['slug' => 'visningsstadning'],
            [
                'name' => 'Visningsstädning',
                'description' => 'Städning inför visning.',
                'pricing_mode' => 'fixed',
                'is_active' => true,
                'sort_order' => 4,
            ]
        );

        Faq::updateOrCreate(
            ['question' => 'Hur fungerar RUT-avdraget?'],
            [
                'answer' => 'Vi redovisar avdraget direkt på fakturan om du uppfyller villkoren.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Testimonial::updateOrCreate(
            ['name' => 'Anna'],
            [
                'city' => 'Bromma',
                'text' => 'Snabb offert och mycket noggrann hemstädning.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }
}