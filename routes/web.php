<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ViewController;
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
    })->name('login_google');

    Route::get('/auth/callback', function (Request $request) {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::query()
                ->where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if (! $user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'group_id' => 4,
                    'email_verified_at' => now(),
                ]);
            }

            $tokenFields = ['google_token' => $googleUser->token];

            if ($googleUser->refreshToken !== null) {
                $tokenFields['google_refresh_token'] = $googleUser->refreshToken;
            }

            $user->update([
                ...$tokenFields,
                'google_id' => $googleUser->id,
                'email' => $googleUser->email,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect('/dashboard')->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect('/')->with('error', 'Google sign-in could not be completed. Please try again.');
        }
    });
});
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('/categories/add', [CategoryController::class, 'create'])->name('add_categories');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware(['throttle:5,1']);
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/{category}/threads', [CategoryController::class, 'posts'])->name('categories_posts');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('edit_categories');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware(['throttle:10,1']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware(['throttle:10,1']);

    Route::get('/subcategories/add', [SubcategoryController::class, 'create'])->name('add_subcategories');
    Route::post('/subcategories', [SubcategoryController::class, 'store'])->middleware(['throttle:5,1']);
    Route::get('/subcategories/{subcategory}/edit', [SubcategoryController::class, 'edit'])->name('edit_subcategories');
    Route::put('/subcategories/{subcategory}', [SubcategoryController::class, 'update'])->middleware(['throttle:10,1']);
    Route::delete('/subcategories/{subcategory}', [SubcategoryController::class, 'destroy'])->middleware(['throttle:10,1']);

    Route::get('/subcategories/{subcategory}/threads', [SubcategoryController::class, 'posts'])->name('subcategories_posts');

    Route::get('/users/administration', [UserController::class, 'administration'])->name('user_administration');
    Route::get('/users/team', [UserController::class, 'team'])->name('team');
    Route::get('/users/search', [UserController::class, 'search'])->name('user_search');
    Route::get('users/{user}', [UserController::class, 'index'])->name('user_profile');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('edit_user_profile');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware(['throttle:10,1']);
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware(['throttle:10,1']);

    Route::get('/groups/administration', [GroupController::class, 'administration'])->name('group_administration');
    Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('edit_groups');
    Route::put('/groups/{group}', [GroupController::class, 'update'])->middleware(['throttle:10,1']);


    Route::get('/threads/add', [PostController::class, 'create'])->name('add_posts');
    Route::post('/threads', [PostController::class, 'store'])->middleware(['throttle:5,1']);
    Route::get('/threads/moderation', [PostController::class, 'moderation'])->name('mod_posts');
    Route::put('/threads/{post}/approve', [PostController::class, 'approve'])->name('approve_posts')->middleware(['throttle:10,1']);
    Route::put('/threads/{post}/pin', [PostController::class, 'pin'])->name('pin_posts')->middleware(['throttle:10,1']);
    Route::put('/threads/{post}/unpin', [PostController::class, 'unpin'])->name('unpin_posts')->middleware(['throttle:10,1']);
    Route::get('/threads/{post}', [PostController::class, 'index'])->name('posts');
    Route::get('/threads/{post}/edit', [PostController::class, 'edit'])->name('edit_posts');
    Route::put('/threads/{post}', [PostController::class, 'update'])->middleware(['throttle:10,1']);
    Route::delete('/threads/{post}', [PostController::class, 'destroy'])->middleware(['throttle:10,1']);

    Route::post('/threads/{post}/comment', [CommentController::class, 'store'])->name('comment_posts')->middleware(['throttle:10,1']);
    Route::get('/comments/moderation', [CommentController::class, 'moderation'])->name('mod_comments');
    Route::put('/comments/{comment}/approve', [CommentController::class, 'approve'])->name('approve_comments')->middleware(['throttle:10,1']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware(['throttle:10,1']);
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('edit_comments');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->middleware(['throttle:10,1']);

    Route::post('/threads/{post}/like', [LikeController::class, 'store'])->name('like_posts')->middleware(['throttle:10,1']);
    Route::delete('/likes/{like}', [LikeController::class, 'destroy'])->middleware(['throttle:10,1']);

    Route::post('/threads/{post}/view', [ViewController::class, 'store'])->name('view_posts');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
});

Route::get('/cookies', function () {
    return view('cookies_info');
});

Route::get('/privacy', function () {
    return view('privacy');
});

Route::get('/terms', function () {
    return view('terms');
});
