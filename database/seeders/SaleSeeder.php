<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $randomOrders = Order::inRandomOrder()->limit(5)->get();

        foreach ($randomOrders as $order) {
            $order->status = 'completed';
            $order->save();

            Sale::factory()->create([
                'order_id' => $order->id,
            ]);
        }
    }
}
