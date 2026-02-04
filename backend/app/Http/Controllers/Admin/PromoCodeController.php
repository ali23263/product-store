<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::paginate(15);
        return response()->json($promoCodes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $promoCode = PromoCode::create([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_purchase' => $request->get('min_purchase', 0),
            'max_uses' => $request->max_uses,
            'expires_at' => $request->expires_at,
            'is_active' => $request->get('is_active', true),
        ]);

        return response()->json($promoCode, 201);
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code,' . $promoCode->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $promoCode->update([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_purchase' => $request->get('min_purchase', 0),
            'max_uses' => $request->max_uses,
            'expires_at' => $request->expires_at,
            'is_active' => $request->get('is_active', $promoCode->is_active),
        ]);

        return response()->json($promoCode);
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return response()->json(['message' => 'Promo code deleted successfully']);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'count' => 'required|integer|min:1|max:100',
        ]);

        $promoCodes = [];

        for ($i = 0; $i < $request->count; $i++) {
            $code = strtoupper(Str::random(8));

            while (PromoCode::where('code', $code)->exists()) {
                $code = strtoupper(Str::random(8));
            }

            $promoCodes[] = PromoCode::create([
                'code' => $code,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'min_purchase' => $request->get('min_purchase', 0),
                'max_uses' => $request->max_uses,
                'expires_at' => $request->expires_at,
                'is_active' => true,
            ]);
        }

        return response()->json($promoCodes, 201);
    }
}
