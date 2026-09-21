<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Post_Category;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;

class PostController extends Controller
{
    public function index(Post $post)
    {
        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin || $post->is_approved)) {
            abort(403, 'Unauthorized Action!');
        }

        if (Auth::user() && $post->is_approved) {
            View::firstOrCreate([
                'post_id' => $post->id,
                'user_id' => Auth::user()->id,
            ]);
        }

        $post->load(['user', 'category']);

        $post_comments = Comment::query()
            ->with('user')
            ->where('post_id', $post->id)
            ->latest()
            ->get();

        $post_likes = Like::query()
            ->with('user')
            ->where('post_id', $post->id)
            ->get();

        $post_views = View::query()
            ->with('user')
            ->where('post_id', $post->id)
            ->get();

        $total_likes = Like::query()->whereIn('post_id', Post::query()->where('user_id', $post->user->id)->pluck('id'))->count();

        $total_posts = Post::query()->where('is_approved', 'true')->pluck('id')->count();

        return view('posts.index', ['post' => $post, 'post_comments' => $post_comments, 'post_likes' => $post_likes, 'post_views' => $post_views, 'total_likes' => $total_likes, 'total_posts' => $total_posts]);
    }

    public function moderation()
    {
        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)) {
            abort(403, 'Unauthorized Action!');
        }
        $all_unapproved_posts = Post::query()
            ->with(['user', 'category'])
            ->where('is_approved', false)
            ->paginate(20);

        return view('posts.moderation', ['all_unapproved_posts' => $all_unapproved_posts]);
    }

    public function approve(Post $post)
    {
        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)) {
            abort(403, 'Unauthorized Action!');
        }

        $post->update([
            'is_approved' => true,
        ]);

        return redirect()->route('mod_posts', ['post' => $post])
            ->with('success', 'The post has been approved!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->group?->is_admin) {
            $categories = Post_Category::query()->get();
        } else {
            $categories = Post_Category::query()
                ->where('can_comment', true)
                ->get();
        }
        return view('posts.create', ['categories' => $categories, 'backUrl' => url()->previous()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $formFields = $request->validate(
            [
                'title' => 'required|string|max:50|min:5',
                'message' => 'required|string|max:8000|min:5',
                'post_category_id' => 'required|integer|exists:post_categories,id',
                'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4|max:5120',
            ],
            [
                'title.required' => 'Please write a title!',
                'name.max' => 'Name must be 50 characters or less.',
                'message.required' => 'Please write a message!',
                'message.max' => 'Message must be 8000 characters or less.',
                'post_category_id.required' => 'Please select a category!',
                'media.mimes' => 'The media must be a JPG, PNG, GIF or MP4 file.',
                'media.max' => 'The media must be 5 MB or smaller.',
                'media.uploaded' => 'The media must be 5 MB or smaller.',

            ]
        );

        if ($request->hasFile('media')) {
            $formFields['media'] = $request->file('media')->store('media', 'public');
        } else {
            unset($formFields['media']);
        }

        if (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin) {
            $formFields['is_approved'] = 1;
        }

        $formFields['user_id'] = Auth::id();

        $formFields['message'] = Purifier::clean($formFields['message']);

        Post::create($formFields);

        return redirect('/categories')->with('success', 'Your Post has been added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if (! (Auth::user()->id === $post->user_id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)) {
            abort(403, 'Unauthorized Action!');
        }

        $categories = Post_Category::all();

        return view('posts.edit', ['post' => $post, 'categories' => $categories, 'backUrl' => url()->previous()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (! (Auth::user()->id === $post->user_id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)) {
            abort(403, 'Unauthorized Action!');
        }

        $media = $request->file('media');

        $formFields = $request->validate(
            [
                'title' => 'required|string|max:50|min:5',
                'message' => 'required|string|max:8000|min:5',
                'post_category_id' => 'required|integer|exists:post_categories,id',
                'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4|max:5120',
            ],
            [
                'title.required' => 'Please write a title!',
                'name.max' => 'Name must be 50 characters or less.',
                'message.required' => 'Please write a message!',
                'message.max' => 'Message must be 8000 characters or less.',
                'post_category_id.required' => 'Please select a category!',
                'media.mimes' => 'The media must be a JPG, PNG, GIF or MP4 file.',
                'media.max' => 'The media must be 5 MB or smaller.',
                'media.uploaded' => 'The media must be 5 MB or smaller.',

            ]
        );

        if (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin) {
            $formFields['is_approved'] = 1;
        } else {
            $formFields['is_approved'] = 0;
        }

        $formFields['message'] = Purifier::clean($formFields['message']);

        $newMedia = null;
        $oldMedia = $post->media;

        if ($media) {
            $newMedia = $media->store('media', 'public');
            $formFields['media'] = $newMedia;
        }

        try {
            $post->update($formFields);
        } catch (\Throwable $exception) {
            if ($newMedia) {
                Storage::disk('public')->delete($newMedia);
            }

            throw $exception;
        }

        if ($oldMedia && $newMedia) {
            Storage::disk('public')->delete($oldMedia);
        }

        return redirect()->route('posts', ['post' => $post])
            ->with('success', 'The post has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (! (Auth::user()->id === $post->user_id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)) {
            abort(403, 'Unauthorized Action!');
        }

        if ($post->media) {
            Storage::disk('public')->delete($post->media);
        }

        $post->delete();

        return redirect('/dashboard')->with('success', 'Your post has been deleted!');
    }
}
