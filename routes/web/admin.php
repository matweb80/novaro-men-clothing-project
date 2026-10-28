<?php

use App\Http\Controllers\Admin\User\UserController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::get('/', 'DashboardController@index')->middleware('can:edit-user')->name('dashboard');

Route::resource('users', 'User\UserController');
Route::get('/users/{user}/permissions', 'User\PermissionController@create')->name('users.permissions')->middleware('can:staff-user-permissions');
Route::post('/users/{user}/permissions', 'User\PermissionController@store')->name('users.permissions.store')->middleware('can:staff-user-permissions');
Route::resource('permissions', 'PermissionController');
Route::resource('roles', 'RoleController');
Route::resource('products', 'ProductController')->except(['show']);
Route::resource('products.gallery', 'ProductGalleryController');
Route::resource('badges', 'BadgeController')->except(['show']);


Route::get('comments/unapproved', 'CommentController@unapproved')->name('comments.unapproved');
Route::patch('comments/{comment}/approved', 'CommentController@updateComment')->name('approved.comments.update');
Route::resource('comments', 'CommentController')->only(['index', 'edit', 'update', 'destroy']);

Route::resource('categories', 'CategoryController');
Route::get('categories/{category}/createChild', 'CategoryController@createChild')->name('categories.create.child');
Route::post('categories/{category}/createChild', 'CategoryController@storeChild')->name('categories.store.child');

Route::post('attribute/values', 'AttributeController@getValues')->name('attribute.values');
Route::resource('attributes', 'AttributeController');
Route::resource('attributes.attribute-values', 'AttributeValueController');

Route::resource('orders', 'OrderController');
Route::get('orders/{order}/payments', 'OrderController@payments')->name('orders.payments');


Route::get('settings', 'SettingController@index')->name('settings.index');
Route::put('settings', 'SettingController@update')->name('settings.update');

Route::get('/messages', 'ContactMessageController@index')->name('messages.index');
Route::get('/messages/{id}', 'ContactMessageController@show')->name('messages.show');
Route::post('/messages/{id}/reply', 'ContactMessageController@reply')->name('messages.reply');

Route::resource('tags', 'TagController');
