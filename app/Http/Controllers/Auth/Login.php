<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

class Login extends Controller
{
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'cf-turnstile-response' => ['required', new Turnstile],
            'email' => 'required|email',
            'password' => 'required',
        ], ['cf-turnstile-response.required' => 'You need to complete the verification!']);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }
}
