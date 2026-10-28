<?php

namespace Modules\Discount\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Cart\Helpers\Cart;
use Modules\Discount\Models\Discount;

class DiscountController extends Controller
{
    public function check(Request $request)
    {
        $validated = $request->validate([
            'discount' => 'required|exists:discounts,code',
            'cart' => 'required'
        ]);

        if (! auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Login Attempt',
            ]);
        }

        $discount = Discount::whereCode($validated['discount'])->first();

        if ($discount->expired_at < now()) {
            return response()->json([
                'success' => false,
                'message' => 'Expired Discount',
            ]);
        }

        if ($discount->users()->count()) {
            if (! in_array(auth()->user()->id, $discount->users()->pluck('id')->toArray())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unaccessable Discount',
                ]);
            }
        }

        $cart = Cart::instance($validated['cart']);
        $cart->addDiscount($discount->code);

        return response()->json([
            'success' => true,
            'cart' => Cart::getDiscount()
        ]);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'cart' => 'required'
        ]);
        $cart = Cart::instance($validated['cart']);
        $cart->addDiscount(null);

        return response()->json([
            'success' => true,
        ]);
    }
}
