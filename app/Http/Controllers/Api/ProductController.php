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
        try {
            $product = $this->productService->getProductById((int)$id);

            if (!$product) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }

            return response()->json($product, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error al obtener el producto.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, string $id)
    {
        try {
            $updated = $this->productService->updateProduct((int)$id, $request->validated());

            if (!$updated) {
                return response()->json(['message' => 'Producto no encontrado para actualizar'], 404);
            }

            $product = $this->productService->getProductById((int)$id);
            return response()->json($product, 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error al actualizar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = $this->productService->deleteProduct((int)$id);

            if (!$deleted) {
                return response()->json(['message' => 'Producto no encontrado para eliminar'], 404);
            }

            return response()->json(null, 204);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error al eliminar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
