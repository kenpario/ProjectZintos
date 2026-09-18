<?php

namespace App\Http\Responses\Auth;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResetResponse implements \Laravel\Fortify\Contracts\PasswordResetResponse
{
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return new JsonResponse('', 200);
        }

        return redirect()->route('login')
            ->with('success', 'Your password has been changed.');
    }
}
