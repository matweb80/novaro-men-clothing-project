<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;

Route::prefix('cart')->namespace('Modules\Cart\Http\Controllers\Frontend')->group(function () {
    Route::post('/add/{product}', 'CartController@addToCart')->name('cart.add');
    Route::get('/', 'CartController@cart')->name('cart');
    Route::patch('/quantity/change', 'CartController@quantityChange');
    Route::delete('/delete/{cart}', 'CartController@deleteFromCart')->name('cart.destroy');
});
