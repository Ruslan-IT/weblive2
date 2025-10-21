<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [IndexController::class, 'index'])->name('index');


Route::get('/oferta', [OfertaController::class, 'index'])->name('oferta');
Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy');


Route::get('/cat', [ProductController::class, 'index'])->name('categories');

// все разделы для лендинга
Route::get('/category/{slug}', [ProductController::class, 'show'])->name('categories.show');

// маршрут для отдельного продукта
Route::get('/product/{slug}', [ProductController::class, 'showProduct'])->name('product.show');

Route::get('/{slug}', [CategoryController::class, 'show'])->name('categories.show');


require __DIR__.'/auth.php';
