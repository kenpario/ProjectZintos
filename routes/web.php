<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/auth/redirect', function () {
        return Socialite::driver('google')->redirect();
    })->name('login');

    Route::get('/auth/callback', function (Request $request) {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::firstOrCreate([
                'google_id' => $googleUser->id,
            ], [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
                'group_id' => 4,
            ]);

            $tokenFields = ['google_token' => $googleUser->token];

            if ($googleUser->refreshToken !== null) {
                $tokenFields['google_refresh_token'] = $googleUser->refreshToken;
            }

            $updateEmail = ['email' => $googleUser->email];

            $user->update($tokenFields, $updateEmail);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect('/dashboard')->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect('/')->with('error', 'Google sign-in could not be completed. Please try again.');
        }
    });
});
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/{category}/posts', [CategoryController::class, 'posts'])->name('categories_posts');
    Route::get('/categories/add', [CategoryController::class, 'create'])->name('add_categories');
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('edit_categories');
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::get('users/{user}', [UserController::class, 'index'])->name('user_profile');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('edit_user_profile');
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);


    Route::get('/posts/add', [PostController::class, 'create'])->name('add_posts');
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/moderation', [PostController::class, 'moderation'])->name('mod_posts');
    Route::put('/posts/{post}/approve', [PostController::class, 'approve'])->name('approve_posts');
    Route::get('/posts/{post}', [PostController::class, 'index'])->name('posts');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('edit_posts');
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    Route::post('logout', Logout::class)->name('logout');
});
