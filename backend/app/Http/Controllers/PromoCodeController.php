<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PromoCodeController extends Controller
{
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'nullable|numeric|min:0',
        ]);

        $promoCode = PromoCode::where('code', $request->code)
            ->where('is_active', true)
            ->first();

        if (!$promoCode) {
            return response()->json([
                'valid' => false,
                'message' => 'Promo code not found',
            ], 404);
        }

        // Check expiration
        if ($promoCode->expires_at && Carbon::parse($promoCode->expires_at)->isPast()) {
            return response()->json([
                'valid' => false,
                'message' => 'Promo code has expired',
            ], 400);
        }

        // Check usage limit
        if ($promoCode->max_uses && $promoCode->used_count >= $promoCode->max_uses) {
            return response()->json([
                'valid' => false,
                'message' => 'Promo code has reached its usage limit',
            ], 400);
        }

        // Check minimum purchase
        $subtotal = $request->subtotal ?? 0;
        if ($promoCode->min_purchase && $subtotal < $promoCode->min_purchase) {
            return response()->json([
                'valid' => false,
                'message' => "Minimum purchase of \${$promoCode->min_purchase} required",
            ], 400);
        }

        // Calculate discount
        $discountAmount = 0;
        if ($promoCode->discount_type === 'percentage') {
            $discountAmount = $subtotal * ($promoCode->discount_value / 100);
        } else {
            $discountAmount = min($promoCode->discount_value, $subtotal);
        }

        return response()->json([
            'valid' => true,
            'promo_code' => [
                'id' => $promoCode->id,
                'code' => $promoCode->code,
                'discount_type' => $promoCode->discount_type,
                'discount_value' => $promoCode->discount_value,
            ],
            'discount_amount' => round($discountAmount, 2),
            'message' => 'Promo code applied successfully',
        ]);
    }
}
