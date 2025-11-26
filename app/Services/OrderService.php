<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    public function createOrder(array $data, $userId): Order
    {
        DB::beginTransaction();

        try {
            $validatedItems = [];
            $total = 0;

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new Exception(
                        "Stock insuficiente para {$product->name}. Disponible: {$product->stock}, Solicitado: {$item['quantity']}"
                    );
                }

                $realPrice = $product->price;
                $total += $realPrice * $item['quantity'];

                $validatedItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $realPrice,
                ];
            }

            $order = Order::create([
                'user_id' => $userId,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'shipping_address' => $data['shipping_address'],
                'notes' => $data['notes'] ?? null,
                'total' => round($total, 2),
                'status' => 'pending', 
            ]);

            foreach ($validatedItems as $validatedItem) {
                $product = $validatedItem['product'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $validatedItem['quantity'],
                    'price' => $validatedItem['price'], 
                ]);

                $product->decrement('stock', $validatedItem['quantity']);
            }

            DB::commit();

            return $order->load('items.product', 'user');

        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }


    public function getUserOrders($userId)
    {
        return Order::where('user_id', $userId)
            ->with('items.product') 
            ->orderBy('created_at', 'desc') 
            ->get();
    }

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
