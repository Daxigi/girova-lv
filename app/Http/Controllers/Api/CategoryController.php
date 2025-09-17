<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\CategoryService;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        try
        {
            $categorys = $this->categoryService->getAllCategorys();

            return response()->json($categorys, 200);
        }
        catch (Exception $e)
        {
            return response()->json([
                'message' => 'Ocurrio un error al obtener las categorias.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        try
        {
            $category = $this->categoryService->createCategory($request->validated());

            return response()->json($category, 201);
        }
        catch(Exception $e)
        {
            return response()->json([
                'message' => 'Error al crear una categoria nueva.',
                'error' => $e->getMessage()
            ],500);
        }
    }

    public function show(string $id)
    {
        try
        {
            $category = $this->categoryService->getCategoryById((int)$id);

            if(!$category)
            {
                return response()->json([
                    'message'=>'No existe la categoria.'
                ], 404);
            }

            return response()->json($category, 200);
        }
        catch(Exception $e)
        {
            return response()->json([
                'message' => 'Error al encontrar la categoria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(StoreCategoryRequest $request, string $id)
    {
        try
        {
            $updated = $this->categoryService->updateCategory((int)$id, $request->validated());

            if(!$updated)
            {
                return response()->json([
                    'message'=> 'Producto no encontrado para actualizar',
                ], 404);
            }
            
            $category = $this->categoryService->getCategoryById((int)$id);

            return response()->json($category, 200);
        }
        catch(Exception $e)
        {
            return response()->json([
                'message'=> 'Error al actualizar la categoria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(string $id)
    {
        try
        {
            $deleted = $this->categoryService->deleteCategory((int)$id);

            if(!$deleted)
            {
                return response()->json(['message' => 'No se encontro una categoria.', 404]);
            }

            return response()->json(null, 204);
        }
        catch(Exception $e)
        {
            return response()->json([
                'message' => 'Ocurrio un error al eliminar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}