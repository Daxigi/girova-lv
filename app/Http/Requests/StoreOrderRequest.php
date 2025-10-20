<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreOrderRequest
 *
 * Valida los datos enviados desde el formulario de checkout
 * para crear una nueva orden.
 */
class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo usuarios autenticados pueden crear órdenes
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Información del cliente
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',

            // Dirección de envío
            'shipping_address' => 'required|string|max:500',

            // Notas adicionales (opcional)
            'notes' => 'nullable|string|max:1000',

            // Items del carrito (array de productos)
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'El nombre es requerido.',
            'customer_email.required' => 'El correo electrónico es requerido.',
            'customer_email.email' => 'El correo electrónico debe ser válido.',
            'shipping_address.required' => 'La dirección de envío es requerida.',

            'items.required' => 'Debes agregar al menos un producto al carrito.',
            'items.min' => 'Debes agregar al menos un producto al carrito.',
            'items.*.product_id.required' => 'ID del producto es requerido.',
            'items.*.product_id.exists' => 'El producto no existe.',
            'items.*.quantity.required' => 'La cantidad es requerida.',
            'items.*.quantity.min' => 'La cantidad debe ser al menos 1.',
            'items.*.price.required' => 'El precio es requerido.',
        ];
    }
}
