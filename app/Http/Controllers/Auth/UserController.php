<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        try{
            $users = $this->userService->getAllUsers();

            return response()->json($users, 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Ocurrio un error al obtener los usuarios.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try{
            $user = $this->userService->getUserById((int)$id);

            if(!$user){
                return response()->json(['message' => 'Usuario no encontrado.'], 404);
            }

            return response()->json($user, 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Ocurrio un error al obtener el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreUserRequest $request)
    {
        try
        {
            $user = $this->userService->createUser($request->validated());

            return response()->json($user, 200);
        }
        catch(Exception $e)
        {
            return response()->json([
                'message' => 'Ocurrio un error al crear el usuario.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(string $id, StoreUserRequest $request)
    {
        try{
            $updated = $this->userService->updateUser((int)$id, $request->validated());

            if(!$updated)
            {
                return response()->json(['message' => 'Usuario no encontrado para actualizar.']);
            }

            $user = $this->userService->getUserById((int)$id);

            return response()->json($user, 200);
        }
        catch(Exception $e)
        {
            return response()->json([
                'message' => 'Ocurrio un error actualizando al usuario.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try
        {
            $deleted = $this->userService->deleteUser((int)$id);

            if(!$deleted)
            {
                return response()->json(['message' => 'Usuario no encontrado para la eliminacion.'],404);
            }

            return response()->json(null, 204);
        }
        catch(Exception $e)
        {
            return response()->json([
                'message' => 'Error eliminando al usuario.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
