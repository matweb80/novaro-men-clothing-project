<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\ActiveCode;
use Illuminate\Http\Request;

class TwoFactorAuthController extends Controller
{
    public function postManageTwoFactor(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:sms,off',
            'phone' => 'required_unless:type,off'
        ]);

        if ($data['type'] === 'sms') {
            //validation phone number
            if ($request->user()->phone_number !== $data['phone']) {
                // create a new code
                $code = ActiveCode::generateCode($request->user());
                // send the code to user phone number
                // $request->user()->notify(new ActiveCodeNotification($code, $data['phone']));

                return response()->json([
                    'success' => true,
                    'goToRoute' => 'twofactor/phone',
                    'phone' => $data['phone']
                ]);
            } else {
                $request->user()->update([
                    'two_factor_type' => 'sms'
                ]);
            }
        }

        if ($data['type'] === 'off') {
            $request->user()->update([
                'two_factor_type' => 'off'
            ]);
        }
        return response()->json([
            'success' => true,
            'goToRoute' => 'twofactor'
        ]);
    }
}
