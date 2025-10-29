<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Policies\ProductPolicy;
use App\Models\Category;
use App\Models\Type;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{

    public function showProducts(){
        // Obtener todos los productos con sus relaciones
        $products = Product::with(['category', 'type'])->get();

        // Obtener todas las categorías y tipos para los filtros
        $categories = Category::all();
        $types = Type::all();

        return Inertia::render('Products/Products', [
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'image_url' => $product->imageUrl,
                    'stock' => $product->stock,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                    ] : null,
                    'type' => $product->type ? [
                        'id' => $product->type->id,
                        'name' => $product->type->name,
                    ] : null,
                ];
            }),
            'categories' => $categories,
            'types' => $types,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Product::class);

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
        $this->authorize('create', Product::class);

        Product::create($request->validated());

        return redirect()->route('home')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $this->authorize('viewAny', Product::class);

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
        $this->authorize('update', Product::class);

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
        $this->authorize('update', Product::class);

        $product->update($request->validated());

        return redirect()->route('products.dashboard')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function softDestroy(Product $product)
    {
        $this->authorize('delete', Product::class);

        $product->delete();

        return redirect()->route('products.dashboard')->with('success', 'Producto dado de baja exitosamente.');
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDestroy(Product $product)
    {
        $this->authorize('delete', Product::class);

        $product->forceDelete();

        return redirect()->route('products.dashboard')->with('success', 'Producto eliminado permanentemente.');
    }
}
