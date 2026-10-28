<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\ActiveCode;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TokenAuthController extends Controller
{
    public function postPhoneVerify(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required',
            'phone' => 'nullable'
        ]);

        $status = ActiveCode::verifyCode($request->token, $request->user());

        if ($status) {
            $request->user()->activeCode()->delete();
            $request->user()->update([
                'phone_number' => $validated['phone'],
                'two_factor_type' => 'sms'
            ]);

            return response()->json([
                'success' => true,
                'goToRoute' => 'twofactor'
            ]);
        } else if (! $request->user()->activeCode()->whereCode($request->token)->first()) {
            return response()->json([
                'success' => false,
                'error' => 'Not Exist Token'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Expired Token'
            ]);
        }
    }
}
