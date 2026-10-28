<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditAccountController extends Controller
{
    public function updateUserDetails(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->firstOrFail();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        if (! is_null($request->phone)) {
            $request->validate([
                'phone' => ['required', 'string', 'max:11'],
            ]);
            $data['phone_number'] = $request->phone;
        } else {
            $data['phone_number'] = null;
            $request->user()->update([
                'two_factor_type' => 'off'
            ]);
        }

        if (! is_null($request->password)) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'goToRoute' => 'edit-account'
        ]);
    }
}
