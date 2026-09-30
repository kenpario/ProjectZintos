<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post_Category;
use App\Models\Post;
use App\Models\Subcategory;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        // $search = $request->string('search')->trim()->toString();

        // $categories = Post_Category::query()
        //     ->orderBy('id', 'asc')
        //     ->paginate(5)
        //     ->withQueryString();

        // $all_posts = Post::with(['user', 'category'])
        //     ->where('is_approved', true)
        //     ->where('is_pinned', false)
        //     ->when($search !== '', function ($query) use ($search) {
        //         $query->where(function ($q) use ($search) {
        //             $q->whereAny(['title', 'message'], 'ilike', "%{$search}%")
        //                 ->orWhereHas('user', function ($userQuery) use ($search) {
        //                     $userQuery->where('name', 'ilike', "%{$search}%");
        //                 });
        //         });
        //     })
        //     ->latest()
        //     ->paginate(10)
        //     ->withQueryString();

        // $all_pinned_posts = Post::with(['user', 'category'])
        //     ->where('is_approved', true)
        //     ->where('is_pinned', true)
        //     ->when($search !== '', function ($query) use ($search) {
        //         $query->where(function ($q) use ($search) {
        //             $q->whereAny(['title', 'message'], 'ilike', "%{$search}%")
        //                 ->orWhereHas('user', function ($userQuery) use ($search) {
        //                     $userQuery->where('name', 'ilike', "%{$search}%");
        //                 });
        //         });
        //     })
        //     ->orderBy('id', 'asc')
        //     ->get();

        // $post_likes = Like::with(['user', 'post'])
        //     ->get();

        // $post_views = View::with(['user', 'post'])
        //     ->get();


        // return view('categories.index', ['categories' => $categories, 'all_posts' => $all_posts, 'post_likes' => $post_likes, 'post_views' => $post_views, 'all_pinned_posts' => $all_pinned_posts]);
    }

    public function posts(Request $request, Post_Category $category)
    {
        // $search = $request->string('search')->trim()->toString();

        // $posts = $category->posts()
        //     ->with('user')
        //     ->where('is_approved', true)
        //     ->where('is_pinned', false)
        //     ->when($search !== '', function ($query) use ($search) {
        //         $query->where(function ($q) use ($search) {
        //             $q->whereAny(['title', 'message'], 'ilike', "%{$search}%")
        //                 ->orWhereHas('user', function ($userQuery) use ($search) {
        //                     $userQuery->where('name', 'ilike', "%{$search}%");
        //                 });
        //         });
        //     })
        //     ->latest()
        //     ->paginate(10)
        //     ->withQueryString();

        // $pinned_posts = Post::with('user')
        //     ->where('is_approved', true)
        //     ->where('is_pinned', true)
        //     ->when($search !== '', function ($query) use ($search) {
        //         $query->where(function ($q) use ($search) {
        //             $q->whereAny(['title', 'message'], 'ilike', "%{$search}%")
        //                 ->orWhereHas('user', function ($userQuery) use ($search) {
        //                     $userQuery->where('name', 'ilike', "%{$search}%");
        //                 });
        //         });
        //     })
        //     ->orderBy('id', 'asc')
        //     ->get();

        // $post_likes = Like::with(['user', 'post'])
        //     ->get();

        // $post_views = View::with(['user', 'post'])
        //     ->get();

        // return view('categories.posts', ['category' => $category, 'posts' => $posts, 'post_likes' => $post_likes, 'post_views' => $post_views, 'pinned_posts' => $pinned_posts]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }

        $categories = Post_Category::query()->get();

        return view('subcategories.create', ['categories' => $categories, 'backUrl' => url()->previous()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }

        $formFields = $request->validate(
            [
                'name' => 'required|string|max:30|min:5',
                'description' => 'required|string|max:30|min:5',
                'post_category_id' => 'required|integer|exists:post_categories,id',
            ],
            [
                'name.required' => 'Please write a name!',
                'name.max' => 'Name must be 30 characters or less.',
                'description.required' => 'Please write a description!',
                'description.max' => 'Name must be 30 characters or less.'

            ]
        );

        $formFields['user_id'] = Auth::user()->id;

        Subcategory::create($formFields);

        return redirect('/dashboard')->with('success', 'Your subcategory has been added!');
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
    public function edit(Subcategory $subcategory)
    {
        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }

        $categories = Post_Category::all();

        return view('subcategories.edit', [
            'subcategory' => $subcategory,
            'backUrl' => url()->previous(),
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }
        $formFields = $request->validate(
            [
                'name' => 'required|string|max:30|min:5',
                'description' => 'required|string|max:30|min:5',
                'post_category_id' => 'required|integer|exists:post_categories,id',
            ],
            [
                'name.required' => 'Please write a name!',
                'name.max' => 'Name must be 30 characters or less.',
                'description.required' => 'Please write a description!',
                'description.max' => 'Name must be 30 characters or less.'

            ]
        );

        $subcategory->update($formFields);

        return redirect('/dashboard')->with('success', 'Your subcategory has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }
        $subcategory->delete();

        return redirect('/dashboard')->with('success', 'Your Subcategory has been deleted!');
    }
}
