<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
use Symfony\Component\HttpFoundation\Response;

class ValidateFortifyTurnstile
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('login.store', 'password.email')) {
            Validator::make($request->all(), [
                'cf-turnstile-response' => ['required', new Turnstile],
            ])->validate();
        }

        return $next($request);
    }
}