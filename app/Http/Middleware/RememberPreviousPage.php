<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RememberPreviousPage
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->isMethod('GET') &&
            !$request->expectsJson() &&
            !$request->ajax() &&
            !$request->is('api/*') &&
            !$request->is('login') &&
            !$request->is('register') &&
            !$request->is('password/*')
        ) {
            $request->session()->put(
                'auth.previous_url',
                $request->fullUrl()
            );
        }

        return $next($request);
    }
}
