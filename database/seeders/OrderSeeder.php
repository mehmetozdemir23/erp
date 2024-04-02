<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ordersCount = 15;
        for ($i = 0; $i < $ordersCount; $i++) {
            $order = Order::factory()->create([
                'customer_id' => Customer::inRandomOrder()->first()->id,
            ]);

            $itemsCount = rand(1, 3);
            for ($j = 0; $j < $itemsCount; $j++) {
                OrderItem::factory()->create([
                    'product_id' => Product::inRandomOrder()->first()->id,
                    'order_id' => $order->id,
                ]);
            }
        }
    }
}
