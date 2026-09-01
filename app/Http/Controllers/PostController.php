<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Post_Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Post $post)
    {
        return view('posts.index', ['post' => $post]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Post_Category::all();

        return view('posts.create', ['categories' => $categories]);
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
                'post_category_id' => 'required',
                'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4|max:2048',
            ],
            [
                'title.required' => 'Please write a title!',
                'name.max' => 'Name must be 50 characters or less.',
                'message.required' => 'Please write a message!',
                'message.max' => 'Message must be 8000 characters or less.',
                'post_category_id.required' => 'Please select a category!',
                'media.mimes' => 'The media must be a JPG, PNG, GIF or MP4 file.',
                'media.max' => 'The media must be 2 MB or smaller.',
                'media.uploaded' => 'The media must be 2 MB or smaller.',

            ]
        );

        if ($request->hasFile('media')) {
            $formFields['media'] = $request->file('media')->store('media', 'public');
        } else {
            unset($formFields['media']);
        }

        $formFields['user_id'] = Auth::id();

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
        if (! (Auth::user()->id === $post->user_id || Auth::user()->group?->is_admin)) {
            abort(403, 'Unauthorized Action!');
        }

        $categories = Post_Category::all();

        return view('posts.edit', ['post' => $post, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (! (Auth::user()->id === $post->user_id || Auth::user()->group?->is_admin)) {
            abort(403, 'Unauthorized Action!');
        }

        $media = $request->file('media');

        $formFields = $request->validate(
            [
                'title' => 'required|string|max:50|min:5',
                'message' => 'required|string|max:8000|min:5',
                'post_category_id' => 'required',
                'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4|max:2048',
            ],
            [
                'title.required' => 'Please write a title!',
                'name.max' => 'Name must be 50 characters or less.',
                'message.required' => 'Please write a message!',
                'message.max' => 'Message must be 8000 characters or less.',
                'post_category_id.required' => 'Please select a category!',
                'media.mimes' => 'The media must be a JPG, PNG, GIF or MP4 file.',
                'media.max' => 'The media must be 2 MB or smaller.',
                'media.uploaded' => 'The media must be 2 MB or smaller.',

            ]
        );

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
        if (! (Auth::user()->id === $post->user_id || Auth::user()->group?->is_admin)) {
            abort(403, 'Unauthorized Action!');
        }

        $post->delete();

        return redirect('/dashboard')->with('success', 'Your post has been deleted!');
    }
}
