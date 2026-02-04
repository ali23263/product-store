<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'promoCode'])
            ->where('user_id', $request->user()->id);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($orders);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with(['items.product.category', 'promoCode'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'nullable|string|exists:promo_codes,code',
        ]);

        $user = $request->user();
        $cart = $user->cart()->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty',
            ], 422);
        }

        // Check stock for all items
        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                return response()->json([
                    'message' => "Insufficient stock for product: {$item->product->name}",
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            // Calculate total
            $total = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $discountAmount = 0;
            $promoCodeId = null;

            // Apply promo code if provided
            if ($request->has('promo_code')) {
                $promoCode = PromoCode::where('code', $request->promo_code)->first();

                if ($promoCode && $promoCode->isValid()) {
                    $discountAmount = $total * ($promoCode->discount_percent / 100);
                    $promoCodeId = $promoCode->id;
                    $promoCode->incrementUsage();
                } else {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Invalid or expired promo code',
                    ], 422);
                }
            }

            $finalTotal = $total - $discountAmount;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $finalTotal,
                'promo_code_id' => $promoCodeId,
                'discount_amount' => $discountAmount,
            ]);

            // Create order items and update stock
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Decrease stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            $order->load(['items.product', 'promoCode']);

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);

        // Only admin can update to any status, picker can only update to processing/completed
        if ($request->user()->isPicker() && !in_array($request->status, ['processing', 'completed'])) {
            return response()->json([
                'message' => 'Unauthorized. Pickers can only update to processing or completed status.',
            ], 403);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order->load(['items.product', 'promoCode']),
        ]);
    }

    public function getAllOrders(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'promoCode']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($orders);
    }
}
