<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderService
{

    public function getOrders(): AnonymousResourceCollection
    {
        $orders = Order::with(['product:id,name', 'customer:id,name'])->paginate(7)->withQueryString();

        return OrderResource::collection($orders);
    }
}