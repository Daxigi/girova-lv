<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        $user = $this->route('user');
        $userId = $user ? $user->id : null;

        $passwordRules = 'required|string|min:8|confirmed';
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $passwordRules = 'nullable|string|min:8|confirmed';
        }

        return 
        [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => $passwordRules,
            'phone' => 'nullable|string|max:255',
        ];
    }
}