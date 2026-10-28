<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\ActiveCode;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, TwoFactorAuthenticate;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        return $this->loggedin($request, $user);
    }


    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'ایمیل الزامی است',
            'email.email' => 'فرمت ایمیل صحیح نیست',
            'password.required' => 'رمز عبور الزامی است',
            'password.min' => 'رمز عبور حداقل 8 کاراکتر باشد',
        ]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'ایمیل یا رمز عبور اشتباه است'
            ])->withInput();
        }

        auth()->login($user);

        $previousUrl = session('auth.previous_url', '/');

        $path = parse_url($previousUrl, PHP_URL_PATH) ?? '/';

        if (str_starts_with($path, '/api/')) {
            $previousUrl = '/';
        }

        $intendedUrl = session('url.intended', '/');
        $intendedPath = parse_url($intendedUrl, PHP_URL_PATH) ?? '/';

        if (str_starts_with($intendedPath, '/api/')) {
            session()->forget('url.intended');
        }

        return redirect()->to($previousUrl);
    }
}
