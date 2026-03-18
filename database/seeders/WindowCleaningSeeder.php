<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class WindowCleaningSeeder extends Seeder
{
    public function run(): void
    {
        $service = Service::updateOrCreate(
            ['slug' => 'fonsterputsning'],
            [
                'name' => 'Fönsterputsning',
                'description' => 'Professionell fönsterputsning för lägenheter, villor och mindre kontor.',
                'pricing_mode' => 'fixed',
                'is_active' => true,
                'sort_order' => 5,
            ]
        );

        $service->priceRanges()->delete();
        $service->priceRanges()->createMany([
            [
                'min_sqm' => 1,
                'max_sqm' => 5,
                'price' => 499,
                'sort_order' => 1,
            ],
            [
                'min_sqm' => 6,
                'max_sqm' => 10,
                'price' => 899,
                'sort_order' => 2,
            ],
            [
                'min_sqm' => 11,
                'max_sqm' => 15,
                'price' => 1299,
                'sort_order' => 3,
            ],
            [
                'min_sqm' => 16,
                'max_sqm' => 20,
                'price' => 1699,
                'sort_order' => 4,
            ],
            [
                'min_sqm' => 21,
                'max_sqm' => 30,
                'price' => 2199,
                'sort_order' => 5,
            ],
        ]);

        $service->addons()->delete();
        $service->addons()->createMany([
            [
                'name' => 'Mellan',
                'price' => 149,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Stege',
                'price' => 199,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Spröjs',
                'price' => 249,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ]);
    }
}