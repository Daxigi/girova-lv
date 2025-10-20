<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Inertia\Inertia;
use Exception;

/**
 * OrderController
 *
 * Maneja las peticiones HTTP relacionadas con órdenes
 */
class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Mostrar el formulario de checkout
     *
     * GET /checkout
     */
    public function showCheckout()
    {
        return Inertia::render('Checkout/Index');
    }

    /**
     * Procesar el checkout y crear la orden
     *
     * POST /checkout
     */
    public function processCheckout(StoreOrderRequest $request)
    {
        try {
            // Crear la orden usando el servicio
            $order = $this->orderService->createOrder(
                $request->validated(),
                auth()->id()
            );

            // Redirigir a la página de confirmación
            return redirect()
                ->route('orders.show', $order->id)
                ->with('success', '¡Orden creada exitosamente! Número de orden: ' . $order->id);

        } catch (Exception $e) {
            // Si algo falló, regresar con el error
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Ver todas las órdenes del usuario autenticado
     *
     * GET /my-orders
     */
    public function myOrders()
    {
        try {
            $orders = $this->orderService->getUserOrders(auth()->id());

            return Inertia::render('Orders/MyOrders', [
                'orders' => $orders,
            ]);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Error al cargar las órdenes.']);
        }
    }

    /**
     * Ver el detalle de una orden específica
     *
     * GET /orders/{id}
     */
    public function show($id)
    {
        try {
            // Obtener la orden verificando que pertenezca al usuario
            $order = $this->orderService->getOrder($id, auth()->id());

            return Inertia::render('Orders/Show', [
                'order' => $order,
            ]);

        } catch (Exception $e) {
            return redirect()
                ->route('orders.my-orders')
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
