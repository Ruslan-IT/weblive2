<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::select('id', 'title', 'slug', 'photo')
            ->take(6)
            ->get();

        return Inertia::render('Product/Category', [
            'categories' => $categories,
            // Пустые данные при первой загрузке
            'products' => [],
            'currentCategory' => null,
        ]);
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::where('category_id', $category->id)
            ->where('is_visible', true)
            ->select('id', 'title', 'slug', 'price', 'old_price', 'photo', 'is_featured', 'is_hit', 'is_sale')
            ->get();

        $categories = Category::select('id', 'title', 'slug', 'photo')
            ->take(6)
            ->get();

        return Inertia::render('Product/Category', [
            'categories' => $categories,
            'products' => $products,
            'currentCategory' => $category,
        ]);
    }

    public function showProduct($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_visible', true)
            ->with('category') // Загружаем связанную категорию
            ->firstOrFail();

        return Inertia::render('Product/Show', [
            'product' => $product,
        ]);
    }
}
