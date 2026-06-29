<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): Response
    {
        Gate::authorize('manage_categories');

        $categories = Category::query()
            ->withCount('assets')
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category): array => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'assets_count' => $category->assets_count,
            ])
            ->all();

        return Inertia::render('categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage_categories');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        Category::create($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category created.')]);

        return to_route('categories.index');
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        Gate::authorize('manage_categories');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,'.$category->id],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category updated.')]);

        return to_route('categories.index');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('manage_categories');

        $category->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category deleted.')]);

        return to_route('categories.index');
    }
}
