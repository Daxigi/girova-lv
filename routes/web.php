<?php

use App\Http\Controllers\Web\ProductController as WebProductController;
use App\Http\Controllers\Api\ProductController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::get('/products/create', [WebProductController::class, 'create'])->name('products.create');

Route::post('/products', [ProductController::class, 'store'])->name('products.store');