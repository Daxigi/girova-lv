<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductController as WebProductController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Auth\AuthController;
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
                'stock' => $product->stock, // ← Agregar stock
            ];
        }),
    ]);
})->name('home');

// Route::get('/', function(){
//     return Inertia::render('tecnologies');})->name('test');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas públicas de registro
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Usuarios (solo edición)
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Checkout y Órdenes
    Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my-orders');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
});

// Rutas protegidas por rol admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Productos - Solo admin
    Route::get('/products/dashboard', [WebProductController::class, 'dashboard'])->name('products.dashboard');
    Route::get('/products/create', [WebProductController::class, 'create'])->name('products.create');
    Route::post('/products', [WebProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [WebProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [WebProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [WebProductController::class, 'softDestroy'])->name('products.destroy');
    Route::delete('/products/{product}/force', [WebProductController::class, 'forceDestroy'])->name('products.force-destroy');
});