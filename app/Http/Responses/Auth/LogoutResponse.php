<?php

namespace App\Http\Responses\Auth;

use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class LogoutResponse implements \Laravel\Fortify\Contracts\LogoutResponse
{
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return response()->json('', 204);
        }

        return redirect(Fortify::redirects('logout', '/'))
            ->with('success', 'You\'ve successfully logged out!');
    }
}