<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->with(['products' => function($query) {
                $query->where('is_visible', true);
            }])
            ->firstOrFail();

        return Inertia::render('Product/Category', [
            'category' => $category,
            'products' => $category->products,
        ]);
    }
}
