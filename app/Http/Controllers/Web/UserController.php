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

        event(new Registered($user));

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

    public function editProfile()
    {
        $user = Auth::user();

        return Inertia::render('Profile/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $dataToUpdate = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->filled('password')) {
            if (!$request->filled('current_password')) {
                return back()->withErrors(['current_password' => 'Debes proporcionar tu contraseña actual.']);
            }

            if (!\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
            }

            $dataToUpdate['password'] = \Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        return back()->with('success', 'Perfil actualizado exitosamente.');
    }
}
