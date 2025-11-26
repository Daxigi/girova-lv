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

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function showCheckout()
    {
        return Inertia::render('Checkout/Index');
    }

    public function processCheckout(StoreOrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrder(
                $request->validated(),
                Auth::id()
            );

            return redirect()
                ->route('orders.show', $order->id)
                ->with('success', '¡Orden creada exitosamente! Número de orden: ' . $order->id);

        } catch (Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function myOrders()
    {
        try {
            $canViewAll = Gate::allows('viewAny', Order::class);

            if ($canViewAll) {
                $orders = Order::with(['items.product', 'user'])
                              ->orderBy('created_at', 'desc')
                              ->get();
            } else {
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

    public function show($id)
    {
        try {
            $canViewAll = Gate::allows('viewAny', Order::class);

            if ($canViewAll) {
                $order = $this->orderService->getOrder($id, null);
            } else {
                $order = $this->orderService->getOrder($id, Auth::id());
            }

            $this->authorize('view', $order);

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

    public function updateStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $this->authorize('updateStatus', $order);

            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
            ]);

            $updatedOrder = $this->orderService->updateOrderStatus($id, $request->status);

            return back()->with('success', 'Estado de la orden actualizado exitosamente.');

        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
