<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            Order::factory()->create([
                'product_id' => Product::inRandomOrder()->first()->id,
                'customer_id' => Customer::inRandomOrder()->first()->id,
            ]);
        }
    }
}
