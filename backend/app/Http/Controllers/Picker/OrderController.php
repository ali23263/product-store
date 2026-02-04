<?php

namespace App\Http\Controllers\Picker;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'items.product')
            ->whereIn('status', ['pending', 'processing']);

        $orders = $query->orderBy('created_at', 'asc')
            ->paginate($request->get('per_page', 15));

        return response()->json($orders);
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');
        return response()->json($order);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,completed',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json($order);
    }
}
