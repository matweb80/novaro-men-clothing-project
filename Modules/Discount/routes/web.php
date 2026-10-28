<?php

use Illuminate\Support\Facades\Route;
use Modules\Discount\Http\Controllers\DiscountController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('discounts', DiscountController::class)->names('discount');
});

Route::prefix('discount')->group(function(){
    Route::post('check','Api\DiscountController@check')->name('cart.discount.check');
    Route::delete('delete','Api\DiscountController@destroy')->name('cart.discount.delete');
});
