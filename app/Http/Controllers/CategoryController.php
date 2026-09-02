<?php

namespace App\Http\Controllers;

use App\Models\Post_Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categoryName = $request->query('category');

        $categories = Post_Category::query()
            ->when($categoryName, function ($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })
            ->withCount(['posts' => function ($query) {
                $query->where('is_approved', true);
            }])
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $all_posts = Post::with(['user', 'category'])->latest()->take(20)->get()->where('is_approved', true);

        return view('categories.index', ['categories' => $categories, 'all_posts' => $all_posts]);
    }

    public function posts(Post_Category $category)
    {
        $posts = $category->posts()
            ->with('user')
            ->latest()
            ->where('is_approved', true)
            ->paginate(10)
            ->withQueryString();

        return view('categories.posts', ['category' => $category, 'posts' => $posts]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->group?->is_admin) {
            return view('categories.create');
        } else {
            abort(403, 'Unauthorized Action!');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        return view('categories.edit', ['category' => $category]);
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
