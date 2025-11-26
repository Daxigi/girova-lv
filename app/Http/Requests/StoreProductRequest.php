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
     * Preparar datos antes de la validación
     * Convierte cadenas vacías a null para campos opcionales
     */
    protected function prepareForValidation(): void
    {
        // Convertir cadenas vacías a null
        if ($this->has('imageUrl') && empty($this->imageUrl)) {
            $this->merge(['imageUrl' => null]);
        }

        if ($this->has('description') && empty($this->description)) {
            $this->merge(['description' => null]);
        }

        if ($this->has('purchasePrice') && empty($this->purchasePrice)) {
            $this->merge(['purchasePrice' => null]);
        }
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
            'imageUrl' => 'nullable|string',  // Acepta URLs completas o rutas relativas
            'status' => 'boolean',
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'type_id' => ['required', 'integer', Rule::exists('types', 'id')],
        ];
    }
}