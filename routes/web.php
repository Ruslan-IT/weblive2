<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/oferta', [OfertaController::class, 'index'])->name('oferta');

Route::get('/{slug}', [CategoryController::class, 'show'])->name('categories.show');

require __DIR__.'/auth.php';
