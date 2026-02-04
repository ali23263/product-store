<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'promo_code' => 'nullable|string|exists:promo_codes,code',
        ]);

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Check stock availability
        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                return response()->json([
                    'message' => "Insufficient stock for {$item->product->name}",
                ], 400);
            }
        }

        $total = $cart->getTotal();
        $discount = 0;
        $promoCodeId = null;

        // Apply promo code if provided
        if ($request->promo_code) {
            $promoCode = PromoCode::where('code', $request->promo_code)->first();

            if (!$promoCode->isValid()) {
                return response()->json(['message' => 'Invalid or expired promo code'], 400);
            }

            $discount = $promoCode->calculateDiscount($total);
            $promoCodeId = $promoCode->id;
        }

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $total - $discount,
                'promo_code_id' => $promoCodeId,
                'discount' => $discount,
                'shipping_address' => $request->shipping_address,
            ]);

            // Create order items and reduce stock
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            // Update promo code usage
            if ($promoCodeId) {
                PromoCode::find($promoCodeId)->increment('used_count');
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            $order->load('items.product', 'promoCode');

            return response()->json($order, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Order creation failed'], 500);
        }
    }

    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product', 'promoCode')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($orders);
    }

    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->load('items.product', 'promoCode');

        return response()->json($order);
    }
}
