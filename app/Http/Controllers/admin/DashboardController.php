<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_categories' => Category::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'new_customers_this_month' => User::where('role', 'customer')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
            'total_revenue' => Order::where('status', 'completed')
                ->sum('total_amount'),
            'revenue_this_month' => Order::where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount'),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
        ];

        // Sản phẩm bán chạy (top 10)
        $best_selling_products = Product::orderBy('sold_count', 'desc')
            ->take(10)
            ->with('category')
            ->get();

        // Tạo mảng 12 tháng gần nhất
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'year' => $date->year,
                'month' => $date->month,
            ];
        }

        // Doanh thu theo tháng (12 tháng gần nhất)
        $revenue_data = Order::where('status', 'completed')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . $item->month;
            });

        $revenue_by_month = collect($months)->map(function ($month) use ($revenue_data) {
            $key = $month['year'] . '-' . $month['month'];
            return [
                'year' => $month['year'],
                'month' => $month['month'],
                'revenue' => $revenue_data->has($key) ? $revenue_data[$key]->revenue : 0,
            ];
        });

        // Đơn hàng theo tháng (12 tháng gần nhất)
        $orders_data = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . $item->month;
            });

        $orders_by_month = collect($months)->map(function ($month) use ($orders_data) {
            $key = $month['year'] . '-' . $month['month'];
            return [
                'year' => $month['year'],
                'month' => $month['month'],
                'count' => $orders_data->has($key) ? $orders_data[$key]->count : 0,
            ];
        });

        // Khách hàng mới theo tháng (12 tháng gần nhất)
        $customers_data = User::where('role', 'customer')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . $item->month;
            });

        $customers_by_month = collect($months)->map(function ($month) use ($customers_data) {
            $key = $month['year'] . '-' . $month['month'];
            return [
                'year' => $month['year'],
                'month' => $month['month'],
                'count' => $customers_data->has($key) ? $customers_data[$key]->count : 0,
            ];
        });

        // Sản phẩm theo danh mục
        $products_by_category = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get();

        // Đơn hàng gần đây
        $recent_orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Khách hàng mới gần đây
        $recent_customers = User::where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'best_selling_products',
            'revenue_by_month',
            'orders_by_month',
            'customers_by_month',
            'products_by_category',
            'recent_orders',
            'recent_customers'
        ));
    }
}

