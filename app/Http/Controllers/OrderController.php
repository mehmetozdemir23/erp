<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService)
    {

    }

    public function index(Request $request): Response
    {
        $orders = $this->orderService->getOrders($request);
        $status = $request->input('status', 'all');

        return inertia('Orders/Index', compact('orders', 'status'));
    }

    public function show(Order $order): Response
    {
        $order->load('customer');
        $order = new OrderResource($order);

        return inertia('Orders/Show', compact('order'));
    }

    public function updateStatus(Order $order, Request $request): void
    {

    }
}
