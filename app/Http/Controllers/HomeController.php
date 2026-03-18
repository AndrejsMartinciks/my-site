<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $siteSettings = SiteSetting::query()->latest()->first();

        $services = Service::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with([
                'frequencies.priceRanges',
                'priceRanges',
                'addons' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order'),
            ])
            ->get();

        $faqs = Faq::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $testimonials = Testimonial::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $calculatorData = [
            'services' => $services->mapWithKeys(function ($service) {
                $serviceData = [
                    'type' => $service->pricing_mode,
                    'supplements' => $service->addons->map(fn ($addon) => [
                        'name' => $addon->name,
                        'price' => $addon->price,
                    ])->values()->all(),
                ];

                if ($service->pricing_mode === 'frequency') {
                    $serviceData['frequencies'] = $service->frequencies->mapWithKeys(function ($frequency) {
                        $rangeString = $frequency->priceRanges
                            ->sortBy('sort_order')
                            ->map(fn ($range) => "{$range->min_sqm}-{$range->max_sqm}: {$range->price}")
                            ->implode('|');

                        return [$frequency->name => $rangeString];
                    })->all();
                } else {
                    $serviceData['ranges'] = $service->priceRanges
                        ->sortBy('sort_order')
                        ->map(fn ($range) => "{$range->min_sqm}-{$range->max_sqm}: {$range->price}")
                        ->implode('|');
                }

                return [$service->name => $serviceData];
            })->all(),
        ];

        return view('home', compact(
            'siteSettings',
            'services',
            'faqs',
            'testimonials',
            'calculatorData'
        ));
    }
}