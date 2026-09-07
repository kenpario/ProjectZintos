<?php

namespace App\Http\Responses\Auth;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements \Laravel\Fortify\Contracts\LoginResponse
{
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        return redirect()->intended(Fortify::redirects('login'))
            ->with('success', 'Welcome back, '.Auth::user()->name.'!');
    }
}