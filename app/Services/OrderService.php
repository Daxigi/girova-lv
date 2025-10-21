<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * OrderService
 *
 * Maneja toda la lógica de negocio relacionada con órdenes:
 * - Crear órdenes
 * - Calcular totales
 * - Actualizar stock de productos
 * - Gestionar items de la orden
 */
class OrderService
{
    /**
     * Crear una nueva orden
     *
     * @param array $data - Datos de la orden (customer_name, customer_email, etc.)
     * @param int|string $userId - ID del usuario que realiza la orden
     * @return Order
     * @throws Exception
     */
    public function createOrder(array $data, $userId): Order
    {
        // Iniciar transacción de base de datos
        // Si algo falla, se revierte TODO automáticamente
        DB::beginTransaction();

        try {
            // PASO 1: Validar productos y calcular total real desde la BD
            $validatedItems = [];
            $total = 0;

            foreach ($data['items'] as $item) {
                // Verificar que el producto existe
                $product = Product::findOrFail($item['product_id']);

                // Verificar que hay stock suficiente
                if ($product->stock < $item['quantity']) {
                    throw new Exception(
                        "Stock insuficiente para {$product->name}. Disponible: {$product->stock}, Solicitado: {$item['quantity']}"
                    );
                }

                // SEGURIDAD: Usar el precio real de la BD, NO el del frontend
                $realPrice = $product->price;
                $total += $realPrice * $item['quantity'];

                // Guardar para usar después
                $validatedItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $realPrice,
                ];
            }

            // PASO 2: Crear la orden con el total validado
            $order = Order::create([
                'user_id' => $userId,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'shipping_address' => $data['shipping_address'],
                'notes' => $data['notes'] ?? null,
                'total' => round($total, 2),
                'status' => 'pending', // Estado inicial
            ]);

            // PASO 3: Crear los items de la orden con precios validados
            foreach ($validatedItems as $validatedItem) {
                $product = $validatedItem['product'];

                // Crear el item de la orden
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $validatedItem['quantity'],
                    'price' => $validatedItem['price'], // Precio validado de la BD
                ]);

                // Actualizar el stock del producto
                $product->decrement('stock', $validatedItem['quantity']);
            }

            // Si todo salió bien, confirmar la transacción
            DB::commit();

            // Cargar las relaciones para retornar la orden completa
            return $order->load('items.product', 'user');

        } catch (Exception $e) {
            // Si algo falló, revertir TODOS los cambios
            DB::rollBack();

            // Re-lanzar la excepción para que el controller la maneje
            throw $e;
        }
    }


    /**
     * Obtener órdenes de un usuario
     *
     * @param int|string $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserOrders($userId)
    {
        return Order::where('user_id', $userId)
            ->with('items.product') // Cargar relaciones
            ->orderBy('created_at', 'desc') // Más recientes primero
            ->get();
    }

    /**
     * Obtener una orden específica
     *
     * @param int|string $orderId
     * @param int|string|null $userId - Si se proporciona, verifica que pertenezca al usuario
     * @return Order
     * @throws Exception
     */
    public function getOrder($orderId, $userId = null): Order
    {
        $query = Order::with('items.product', 'user');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $order = $query->find($orderId);

        if (!$order) {
            throw new Exception('Orden no encontrada.');
        }

        return $order;
    }

    /**
     * Actualizar el estado de una orden
     *
     * @param int|string $orderId
     * @param string $status - pending, processing, shipped, delivered, cancelled
     * @return Order
     * @throws Exception
     */
    public function updateOrderStatus($orderId, string $status): Order
    {
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            throw new Exception("Estado '{$status}' no válido.");
        }

        $order = Order::findOrFail($orderId);
        $order->update(['status' => $status]);

        return $order->fresh();
    }
}
