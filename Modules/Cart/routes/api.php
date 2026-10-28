<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // Route::apiResource('carts', CartController::class)->names('cart');
});

Route::prefix('cart')->namespace('Modules\Cart\Http\Controllers\Frontend')->group(function () {
    // Route::get('/', 'CartController@cart');
    Route::post('/add/{product->slug}', 'CartController@addToCart')->name('cart.add');
    Route::patch('/quantity/change', 'CartController@quantityChange');
    Route::delete('/delete/{cart}', 'CartController@deleteFromCart')->name('cart.destroy');
});