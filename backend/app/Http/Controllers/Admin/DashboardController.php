<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $totalSales = Order::whereIn('status', ['completed', 'processing'])
            ->sum('total');

        $ordersCount = Order::count();

        $customersCount = User::where('role', 'customer')->count();

        $pendingOrders = Order::where('status', 'pending')->count();

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'total_sales' => $totalSales,
            'orders_count' => $ordersCount,
            'customers_count' => $customersCount,
            'pending_orders' => $pendingOrders,
            'recent_orders' => $recentOrders,
            'top_products' => $topProducts,
        ]);
    }
}
