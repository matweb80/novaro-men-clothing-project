<?php

use App\Http\Middleware\RememberPreviousPage;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web/home.php',
        admin: __DIR__ . '/../routes/web/admin.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // Register api/ admin route file here
            Route::middleware('api')->prefix('api/vue')->namespace('App\Http\Controllers\Vue')
                ->group(base_path('routes/vue.php'));
            Route::middleware('api')->prefix('api')->namespace('App\Http\Controllers\Api')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        \Illuminate\Http\Middleware\HandleCors::class;
        \App\Http\Middleware\SecurityHeaders::class;
        $middleware->appendToGroup('web', [
            RememberPreviousPage::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
