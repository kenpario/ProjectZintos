<?php

namespace App\Http\Controllers;

use App\Models\View;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class ViewController extends Controller
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
    public function store(Post $post)
    {
        if (! $post->is_approved) {
            abort(404);
        }

        View::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return redirect()->route('posts', ['post' => $post]);
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
    public function destroy(string $id)
    {
        //
    }
}
