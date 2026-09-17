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

    public function moderation(Comment $comment)
    {
        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)) {
            abort(403, 'Unauthorized Action!');
        }

        $all_unapproved_comments = Comment::query()
            ->with(['user', 'post'])
            ->where('is_approved', false)
            ->paginate(20);

        return view('comments.moderation', ['all_unapproved_comments' => $all_unapproved_comments]);
    }

    public function approve(Comment $comment)
    {
        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)) {
            abort(403, 'Unauthorized Action!');
        }

        $comment->update([
            'is_approved' => true,
        ]);

        return redirect()->route('mod_comments', ['comment' => $comment])
            ->with('success', 'The comment has been approved!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $isModerator = Auth::user()->group?->is_mod || Auth::user()->group?->is_admin;

        if (! $post->category?->can_comment || (! $post->is_approved && ! $isModerator)) {
            abort(403, 'Unauthorized Action!');
        }

        $formFields = $request->validate(
            [
                'message' => 'required|string|max:8000|min:5',
            ],
            [
                'message.required' => 'Please write a message!',
                'message.max' => 'Name must be 8000 characters or less.',

            ]
        );

        if (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin) {
            $formFields['is_approved'] = 1;
        }

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
    public function edit(Comment $comment)
    {
        if (! (Auth::user()->id === $comment->user_id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)) {
            abort(403, 'Unauthorized Action!');
        }

        return view('comments.edit', ['comment' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {

        if (! (Auth::user()->id === $comment->user_id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)) {
            abort(403, 'Unauthorized Action!');
        }

        $formFields = $request->validate(
            [
                'message' => 'required|string|max:8000|min:5',
            ],
            [
                'message.required' => 'Please write a message!',
                'message.max' => 'Name must be 8000 characters or less.',

            ]
        );

        if (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin) {
            $formFields['is_approved'] = 1;
        } else {
            $formFields['is_approved'] = 0;
        }

        $comment->update($formFields);

        $postId = $comment->post_id;

        return redirect()->route('posts', ['post' => $postId])
            ->with('success', 'Your comment has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (! (Auth::user()->id === $comment->user_id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)) {
            abort(403, 'Unauthorized Action!');
        }

        $postId = $comment->post_id;

        $comment->delete();

        return redirect()->route('posts', ['post' => $postId])
            ->with('success', 'Your comment has been deleted!');
    }
}
