<?php

use Illuminate\Support\Facades\Route;

Route::post('/decrypt', 'AppDataController@decrypt');

Route::get('/products', 'ProductController@index');
Route::get('/special-products', 'ProductController@specialProducts');
Route::get('/products/{slug}', 'ProductController@show');
Route::get('/products/{slug}/gallery');

Route::get('/filters', 'FilterController@index');

Route::post('/comments', 'CommentController@store');

// Route::get('/blog' , 'BlogController@index');
Route::post('/contact-us', 'ContactMessageController@store');

Route::get('/categories', 'CategoryController@index');
Route::get('/categories/{slug}', 'CategoryController@showProducts');

Route::get('/tags/{slug}', 'TagController@showProducts');


Route::prefix('cart')->middleware('web')->group(function () {
    Route::get('/', 'CartController@get');
    Route::get('/product/{slug}', 'CartController@getProduct');
    Route::post('/add/{slug}', 'CartController@addToCart')->name('cart.add');
    Route::patch('/quantity/change/{slug}', 'CartController@quantityChange');
    Route::delete('/delete/{cart}', 'CartController@deleteFromCart')->name('cart.destroy');
});