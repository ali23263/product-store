<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCart(Request $request)
    {
        $cart = $request->user()->cart()->with(['items.product.category'])->first();

        if (!$cart) {
            $cart = $request->user()->cart()->create();
        }

        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return response()->json([
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock',
            ], 422);
        }

        $cart = $request->user()->cart;

        if (!$cart) {
            $cart = $request->user()->cart()->create();
        }

        // Check if item already exists in cart
        $cartItem = $cart->items()->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            // Check stock for total quantity
            if ($product->stock < ($cartItem->quantity + $request->quantity)) {
                return response()->json([
                    'message' => 'Insufficient stock',
                ], 422);
            }
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cartItem = $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        $cart->load(['items.product.category']);

        return response()->json([
            'message' => 'Item added to cart',
            'cart' => $cart,
        ]);
    }

    public function updateItem(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('id', $itemId)
            ->whereHas('cart', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->firstOrFail();

        // Check stock
        if ($cartItem->product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock',
            ], 422);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $cart = $request->user()->cart()->with(['items.product.category'])->first();

        return response()->json([
            'message' => 'Cart item updated',
            'cart' => $cart,
        ]);
    }

    public function removeItem(Request $request, $itemId)
    {
        $cartItem = CartItem::where('id', $itemId)
            ->whereHas('cart', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->firstOrFail();

        $cartItem->delete();

        $cart = $request->user()->cart()->with(['items.product.category'])->first();

        return response()->json([
            'message' => 'Item removed from cart',
            'cart' => $cart,
        ]);
    }

    public function clearCart(Request $request)
    {
        $cart = $request->user()->cart;

        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json([
            'message' => 'Cart cleared',
        ]);
    }
}
