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
        $products = Product::with(['category', 'type'])->get();

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

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);

        Product::create($request->validated());

        return redirect()->route('home')->with('success', 'Producto creado exitosamente.');
    }

    public function dashboard()
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::with(['category', 'type'])->latest()->get(); 
        return Inertia::render('Products/Dashboard', [
            'products' => $products
        ]);
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        return Inertia::render('Products/Create', [
            'product' => $product,
            'categories' => Category::all(),
            'types' => Type::all(),
        ]);
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validated();

        if (empty($data['imageUrl'])) {
            unset($data['imageUrl']);
        }

        $product->update($data);

        return redirect()->route('products.dashboard')->with('success', 'Producto actualizado exitosamente.');
    }

    public function softDestroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('products.dashboard')->with('success', 'Producto dado de baja exitosamente.');
    }

    public function forceDestroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->forceDelete();

        return redirect()->route('products.dashboard')->with('success', 'Producto eliminado permanentemente.');
    }
}
