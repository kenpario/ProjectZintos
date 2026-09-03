<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
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
        $formFields = $request->validate(
            [
                'message' => 'required|string|max:8000|min:5',
            ],
            [
                'message.required' => 'Please write a message!',
                'message.max' => 'Name must be 8000 characters or less.',

            ]
        );

        $formFields['user_id'] = Auth::user()->id;

        $formFields['post_id'] = $post->id;

        Comment::create($formFields);

        return redirect()->route('posts', ['post' => $post])
            ->with('success', 'Your comment has been posted!');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
