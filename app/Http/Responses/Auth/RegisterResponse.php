<?php

namespace App\Http\Responses\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class RegisterResponse implements \Laravel\Fortify\Contracts\RegisterResponse
{
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return new JsonResponse('', 201);
        }

        return redirect()->intended(Fortify::redirects('register'))
            ->with('success', 'Your account has been created, welcome '.Auth::user()->name.'!');
    }
}