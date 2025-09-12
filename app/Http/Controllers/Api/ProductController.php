<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Exception;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Type;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Consider eager loading relationships if they are used in the response
            // $products = Product::with(['category', 'type'])->get();
            $products = Product::all();

            return response()->json($products, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error al obtener los productos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $product = Product::create($request->validated());

            return redirect('/products/create')->with('success', 'Producto creado exitosamente!');
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error al crear el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * NOTE: This method is returning an Inertia view, which is not typical for an API controller.
     * Consider moving this to a dedicated web controller (e.g., ProductController under App\Http\Controllers\Web).
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
