<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Register extends Controller
{
    public function __invoke(Request $request)
    {

        $formFields = $request->validate([
            'name' => 'required|string|max:255|min:5',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.max' => 'Name must be 255 characters or less.',
            'name.min' => 'Name must be at lest 5 characters',
        ]);

        $formFields['group_id'] = 4;

        $user = User::create($formFields);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Welcome, ' . Auth::user()->name . '!');
    }
}
