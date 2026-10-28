<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'g-recaptcha-response' => ['required', new Recaptcha],
        ], [
            'name.required' => 'نام و نام خانوادگی الزامی است',
            'name.min' => 'نام حداقل باید 3 کاراکتر باشد',
            'email.required' => 'ایمیل الزامی است',
            'email.email' => 'فرمت ایمیل صحیح نیست',
            'email.unique' => 'این ایمیل قبلا ثبت شده است',
            'password.required' => 'رمز عبور الزامی است',
            'password.min' => 'رمز عبور حداقل 8 کاراکتر باشد',
            'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد',
            'g-recaptcha-response.required' => 'لطفا روی من ربات نیستم کلیک کنید'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function registered(Request $request, $user)
    {
        $previousUrl = $request->session()->get(
            'auth.previous_url',
            '/'
        );

        $path = parse_url($previousUrl, PHP_URL_PATH) ?? '/';

        // جلوگیری از هدایت به API
        if (str_starts_with($path, '/api/')) {
            $previousUrl = '/';
        }

        // جلوگیری از هدایت به مقصد intended از نوع API
        $intendedUrl = $request->session()->get(
            'url.intended',
            '/'
        );

        $intendedPath = parse_url($intendedUrl, PHP_URL_PATH) ?? '/';

        if (str_starts_with($intendedPath, '/api/')) {
            $request->session()->forget('url.intended');
        } else {
            $previousUrl = $intendedUrl !== '/'
                ? $intendedUrl
                : $previousUrl;
        }

        return redirect()->to($previousUrl);
    }
}
