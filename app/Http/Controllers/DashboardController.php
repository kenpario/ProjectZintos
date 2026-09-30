<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Post_Category;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $latest_posts = Post::with(['user', 'category', 'subcategory'])
            ->where('is_approved', true)
            ->latest()
            ->take(5)
            ->get();

        $hot_topics = Post::with(['user', 'category', 'subcategory'])
            ->withCount('like')
            ->where('is_approved', true)
            ->orderByDesc('like_count')
            ->take(5)
            ->get();

        $all_posts = Post::with(['user', 'category', 'subcategory'])
            ->where('is_approved', true)
            ->where('is_pinned', false)
            ->latest()
            ->take(5)
            ->get();

        $all_pinned_posts = Post::with(['user', 'category', 'subcategory'])
            ->where('is_approved', true)
            ->where('is_pinned', true)
            ->get();

        $statistics_posts = Post::with(['user', 'category', 'subcategory'])
            ->where('is_approved', true)
            ->count();

        $post_categories = Post_Category::all();

        $post_subcategories = Subcategory::all();

        $post_likes = Like::with(['user', 'post'])->get();

        $post_views = View::with(['user', 'post'])->get();

        $users = User::all();

        return view('dashboard', ['latest_posts' => $latest_posts, 'hot_topics' => $hot_topics, 'post_categories' => $post_categories, 'post_subcategories' => $post_subcategories, 'all_posts' => $all_posts, 'post_likes' => $post_likes, 'post_views' => $post_views, 'users' => $users, 'all_pinned_posts' => $all_pinned_posts, 'statistics_posts' => $statistics_posts]);
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
