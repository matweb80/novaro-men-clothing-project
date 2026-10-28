<?php

use Illuminate\Support\Facades\Route;


Route::resource('http', 'HttpController');

// translations
Route::get('translations/{locale}', 'TranslationController@index');

// search
Route::get('search/all', 'SearchController@index');

