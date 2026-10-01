<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Post_Category;
use App\Models\Subcategory;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;

class PostController extends Controller
{
    public function index(Post $post)
    {
        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin || $post->is_approved)) {
            abort(404);
        }

        if (Auth::user() && $post->is_approved) {
            View::firstOrCreate([
                'post_id' => $post->id,
                'user_id' => Auth::user()->id,
            ]);
        }

        $post->load(['user', 'category']);

        $isModerator = Auth::user()->group?->is_mod || Auth::user()->group?->is_admin;

        $post_comments = Comment::query()
            ->with('user')
            ->where('post_id', $post->id)
            ->when(! $isModerator, fn ($query) => $query->where('is_approved', true))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $post_likes = Like::query()
            ->with('user')
            ->where('post_id', $post->id)
            ->get();

        $post_views = View::query()
            ->with('user')
            ->where('post_id', $post->id)
            ->get();

        $total_likes = Like::query()
            ->whereHas('post', function ($query) use ($post) {
                $query->where('user_id', $post->user_id)
                    ->where('is_approved', true);
            })
            ->count();

        $total_posts = $post->user->posts()
            ->where('is_approved', true)
            ->count();

        return view('posts.index', ['post' => $post, 'post_comments' => $post_comments, 'post_likes' => $post_likes, 'post_views' => $post_views, 'total_likes' => $total_likes, 'total_posts' => $total_posts]);
    }

    public function moderation(Request $request)
    {
        $search = $request->string('search')->trim()->toString();

        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)) {
            abort(404);
        }
        $all_unapproved_posts = Post::query()
            ->with(['user', 'category'])
            ->where('is_approved', false)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereAny(['title', 'message'], 'ilike', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'ilike', "%{$search}%");
                        });
                });
            })
            ->paginate(20)
            ->withQueryString();

        return view('posts.moderation', ['all_unapproved_posts' => $all_unapproved_posts]);
    }

    public function approve(Post $post)
    {
        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)) {
            abort(404);
        }

        $post->update([
            'is_approved' => true,
        ]);

        return redirect()->route('mod_posts', ['post' => $post])
            ->with('success', 'This thread has been approved!');
    }

    public function pin(Post $post)
    {
        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)) {
            abort(404);
        }

        $post->update([
            'is_pinned' => true,
        ]);

        return redirect()->route('posts', ['post' => $post])
            ->with('success', 'This thread has been pinned!');
    }

    public function unpin(Post $post)
    {
        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)) {
            abort(404);
        }

        $post->update([
            'is_pinned' => false,
        ]);

        return redirect()->route('posts', ['post' => $post])
            ->with('success', 'This thread has been unpinned!');
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

        $subcategories = collect();

        $allsubcategories = Subcategory::query()
            ->whereIn('post_category_id', $categories->pluck('id'))
            ->get();

        return view('posts.create', ['categories' => $categories, 'subcategories' => $subcategories, 'allsubcategories' => $allsubcategories, 'backUrl' => url()->previous()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $categoryRule = Rule::exists('post_categories', 'id');

        if (! Auth::user()->group?->is_admin) {
            $categoryRule->where('can_comment', true);
        }

        $formFields = $request->validate(
            [
                'title' => 'required|string|max:50|min:5',
                'message' => 'required|string|max:8000|min:5',
                'post_category_id' => ['required', 'integer', $categoryRule],
                'post_subcategory_id' => ['required', 'integer', Rule::exists('post_subcategories', 'id')->where('post_category_id', $request->post_category_id),],
                'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4|max:5120',
            ],
            [
                'title.required' => 'Please write a title!',
                'tile.max' => 'Title must be 50 characters or less.',
                'message.required' => 'Please write a message!',
                'message.max' => 'Message must be 8000 characters or less.',
                'post_category_id.required' => 'Please select a category!',
                'post_subcategory_id.required' => 'Please select a subcategory!',
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

        if (Auth::user()->group?->is_admin || Auth::user()->group?->is_mod) {
            return redirect('/categories')->with('success', 'Your thread has been added!');
        }
        return redirect('/categories')->with('success', 'Your thread is waiting for approval!');
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
            abort(403);
        }

        if (Auth::user()->group?->is_admin) {
            $categories = Post_Category::query()->get();
        } else {
            $categories = Post_Category::query()
                ->where('can_comment', true)
                ->get();
        }

        $allsubcategories = Subcategory::query()
            ->whereIn('post_category_id', $categories->pluck('id'))
            ->get();

        $subcategories = Subcategory::query()->where('post_category_id', $post->post_category_id)->get();

        return view('posts.edit', ['post' => $post, 'categories' => $categories, 'subcategories' => $subcategories, 'allsubcategories' => $allsubcategories, 'backUrl' => url()->previous()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (! (Auth::user()->id === $post->user_id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)) {
            abort(403);
        }

        $categoryRule = Rule::exists('post_categories', 'id');

        if (! Auth::user()->group?->is_admin) {
            $categoryRule->where('can_comment', true);
        }

        $media = $request->file('media');

        $formFields = $request->validate(
            [
                'title' => 'required|string|max:50|min:5',
                'message' => 'required|string|max:8000|min:5',
                'post_category_id' => ['required', 'integer', $categoryRule],
                'post_subcategory_id' => ['required', 'integer', Rule::exists('post_subcategories', 'id')->where('post_category_id', $request->post_category_id),],
                'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4|max:5120',
            ],
            [
                'title.required' => 'Please write a title!',
                'title.max' => 'Name must be 50 characters or less.',
                'message.required' => 'Please write a message!',
                'message.max' => 'Message must be 8000 characters or less.',
                'post_category_id.required' => 'Please select a category!',
                'post_subcategory_id.required' => 'Please select a subcategory!',
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


        if (Auth::user()->group?->is_admin || Auth::user()->group?->is_mod) {
            return redirect()->route('posts', ['post' => $post])
                ->with('success', 'The thread has been updated!');
        }
        return redirect('/categories')->with('success', 'Your thread has been updated and is waiting for approval!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (! (Auth::user()->id === $post->user_id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)) {
            abort(403);
        }

        if ($post->media) {
            Storage::disk('public')->delete($post->media);
        }

        $post->delete();

        return redirect('/dashboard')->with('success', 'Your thread has been deleted!');
    }
}
