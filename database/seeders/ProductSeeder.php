<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use App\Models\Sale;
use App\Models\SalesAgent;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ProductCategory::all();

        foreach ($categories as $category) {
            Product::factory(10)
            ->has(ProductStock::factory(),'stock')
            ->create([
                'product_category_id' => $category->id,
            ]);
        }
    }

}
