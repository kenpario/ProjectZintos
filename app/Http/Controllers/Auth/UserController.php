<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Like;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

        $post_views = View::query()
            ->with('user')
            ->whereIn('post_id', $user->posts()->pluck('id'))
            ->get();

        $total_likes =  Like::query()->whereIn('post_id', $user_posts->where('user_id', $user->id)->pluck('id'))->count();

        return view('users.index', ['user' => $user, 'user_posts' => $user_posts, 'post_likes' => $post_likes, 'post_views' => $post_views, 'total_likes' => $total_likes]);
    }

    public function administration(Request $request)
    {

        if (! Auth::user()->group?->is_admin) {
            abort(404);
        }

        $search = $request->string('search')->trim()->toString();

        $user_data = User::query()
            ->with('group')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereAny(
                    ['name', 'email'],
                    'ilike',
                    "%{$search}%"
                );
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('users.administration', ['user_data' => $user_data]);
    }

    public function team()
    {
        $team_members = User::query()
            ->with('group')
            ->whereIn('group_id', [1, 2])
            ->orderBy('group_id', 'asc')
            ->get();

        $team_groups = $team_members
            ->groupBy('group_id')
            ->map(fn($group_members): array => [
                'group' => $group_members->first()->group,
                'members' => $group_members,
            ]);

        return view('members.team', ['team_groups' => $team_groups]);
    }

    public function search(Request $request)
    {
        $search = $request->string('search')->trim()->toString();

        $user_data = User::query()
            ->with('group')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereAny(
                    ['name'],
                    'ilike',
                    "%{$search}%"
                );
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('members.search', ['user_data' => $user_data]);
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
            abort(403);
        }

        $groups = Group::where('is_admin', false)->where('is_premium', false)->get();

        return view('users.edit', ['user' => $user, 'groups' => $groups, 'backUrl' => url()->previous()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (! (Auth::user()->id === $user->id || Auth::user()->group?->is_admin)) {
            abort(403);
        }

        $avatar = $request->file('avatar');

        $allowedGroupIds = Group::where('is_admin', false)
            ->where('is_premium', false)
            ->pluck('id');

        $formFields = $request->validate(
            [
                'name' => 'required|string|max:30|min:5',
                'avatar' => 'nullable|file|image|mimes:jpg,jpeg,png,gif|max:2048',
                'bio' => 'nullable|string|max:100|min:5',
                'group_id' => ['nullable', 'integer', Rule::in($allowedGroupIds)],
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
            if (! Auth::user()->group?->is_admin || $user->group?->is_admin) {
                unset($formFields['group_id']);
            }

            if (array_key_exists('group_id', $formFields) && empty($formFields['group_id'])) {
                unset($formFields['group_id']);
            }

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
            abort(403);
        }

        if ($user->group?->is_admin) {
            abort(403);
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
