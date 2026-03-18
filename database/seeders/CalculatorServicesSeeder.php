<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CalculatorServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Hemstädning',
                'slug' => 'hemstadning',
                'description' => 'Återkommande hemstädning med pris beroende på storlek och frekvens.',
                'pricing_mode' => 'frequency',
                'is_active' => true,
                'sort_order' => 1,
                'addons' => [
                    ['name' => 'RH Mikrofibertrasor (gratis)', 'price' => 0, 'sort_order' => 1],
                ],
                'frequencies' => [
                    [
                        'name' => 'Varannan vecka',
                        'is_active' => true,
                        'sort_order' => 1,
                        'ranges' => '0-50: 1374|51-62: 1603|63-85: 1832|86-106: 2061|107-170: 2290|171-180: 2519|181-200: 2748|201-240: 3206',
                    ],
                    [
                        'name' => 'Varje vecka',
                        'is_active' => true,
                        'sort_order' => 2,
                        'ranges' => '0-50: 2748|51-62: 3206|63-85: 3664|86-106: 4122|107-170: 4580|171-180: 5038|181-200: 5496|201-240: 6412',
                    ],
                    [
                        'name' => 'Var fjärde vecka',
                        'is_active' => true,
                        'sort_order' => 3,
                        'ranges' => '0-50: 777|51-85: 1036|86-106: 1166|107-150: 1295|151-170: 1425',
                    ],
                ],
            ],

            [
                'name' => 'Storstädning',
                'slug' => 'storstadning',
                'description' => 'Grundlig storstädning med fasta prisnivåer efter bostadens storlek.',
                'pricing_mode' => 'fixed',
                'is_active' => true,
                'sort_order' => 2,
                'ranges' => '0-45: 2100|46-69: 2500|70-110: 3100|111-149: 3500|150-171: 4300|172-201: 4800|202-248: 5900',
                'addons' => [
                    ['name' => 'Rengöring av ugn (285 kr/st)', 'price' => 285, 'sort_order' => 1],
                    ['name' => 'Rengöring av kylskåp (225 kr/st)', 'price' => 225, 'sort_order' => 2],
                    ['name' => 'Ångtvätt av badrum 1-8 kvm (550 kr/st)', 'price' => 550, 'sort_order' => 3],
                ],
            ],

            [
                'name' => 'Flyttstädning',
                'slug' => 'flyttstadning',
                'description' => 'Flyttstädning med fasta prisnivåer efter storlek och valbara tillägg.',
                'pricing_mode' => 'fixed',
                'is_active' => true,
                'sort_order' => 3,
                'ranges' => '0-39: 2000|40-49: 2400|50-59: 2800|60-75: 3300|76-85: 3700|86-95: 4000|96-105: 4400|106-125: 4700|126-150: 5100|151-180: 5600|181-199: 6200|200-221: 6700|222-240: 7200',
                'addons' => [
                    ['name' => 'Teleskopstege 3m höjd (150 kr)', 'price' => 150, 'sort_order' => 1],
                    ['name' => 'Fönsterputs halvinglasad balkong >6m2 (+350 kr)', 'price' => 350, 'sort_order' => 2],
                    ['name' => 'Fönsterputs helinglasad balkong >6m2 (+500 kr)', 'price' => 500, 'sort_order' => 3],
                    ['name' => 'Möblerat boende (+500 kr)', 'price' => 500, 'sort_order' => 4],
                    ['name' => 'Sopning & våttorkning av räcken balkong <6m2 (+250 kr)', 'price' => 250, 'sort_order' => 5],
                ],
            ],

            [
                'name' => 'Visningsstädning',
                'slug' => 'visningsstadning',
                'description' => 'Visningsstädning med fasta prisnivåer efter storlek och valbara tillägg.',
                'pricing_mode' => 'fixed',
                'is_active' => true,
                'sort_order' => 4,
                'ranges' => '0-38: 2100|39-50: 2500|51-58: 2900|59-65: 3100|66-75: 3300|76-85: 3500|86-95: 3700|96-105: 3900|106-120: 4100|121-151: 4500|152-178: 4900|179-201: 5400|202-248: 6100',
                'addons' => [
                    ['name' => 'Rengöring av kylskåp (+250 kr/st)', 'price' => 250, 'sort_order' => 1],
                    ['name' => 'Rengöring av ugn (+285 kr/st)', 'price' => 285, 'sort_order' => 2],
                    ['name' => 'Rent Hem tar med all städmaterial (+150 kr)', 'price' => 150, 'sort_order' => 3],
                    ['name' => 'Ångtvätt av badrum 1-8 kvm (+550 kr)', 'price' => 550, 'sort_order' => 4],
                    ['name' => 'Fönsterputs halvinglasad balkong >6m2 (+350 kr)', 'price' => 350, 'sort_order' => 5],
                    ['name' => 'Fönsterputs helinglasad balkong >6m2 (+500 kr)', 'price' => 500, 'sort_order' => 6],
                    ['name' => 'Sopning + Våttorkning av balkong >6m2 (+250 kr)', 'price' => 250, 'sort_order' => 7],
                ],
            ],
        ];

        DB::transaction(function () use ($services) {
            foreach ($services as $serviceData) {
                $service = Service::updateOrCreate(
                    ['slug' => $serviceData['slug']],
                    [
                        'name' => $serviceData['name'],
                        'description' => $serviceData['description'],
                        'pricing_mode' => $serviceData['pricing_mode'],
                        'is_active' => $serviceData['is_active'],
                        'sort_order' => $serviceData['sort_order'],
                    ]
                );

                // Сначала чистим старые связанные данные этой услуги,
                // чтобы не было дублей и конфликтов.
                foreach ($service->frequencies as $oldFrequency) {
                    $oldFrequency->priceRanges()->delete();
                }

                $service->frequencies()->delete();
                $service->priceRanges()->delete();
                $service->addons()->delete();

                // Addons
                foreach ($serviceData['addons'] ?? [] as $addon) {
                    $service->addons()->create([
                        'name' => $addon['name'],
                        'price' => $addon['price'],
                        'is_active' => true,
                        'sort_order' => $addon['sort_order'],
                    ]);
                }

                // Frequency pricing
                if ($serviceData['pricing_mode'] === 'frequency') {
                    foreach ($serviceData['frequencies'] as $frequencyData) {
                        $frequency = $service->frequencies()->create([
                            'name' => $frequencyData['name'],
                            'is_active' => $frequencyData['is_active'],
                            'sort_order' => $frequencyData['sort_order'],
                        ]);

                        foreach ($this->parseRanges($frequencyData['ranges']) as $range) {
                            $frequency->priceRanges()->create([
                                'min_sqm' => $range['min_sqm'],
                                'max_sqm' => $range['max_sqm'],
                                'price' => $range['price'],
                                'sort_order' => $range['sort_order'],
                            ]);
                        }
                    }
                } else {
                    // Fixed pricing
                    foreach ($this->parseRanges($serviceData['ranges']) as $range) {
                        $service->priceRanges()->create([
                            'min_sqm' => $range['min_sqm'],
                            'max_sqm' => $range['max_sqm'],
                            'price' => $range['price'],
                            'sort_order' => $range['sort_order'],
                        ]);
                    }
                }
            }
        });
    }

    private function parseRanges(string $rangeString): array
    {
        $items = array_filter(array_map('trim', explode('|', $rangeString)));

        $result = [];

        foreach ($items as $index => $item) {
            [$rangePart, $pricePart] = array_map('trim', explode(':', $item));
            [$min, $max] = array_map(fn ($value) => (int) trim($value), explode('-', $rangePart));

            $result[] = [
                'min_sqm' => $min,
                'max_sqm' => $max,
                'price' => (int) trim($pricePart),
                'sort_order' => $index + 1,
            ];
        }

        return $result;
    }
}