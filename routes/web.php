<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductController as WebProductController;
use App\Http\Controllers\Api\ProductController;
use Inertia\Inertia;
use App\Models\Product;

Route::get('/', function () {
    $products = Product::all();
    return Inertia::render('Welcome', [
        'products' => $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'image_url' => $product->imageUrl,
            ];
        }),
    ]);
})->name('home');

// Route::get('/', function(){
//     return Inertia::render('tecnologies');})->name('test');


Route::get('/products/dashboard', [WebProductController::class, 'dashboard'])->name('products.dashboard');
Route::get('/products/create', [WebProductController::class, 'create'])->name('products.create');
Route::post('/products', [WebProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [WebProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [WebProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [WebProductController::class, 'softDestroy'])->name('products.destroy');
Route::delete('/products/{product}/force', [WebProductController::class, 'forceDestroy'])->name('products.force-destroy');