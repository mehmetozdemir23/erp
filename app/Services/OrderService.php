<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderService
{
    public function getOrders(Request $request): AnonymousResourceCollection
    {
        $status = $request->input('status', 'all');

        $ordersQuery = Order::query();

        if ($status !== 'all') {
            $ordersQuery->whereStatus($status);
        }

        $orders = $ordersQuery->with(['items.product:id,name,price', 'customer:id,name'])->paginate(7)->withQueryString();

        return OrderResource::collection($orders);
    }
}
