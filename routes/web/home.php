<?php

use App\Helpers\Cart\Cart;
use Illuminate\Support\Facades\Route;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Morilog\Jalali\Jalalian;

Route::get('/sitemap.xml', 'App\Http\Controllers\SitemapController@index');

Route::get('/', 'App\Http\Controllers\WelcomeController@welcome')->name('home');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return ['status' => 'ok'];
})->middleware('auth');


Auth::routes(['verify' => true]);
Route::get('/auth/token', [App\Http\Controllers\Auth\AuthTokenController::class, 'getToken'])->name('2fa.token');
Route::post('/auth/token', [App\Http\Controllers\Auth\AuthTokenController::class, 'postToken']);

Route::get('/auth/google', 'App\Http\Controllers\Auth\GoogleAuthController@redirect')->name('auth.google');
Route::get('/auth/google/callback', 'App\Http\Controllers\Auth\GoogleAuthController@callback');

Route::get('/auth/github', 'App\Http\Controllers\Auth\GithubAuthController@redirect')->name('auth.github');
Route::get('/auth/github/callback', 'App\Http\Controllers\Auth\GithubAuthController@callback');

Route::get('/auth/linkedin', 'App\Http\Controllers\Auth\LinkedinAuthController@redirect')->name('auth.linkedin');
Route::get('/auth/linkedin/callback', 'App\Http\Controllers\Auth\LinkedinAuthController@callback');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.page');

// Profile Routes
Route::prefix('profile')->namespace('App\Http\Controllers')->middleware('auth')->group(function () {
    Route::get('/{any?}', 'ProfileController@index')->where('any', '.*');
});
// Profile APIs
Route::namespace('App\Http\Controllers\Api\Profile')->group(function () {
    Route::get('/user', 'UserDataController@user');

    Route::post('/twofactor', 'TwoFactorAuthController@postManageTwoFactor');

    Route::post('/twofactor/phone', 'TokenAuthController@postPhoneVerify');

    Route::get('/orders/{order}', 'UserDataController@showDetails')->name('profile.orders.detail');
    Route::get('/orders/{order}/payment', 'OrderController@payment')->name('profile.orders.payment');

    Route::post('/edit-account', 'EditAccountController@updateUserDetails');
});

Route::get('/products', 'App\Http\Controllers\ProductController@index');
Route::get('/products/{product}', 'App\Http\Controllers\ProductController@single');

Route::get('categories/{category}', 'App\Http\Controllers\CategoryController@showProducts')->name('categories.show.products');

Route::get('tags/{tag}', 'App\Http\Controllers\TagController@showProducts')->name('tags.show.products');


Route::middleware('auth')->namespace('App\Http\Controllers')->group(function () {
    Route::get('payment/callback', 'PaymentController@callback')->name('payment.callback');
    Route::get('/checkout', 'App\Http\Controllers\CheckoutController@index')->name('checkout');
    Route::get('/checkout/address', 'AddressController@show');
    Route::post('/checkout/address', 'AddressController@store');
    Route::post('/payment', 'PaymentController@payment')->name('cart.payment');
});

Route::get('/contact-us', 'App\Http\Controllers\ContactMessageController@create')->name('contact.us.create');

Route::get('/about-us', 'App\Http\Controllers\AboutUsController@index')->name('about.us');

Route::get('/shipping-returns', 'App\Http\Controllers\ShippingReturnsController@index')->name('shipping.returns');
Route::get('/privacy-policy', 'App\Http\Controllers\PrivacyController@index')->name('privacy.policy');
Route::get('/terms', 'App\Http\Controllers\TermsController@index')->name('terms');
