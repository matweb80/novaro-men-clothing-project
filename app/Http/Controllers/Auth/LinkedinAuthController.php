<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LinkedinAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $linkedinUser = Socialite::driver('linkedin')->user();
            $user = User::where('email', $linkedinUser->email)->first();
            if (! $user) {
                $user = User::create([
                    'name' => $linkedinUser->name,
                    'email' => $linkedinUser->email,
                    'password' => bcrypt(Str::random(16))
                ]);
            }
            auth()->loginUsingId($user->id);

            return $this->loggedin($request, $user)? : redirect('/');
        } catch (Exception $e) {
            //TODO show Error message
            return $e;
        }
    }
}
