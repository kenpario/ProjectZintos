<?php

namespace App\Http\Controllers;

use App\Models\Post_Category;
use App\Models\Post;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();

        $categories = Post_Category::query()
            ->orderBy('id', 'asc')
            ->paginate(5)
            ->withQueryString();

        $subcategories = Subcategory::query()
            ->orderBy('id', 'asc')
            ->get();

        $all_posts = Post::with(['user', 'category', 'subcategory'])
            ->withCount(['like', 'view'])
            ->where('is_approved', true)
            ->where('is_pinned', false)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereAny(['title', 'message'], 'ilike', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'ilike', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $all_pinned_posts = Post::with(['user', 'category', 'subcategory'])
            ->withCount(['like', 'view'])
            ->where('is_approved', true)
            ->where('is_pinned', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereAny(['title', 'message'], 'ilike', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'ilike', "%{$search}%");
                        });
                });
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('categories.index', ['categories' => $categories, 'subcategories' => $subcategories, 'all_posts' => $all_posts, 'all_pinned_posts' => $all_pinned_posts]);
    }

    public function posts(Request $request, Post_Category $category)
    {
        $search = $request->string('search')->trim()->toString();

        $subcategories = Subcategory::query()
            ->orderBy('id', 'asc')
            ->get();

        $posts = $category->posts()
            ->with('user')
            ->withCount(['like', 'view'])
            ->where('is_approved', true)
            ->where('is_pinned', false)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereAny(['title', 'message'], 'ilike', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'ilike', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $pinned_posts = Post::with('user')
            ->withCount(['like', 'view'])
            ->where('is_approved', true)
            ->where('is_pinned', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereAny(['title', 'message'], 'ilike', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'ilike', "%{$search}%");
                        });
                });
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('categories.posts', ['category' => $category, 'subcategories' => $subcategories, 'posts' => $posts, 'pinned_posts' => $pinned_posts]);
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
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! Auth::user()->group?->is_admin) {
            abort(404);
        }

        $formFields = $request->validate(
            [
                'name' => 'required|string|max:30|min:5',
                'description' => 'required|string|max:50|min:5',
                'can_comment' => 'required|boolean'
            ],
            [
                'name.required' => 'Please write a name!',
                'name.max' => 'Name must be 30 characters or less.',
                'description.required' => 'Please write a description!',
                'description.max' => 'Description must be 50 characters or less.'

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
            abort(404);
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
            abort(404);
        }
        $formFields = $request->validate(
            [
                'name' => 'required|string|max:30|min:5',
                'description' => 'required|string|max:50|min:5',
                'can_comment' => 'required|boolean'
            ],
            [
                'name.required' => 'Please write a name!',
                'name.max' => 'Name must be 30 characters or less.',
                'description.required' => 'Please write a description!',
                'description.max' => 'Description must be 50 characters or less.'

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
            abort(404);
        }

        $category->posts()
            ->whereNotNull('media')
            ->chunkById(100, function ($posts): void {
                foreach ($posts as $post) {
                    Storage::disk('public')->delete($post->media);
                }
            });

        $category->delete();

        return redirect('/categories')->with('success', 'Your Category has been deleted!');
    }
}
