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

    public function posts(Request $request, Subcategory $subcategory)
    {
        $search = $request->string('search')->trim()->toString();

        $posts = $subcategory->posts()
            ->with('user')
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
            ->paginate(20)
            ->withQueryString();

        $pinned_posts = Post::with('user')
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

        $post_likes = Like::with(['user', 'post'])
            ->get();

        $post_views = View::with(['user', 'post'])
            ->get();

        return view('subcategories.posts', ['subcategory' => $subcategory, 'posts' => $posts, 'post_likes' => $post_likes, 'post_views' => $post_views, 'pinned_posts' => $pinned_posts]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! Auth::user()->group?->is_admin) {
            abort(404);
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
            abort(404);
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
            abort(404);
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
            abort(404);
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
            abort(404);
        }
        $subcategory->delete();

        return redirect('/dashboard')->with('success', 'Your Subcategory has been deleted!');
    }
}
