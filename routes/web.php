<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


//Route::get('/', [IndexController::class, 'index'])->name('index');


Route::get('/oferta', [OfertaController::class, 'index'])->name('oferta');
Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy');


Route::get('/', [ProductController::class, 'index'])->name('categories');
Route::get('/', [ProductController::class, 'index'])->name('categories');

// все разделы для лендинга
Route::get('/category/{slug}', [ProductController::class, 'show'])->name('categories.show');

// маршрут для отдельного продукта
Route::get('/product/{slug}', [ProductController::class, 'showProduct'])->name('product.show');

Route::get('/{slug}', [CategoryController::class, 'show'])->name('categories.show');

/** Оплата *********************************************************************************************************************/

//Создание платежа и перенаправление пользователя на страницу оплаты (Робокассы).
Route::post('/pay', [PaymentController::class, 'create'])->name('payment.create');

//куда Робокасса возвращает пользователя после успешной оплаты.
Route::get('/pay/success', [PaymentController::class, 'success'])->name('payment.success');

//куда пользователь попадает, если отменил или не завершил оплату.
Route::get('/pay/fail', [PaymentController::class, 'fail'])->name('payment.fail');

//Технический callback от Робокассы для подтверждения оплаты.
//‼️ Пользователь сюда не попадает, это запрос от сервера Робокассы на твой сайт.
Route::get('/pay/result', [PaymentController::class, 'result'])->name('payment.result');

/***********************************************************************************************************************/


require __DIR__.'/auth.php';
