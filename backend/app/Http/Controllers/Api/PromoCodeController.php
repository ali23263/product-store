<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::orderBy('created_at', 'desc')->get();
        return response()->json($promoCodes);
    }

    public function show($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        return response()->json($promoCode);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|unique:promo_codes,code',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $code = $request->code ?? strtoupper(Str::random(8));

        $promoCode = PromoCode::create([
            'code' => $code,
            'discount_percent' => $request->discount_percent,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'usage_limit' => $request->usage_limit,
            'used_count' => 0,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Promo code created successfully',
            'promo_code' => $promoCode,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $promoCode = PromoCode::findOrFail($id);

        $request->validate([
            'code' => 'sometimes|required|string|unique:promo_codes,code,' . $id,
            'discount_percent' => 'sometimes|required|numeric|min:0|max:100',
            'valid_from' => 'sometimes|required|date',
            'valid_until' => 'sometimes|required|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $promoCode->update($request->all());

        return response()->json([
            'message' => 'Promo code updated successfully',
            'promo_code' => $promoCode,
        ]);
    }

    public function destroy($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        $promoCode->delete();

        return response()->json([
            'message' => 'Promo code deleted successfully',
        ]);
    }

    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $promoCode = PromoCode::where('code', $request->code)->first();

        if (!$promoCode) {
            return response()->json([
                'valid' => false,
                'message' => 'Promo code not found',
            ], 404);
        }

        if (!$promoCode->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Promo code is invalid or expired',
            ], 422);
        }

        return response()->json([
            'valid' => true,
            'promo_code' => $promoCode,
            'message' => 'Promo code is valid',
        ]);
    }
}
