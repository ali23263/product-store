<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getOrCreateCart(Request $request)
    {
        if ($request->user()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => $request->user()->id],
                ['user_id' => $request->user()->id]
            );
        } else {
            $sessionId = $request->session()->getId();
            $cart = Cart::firstOrCreate(
                ['session_id' => $sessionId],
                ['session_id' => $sessionId]
            );
        }

        return $cart;
    }

    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load('items.product');

        return response()->json([
            'items' => $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'image' => $item->product->image,
                    'quantity' => $item->quantity,
                ];
            }),
            'total' => $cart->getTotal(),
        ]);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock',
            ], 400);
        }

        $cart = $this->getOrCreateCart($request);

        $cartItem = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => \DB::raw("quantity + {$request->quantity}"),
            ]
        );

        $cart->load('items.product');

        return response()->json([
            'message' => 'Item added to cart',
            'items' => $cart->items,
            'total' => $cart->getTotal(),
        ]);
    }

    public function updateItem(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateCart($request);

        if ($cartItem->cart_id !== $cart->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($cartItem->product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock',
            ], 400);
        }

        $cartItem->update(['quantity' => $request->quantity]);
        $cart->load('items.product');

        return response()->json([
            'message' => 'Cart updated',
            'items' => $cart->items,
            'total' => $cart->getTotal(),
        ]);
    }

    public function removeItem(Request $request, CartItem $cartItem)
    {
        $cart = $this->getOrCreateCart($request);

        if ($cartItem->cart_id !== $cart->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->delete();
        $cart->load('items.product');

        return response()->json([
            'message' => 'Item removed from cart',
            'items' => $cart->items,
            'total' => $cart->getTotal(),
        ]);
    }

    public function clear(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->items()->delete();

        return response()->json([
            'message' => 'Cart cleared',
            'items' => [],
            'total' => 0,
        ]);
    }

    public function sync(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateCart($request);
        
        // Clear existing items and add new ones
        $cart->items()->delete();
        
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->stock >= $item['quantity']) {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $item['product_id'],
                    'quantity' => min($item['quantity'], $product->stock),
                ]);
            }
        }

        $cart->load('items.product');

        return response()->json([
            'message' => 'Cart synced',
            'items' => $cart->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'image' => $item->product->image,
                    'quantity' => $item->quantity,
                ];
            }),
            'total' => $cart->getTotal(),
        ]);
    }
}
