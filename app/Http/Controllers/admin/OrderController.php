<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(15);

        foreach ($orders as $order) {
            $order->allowedStatuses = $this->getAllowedStatuses($order->status);
        }

        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'awaiting_pickup_orders' => Order::where('status', 'awaiting_pickup')->count(),
            'shipping_orders' => Order::where('status', 'shipping')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'cancelled_orders' => Order::whereIn('status', ['cancelled_by_customer', 'cancelled_by_admin'])->count(),
            'delivery_failed_orders' => Order::where('status', 'delivery_failed')->count(),
            'unpaid_orders' => Order::where('payment_status', 'unpaid')->count(),
        ];

        return view('admin.orders.orders-list', compact('orders', 'stats'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'orderItems'])->findOrFail($id);
        $allowedStatuses = $this->getAllowedStatuses($order->status);

        return view('admin.orders.order-detail', compact('order', 'allowedStatuses'));
    }
    private function getAllowedStatuses($currentStatus)
    {
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled_by_admin'],
            'confirmed' => ['awaiting_pickup', 'cancelled_by_admin'],
            'awaiting_pickup' => ['shipping', 'cancelled_by_admin'],
            'shipping' => ['delivered', 'delivery_failed'],
            'delivered' => [],
            'completed' => [],
            'cancelled_by_customer' => [],
            'cancelled_by_admin' => [],
            'delivery_failed' => [],
        ];

        return $allowedTransitions[$currentStatus] ?? [];
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,awaiting_pickup,shipping,delivered,cancelled_by_admin,delivery_failed'
        ]);

        $order = Order::with('orderItems')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $allowedStatuses = $this->getAllowedStatuses($oldStatus);

        if (!in_array($newStatus, $allowedStatuses)) {
            return redirect()->back()
                ->with('error', 'Không thể chuyển từ trạng thái "' . $this->getStatusLabel($oldStatus) . '" sang "' . $this->getStatusLabel($newStatus) . '". Vui lòng chọn trạng thái hợp lệ.');
        }

        if ($oldStatus === $newStatus) {
            return redirect()->back()
                ->with('error', 'Đơn hàng đã ở trạng thái này.');
        }

        $shouldRestoreStock = in_array($newStatus, ['cancelled_by_admin', 'delivery_failed'])
            && !in_array($oldStatus, ['delivered', 'completed', 'cancelled_by_admin', 'cancelled_by_customer', 'delivery_failed']);

        if ($shouldRestoreStock) {
            $this->restoreProductStock($order);
        }

        $order->status = $newStatus;

        if (in_array($newStatus, ['confirmed', 'awaiting_pickup', 'shipping', 'delivered']) && !$order->confirmed_at) {
            $order->confirmed_at = Carbon::now();
        }

        if ($newStatus === 'delivered' && !$order->delivered_at) {
            $order->delivered_at = Carbon::now();
            if ($order->payment_status === 'unpaid') {
                $order->payment_status = 'paid';
            }
        }

        if ($newStatus === 'cancelled_by_admin' && !$order->cancelled_at) {
            $order->cancelled_at = Carbon::now();
        }

        $order->save();

        return redirect()->back()
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    private function restoreProductStock(Order $order)
    {
        try {
            DB::beginTransaction();

            foreach ($order->orderItems as $orderItem) {
                if ($orderItem->product_id) {
                    $product = Product::find($orderItem->product_id);
                    if ($product) {
                        $product->increment('stock', $orderItem->quantity);
                    }
                }

                if ($orderItem->variant_id) {
                    $variant = ProductVariant::find($orderItem->variant_id);
                    if ($variant) {
                        $variant->increment('stock', $orderItem->quantity);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error restoring product stock for order #' . $order->order_number . ': ' . $e->getMessage());
            throw $e;
        }
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'awaiting_pickup' => 'Chờ lấy hàng',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao hàng',
            'completed' => 'Đã hoàn thành',
            'cancelled_by_customer' => 'Khách hủy',
            'cancelled_by_admin' => 'Admin hủy',
            'delivery_failed' => 'Giao thất bại',
        ];

        return $labels[$status] ?? $status;
    }
}
