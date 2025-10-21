<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        // Disparar evento de registro para enviar email de verificación
        event(new Registered($user));

        // Loguear automáticamente al usuario
        Auth::login($user);

        return redirect()->route('verification.notice')->with('success', 'Cuenta creada exitosamente. Por favor verifica tu email.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Create', [
            'user' => $user
        ]);
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $this->userService->updateUser($user->id, $request->validated());
        return redirect()->route('users.edit', $user)->with('success', 'User updated successfully.');
    }
}
