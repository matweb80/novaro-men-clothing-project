<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GithubAuthController extends Controller
{
    use TwoFactorAuthenticate;

    public function redirect()
    {
        return Socialite::driver('github')->with(['prompt' => 'consent select_account'])->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            $user = User::where('email', $githubUser->email)->first();
            if (! $user) {
                $user = User::create([
                    'name' => $githubUser->name,
                    'email' => $githubUser->email,
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
