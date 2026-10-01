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

    public function moderation(Request $request, Comment $comment)
    {
        $search = $request->string('search')->trim()->toString();

        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)) {
            abort(404);
        }

        $all_unapproved_comments = Comment::query()
            ->with(['user', 'post'])
            ->where('is_approved', false)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereAny(['message'], 'ilike', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'ilike', "%{$search}%");
                        });
                });
            })
            ->paginate(20)
            ->withQueryString();

        return view('comments.moderation', ['all_unapproved_comments' => $all_unapproved_comments]);
    }

    public function approve(Comment $comment)
    {
        if (! (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin)) {
            abort(404);
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
            abort(403);
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

        if (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin) {
            return redirect()->route('posts', ['post' => $post])
                ->with('success', 'Your comment has been posted!');
        }
        return redirect()->route('posts', ['post' => $post])
            ->with('success', 'Your comment is waiting for approval!');
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
            abort(403);
        }

        return view('comments.edit', ['comment' => $comment, 'backUrl' => url()->previous()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {

        if (! (Auth::user()->id === $comment->user_id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)) {
            abort(403);
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

        if (Auth::user()->group?->is_mod || Auth::user()->group?->is_admin) {
            return redirect()->route('posts', ['post' => $postId])
                ->with('success', 'Your comment has been posted!');
        }
        return redirect()->route('posts', ['post' => $postId])
            ->with('success', 'Your comment is waiting for approval!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (! (Auth::user()->id === $comment->user_id || Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)) {
            abort(403);
        }

        $postId = $comment->post_id;

        $comment->delete();

        return redirect()->route('posts', ['post' => $postId])
            ->with('success', 'Your comment has been deleted!');
    }
}
