<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function index(User $user)
    {

        $user_posts = $user->posts()
            ->with(['user', 'category'])
            ->latest()
            ->where('is_approved', true)
            ->paginate(10);

        $post_likes = Like::query()
            ->with('user')
            ->whereIn('post_id', $user->posts()->pluck('id'))
            ->get();

        return view('users.index', ['user' => $user, 'user_posts' => $user_posts, 'post_likes' => $post_likes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (! (Auth::user()->id === $user->id || Auth::user()->group?->is_admin)) {
            abort(403, 'Unauthorized Action!');
        }
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (! (Auth::user()->id === $user->id || Auth::user()->group?->is_admin)) {
            abort(403, 'Unauthorized Action!');
        }

        $avatar = $request->file('avatar');

        $formFields = $request->validate(
            [
                'name' => 'required|string|max:30|min:5',
                'avatar' => 'nullable|file|image|mimes:jpg,jpeg,png,gif|max:2048',
                'bio' => 'nullable|string|max:100|min:5',
            ],
            [
                'name.required' => 'Please write a name!',
                'name.max' => 'Name must be 30 characters or less.',
                'bio.max' => 'Biography must be 100 characters or less.',
                'avatar.mimes' => 'The avatar must be a JPG, PNG or GIF image.',
                'avatar.max' => 'The avatar must be 2 MB or smaller.',
                'avatar.uploaded' => 'The avatar must be 2 MB or smaller.',
            ]
        );

        $newAvatar = null;
        $oldAvatar = $user->avatar;

        if ($avatar) {
            $newAvatar = $avatar->store('avatars', 'public');
            $formFields['avatar'] = $newAvatar;
        }

        try {
            $user->update($formFields);
        } catch (\Throwable $exception) {
            if ($newAvatar) {
                Storage::disk('public')->delete($newAvatar);
            }

            throw $exception;
        }

        if ($oldAvatar && $newAvatar) {
            Storage::disk('public')->delete($oldAvatar);
        }

        return redirect()->route('user_profile', ['user' => $user])
            ->with('success', 'Your information has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (! (Auth::user()->id === $user->id || Auth::user()->group?->is_admin)) {
            abort(403, 'Unauthorized Action!');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        foreach ($user->posts as $post) {
            if ($post->media) {
                Storage::disk('public')->delete($post->media);
            }
        }

        User::destroy($user->id);

        return redirect('/')->with('success', 'Your account has been deleted!');
    }
}
