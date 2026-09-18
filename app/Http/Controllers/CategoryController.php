<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post_Category;
use App\Models\Post;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();

        $categories = Post_Category::query()
            ->orderBy('id', 'asc')
            ->paginate(5)
            ->withQueryString();

        $all_posts = Post::with(['user', 'category'])
            ->where('is_approved', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->whereAny(
                    ['title', 'message'],
                    'ilike',
                    "%{$search}%"
                );
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $post_likes = Like::with(['user', 'post'])
            ->get();

        $post_views = View::with(['user', 'post'])
            ->get();


        return view('categories.index', ['categories' => $categories, 'all_posts' => $all_posts, 'post_likes' => $post_likes, 'post_views' => $post_views]);
    }

    public function posts(Request $request, Post_Category $category)
    {
        $search = $request->string('search')->trim()->toString();

        $posts = $category->posts()
            ->with('user')
            ->where('is_approved', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->whereAny(
                    ['title', 'message'],
                    'ilike',
                    "%{$search}%"
                );
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $post_likes = Like::with(['user', 'post'])
            ->get();

        $post_views = View::with(['user', 'post'])
            ->get();

        return view('categories.posts', ['category' => $category, 'posts' => $posts, 'post_likes' => $post_likes, 'post_views' => $post_views]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->group?->is_admin) {
            return view('categories.create', [
                'backUrl' => url()->previous(),
            ]);
        } else {
            abort(403, 'Unauthorized Action!');
        }
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
                'can_comment' => 'required|boolean'
            ],
            [
                'name.required' => 'Please write a name!',
                'name.max' => 'Name must be 30 characters or less.',
                'description.required' => 'Please write a description!',
                'description.max' => 'Name must be 30 characters or less.'

            ]
        );

        $formFields['user_id'] = Auth::user()->id;

        Post_Category::create($formFields);

        return redirect('/categories')->with('success', 'Your Category has been added!');
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
    public function edit(Post_Category $category)
    {
        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }
        return view('categories.edit', [
            'category' => $category,
            'backUrl' => url()->previous(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post_Category $category)
    {
        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }
        $formFields = $request->validate(
            [
                'name' => 'required|string|max:30|min:5',
                'description' => 'required|string|max:30|min:5',
                'can_comment' => 'required|boolean'
            ],
            [
                'name.required' => 'Please write a name!',
                'name.max' => 'Name must be 30 characters or less.',
                'description.required' => 'Please write a description!',
                'description.max' => 'Name must be 30 characters or less.'

            ]
        );

        $category->update($formFields);

        return redirect('/categories')->with('success', 'Your Category has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post_Category $category)
    {
        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }
        $category->delete();

        return redirect('/categories')->with('success', 'Your Category has been deleted!');
    }
}
