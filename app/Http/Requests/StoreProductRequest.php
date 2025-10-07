<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'purchasePrice' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imageUrl' => 'nullable|url',
            'status' => 'boolean',
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'type_id' => ['required', 'integer', Rule::exists('types', 'id')],
        ];
    }
}