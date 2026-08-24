<?php

namespace App\Http\Controllers;

use App\Models\Post_Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categoryName = $request->query('category');

        $categories = Post_Category::query()
            ->when($categoryName, function ($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();
        $categories->getCollection()->transform(function (Post_Category $category) {
            $category->setRelation(
                'posts',
                $category->posts()
                    ->latest()
                    ->paginate(5, ['*'], 'posts_page_' . $category->id)
                    ->withQueryString()
            );

            return $category;
        });

        return view('categories.index', ['categories' => $categories]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required|string|max:30|min:5',
            'description' => 'required|string|max:30|min:5',
        ]);

        Post_Category::create($formFields);

        return redirect('/categories');
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
        return view('categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post_Category $category)
    {
        $formFields = $request->validate([
            'name' => 'required|string|max:30|min:5',
            'description' => 'required|string|max:30|min:5',
        ]);

        $category->update($formFields);

        return redirect('/categories');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post_Category $category)
    {
        $category->delete();

        return redirect('/categories');
    }
}
