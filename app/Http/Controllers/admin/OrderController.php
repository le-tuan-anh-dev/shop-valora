<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->orderBy('created_at', 'desc');

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Lọc theo trạng thái thanh toán
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(15);

        // Lấy danh sách trạng thái được phép cho mỗi đơn hàng
        foreach ($orders as $order) {
            $order->allowedStatuses = $this->getAllowedStatuses($order->status);
        }

        // Thống kê
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
        
        // Lấy danh sách trạng thái có thể chuyển đến
        $allowedStatuses = $this->getAllowedStatuses($order->status);
        
        return view('admin.orders.order-detail', compact('order', 'allowedStatuses'));
    }

    /**
     * Lấy danh sách trạng thái có thể chuyển đến từ trạng thái hiện tại
     */
    private function getAllowedStatuses($currentStatus)
    {
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled_by_customer', 'cancelled_by_admin'],
            'confirmed' => ['awaiting_pickup', 'cancelled_by_customer', 'cancelled_by_admin'],
            'awaiting_pickup' => ['shipping', 'cancelled_by_customer', 'cancelled_by_admin'],
            'shipping' => ['delivered', 'delivery_failed', 'cancelled_by_admin'],
            'delivered' => [], // Không thể chuyển thủ công, chỉ tự động sau 7 ngày
            'completed' => [], // Không thể chuyển từ completed
            'cancelled_by_customer' => [], // Không thể chuyển từ cancelled
            'cancelled_by_admin' => [], // Không thể chuyển từ cancelled
            'delivery_failed' => ['shipping', 'cancelled_by_admin'], // Có thể thử giao lại hoặc hủy
        ];

        return $allowedTransitions[$currentStatus] ?? [];
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,awaiting_pickup,shipping,delivered,completed,cancelled_by_customer,cancelled_by_admin,delivery_failed'
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Kiểm tra xem có thể chuyển từ trạng thái hiện tại sang trạng thái mới không
        $allowedStatuses = $this->getAllowedStatuses($oldStatus);
        
        if (!in_array($newStatus, $allowedStatuses)) {
            return redirect()->back()
                ->with('error', 'Không thể chuyển từ trạng thái "' . $this->getStatusLabel($oldStatus) . '" sang "' . $this->getStatusLabel($newStatus) . '". Vui lòng chọn trạng thái hợp lệ.');
        }

        // Không cho phép chuyển sang cùng trạng thái
        if ($oldStatus === $newStatus) {
            return redirect()->back()
                ->with('error', 'Đơn hàng đã ở trạng thái này.');
        }

        $order->status = $newStatus;

        // Cập nhật timestamps dựa trên status
        if (in_array($newStatus, ['confirmed', 'awaiting_pickup', 'shipping', 'delivered', 'completed']) && !$order->confirmed_at) {
            $order->confirmed_at = Carbon::now();
        }

        if (in_array($newStatus, ['delivered', 'completed']) && !$order->completed_at) {
            $order->completed_at = Carbon::now();
        }

        if (in_array($newStatus, ['cancelled_by_customer', 'cancelled_by_admin']) && !$order->cancelled_at) {
            $order->cancelled_at = Carbon::now();
        }

        $order->save();

        return redirect()->back()
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    /**
     * Lấy nhãn trạng thái
     */
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
