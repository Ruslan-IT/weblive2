<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function index()
    {


        $products = Product::all();

        return Inertia::render('Index', [

            'products' => $products,
        ]);


    }
}
