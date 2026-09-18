<?php

namespace App\Http\Responses\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class VerifyResponse implements \Laravel\Fortify\Contracts\VerifyEmailResponse
{
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return new JsonResponse('', 200);
        }

        return redirect()->intended(Fortify::redirects('verify-email'))
            ->with('success', 'You are verified now, ' . Auth::user()->name . '!');
    }
}
