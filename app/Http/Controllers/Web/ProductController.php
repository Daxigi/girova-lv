<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Type;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $types = Type::all();

        return Inertia::render('Products/Create', [
            'categories' => $categories,
            'types' => $types,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('home')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $products = Product::with(['category', 'type'])->latest()->get(); // Fetch products, newest first
        return Inertia::render('Products/Dashboard', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/Create', [
            'product' => $product,
            'categories' => Category::all(),
            'types' => Type::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.dashboard')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function softDestroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.dashboard')->with('success', 'Producto dado de baja exitosamente.');
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDestroy(Product $product)
    {
        $product->forceDelete();

        return redirect()->route('products.dashboard')->with('success', 'Producto eliminado permanentemente.');
    }
}
