<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function creating(Order $order): void
    {
        $order->total_amount = $order->product->price * $order->quantity;
    }

}
