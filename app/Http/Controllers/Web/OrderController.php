<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Inertia\Inertia;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Order;

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
                Auth::id()
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
     * Ver órdenes según el rol del usuario
     * - Customers: ven solo SUS órdenes
     * - Admin/Employee: ven TODAS las órdenes
     *
     * GET /my-orders
     */
    public function myOrders()
    {
        try {
            // Verificar con la policy si puede ver TODAS las órdenes
            $canViewAll = Gate::allows('viewAny', Order::class);

            if ($canViewAll) {
                // Admin y Employee ven TODAS las órdenes con información del usuario
                $orders = Order::with(['items.product', 'user'])
                              ->orderBy('created_at', 'desc')
                              ->get();
            } else {
                // Customers ven solo SUS órdenes
                $orders = Order::where('user_id', Auth::id())
                              ->with('items.product')
                              ->orderBy('created_at', 'desc')
                              ->get();
            }

            return Inertia::render('Orders/MyOrders', [
                'orders' => $orders,
                'isAdminView' => $canViewAll,
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
            // Admin/Employee pueden ver cualquier orden
            // Customers solo pueden ver sus propias órdenes
            $canViewAll = Gate::allows('viewAny', Order::class);

            if ($canViewAll) {
                // Admin/Employee: obtener orden sin filtro de usuario
                $order = $this->orderService->getOrder($id, null);
            } else {
                // Customer: obtener solo si es su orden
                $order = $this->orderService->getOrder($id, Auth::id());
            }

            // Verificar con policy si puede ver esta orden
            $this->authorize('view', $order);

            // Verificar si puede actualizar el estado
            $canUpdateStatus = Gate::allows('updateStatus', $order);

            return Inertia::render('Orders/Show', [
                'order' => $order,
                'canUpdateStatus' => $canUpdateStatus,
            ]);

        } catch (Exception $e) {
            return redirect()
                ->route('orders.my-orders')
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Actualizar el estado de una orden
     *
     * PUT /orders/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            // Obtener la orden
            $order = Order::findOrFail($id);

            // Verificar permisos
            $this->authorize('updateStatus', $order);

            // Validar el estado
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
            ]);

            // Actualizar el estado usando el servicio
            $updatedOrder = $this->orderService->updateOrderStatus($id, $request->status);

            return back()->with('success', 'Estado de la orden actualizado exitosamente.');

        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
