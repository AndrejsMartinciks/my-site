<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    Product::create(['name' => 'Кофе по-рижски', 'price' => 4.50]);
    Product::create(['name' => 'Черный бальзам', 'price' => 12.00]);
    Product::create(['name' => 'Булочка с корицей', 'price' => 1.20]);
}
}
