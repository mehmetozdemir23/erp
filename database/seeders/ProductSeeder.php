<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {

        $productsCount = 27;

        for ($i = 0; $i < $productsCount; $i++) {
            Product::factory()
                ->has(ProductStock::factory(), 'stock')
                ->create([
                    'product_category_id' => ProductCategory::inRandomOrder()->first()->id,
                    'updated_at' => now()->addDays(rand(1, 10)),
                ]);
        }

    }
}
