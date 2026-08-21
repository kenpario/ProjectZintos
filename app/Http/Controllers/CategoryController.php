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
            ->with('posts')
            ->when($categoryName, function ($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
