<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function index()
    {
        //
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
    public function store(Request $request, Post $post)
    {
        if (! $post->is_approved) {
            abort(404);
        }

        $formFields = $request->validate([]);

        $formFields['user_id'] = Auth::user()->id;

        $formFields['post_id'] = $post->id;

        Like::create($formFields);

        return redirect()->route('posts', ['post' => $post])
            ->with('success', 'You liked this post!');
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
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like)
    {
        if (Auth::user()->id !== $like->user_id) {
            abort(403);
        }

        $postId = $like->post_id;

        $like->delete();

        return redirect()->route('posts', ['post' => $postId])
            ->with('success', 'Your took your like back!');
    }
}
