<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService)
    {

    }

    public function index()
    {
        $orders = $this->orderService->getOrders();

        return inertia('Orders/Index', compact('orders'));
    }
}
