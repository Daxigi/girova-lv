<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = $this->productService->getAllProducts();

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
            $product = $this->productService->createProduct($request->validated());

            return response()->json($product, 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error al crear el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Implementar usando $this->productService->getProductById($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Implementar usando $this->productService->updateProduct($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Implementar usando $this->productService->deleteProduct($id);
    }
}
