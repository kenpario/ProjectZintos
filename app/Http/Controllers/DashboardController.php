<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Post_Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $latest_posts = Post::with(['user', 'category'])
            ->where('is_approved', true)
            ->latest()
            ->take(5)
            ->get();

        $hot_topics = Post::with(['user', 'category'])
            ->where('is_approved', true)
            ->orderByDesc('likes')
            ->take(5)
            ->get();

        $all_posts = Post::with(['user', 'category'])
            ->where('is_approved', true)
            ->latest()
            ->take(20)
            ->get();

        $post_categories = Post_Category::all();

        $post_likes = Like::with(['user', 'post'])->get();

        return view('dashboard', ['latest_posts' => $latest_posts, 'hot_topics' => $hot_topics, 'post_categories' => $post_categories, 'all_posts' => $all_posts, 'post_likes' => $post_likes]);
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
