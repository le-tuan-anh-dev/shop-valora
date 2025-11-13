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
    /**
     * Lấy khoảng thời gian dựa trên filter
     */
    private function getDateRange($filter, $startDate = null, $endDate = null)
    {
        $now = Carbon::now();

        switch ($filter) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];

            case 'yesterday':
                return [
                    'start' => $now->copy()->subDay()->startOfDay(),
                    'end' => $now->copy()->subDay()->endOfDay()
                ];

            case 'this_week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];

            case 'last_week':
                return [
                    'start' => $now->copy()->subWeek()->startOfWeek(),
                    'end' => $now->copy()->subWeek()->endOfWeek()
                ];

            case 'this_month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];

            case 'last_month':
                return [
                    'start' => $now->copy()->subMonth()->startOfMonth(),
                    'end' => $now->copy()->subMonth()->endOfMonth()
                ];

            case 'custom':
                if ($startDate && $endDate) {
                    return [
                        'start' => Carbon::parse($startDate)->startOfDay(),
                        'end' => Carbon::parse($endDate)->endOfDay()
                    ];
                }
                // Fallback to this month if custom dates are invalid
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];

            default:
                // Default: all time
                return [
                    'start' => null,
                    'end' => null
                ];
        }
    }

    public function index(Request $request)
    {
        // Lấy filter từ request
        $filter = $request->get('filter', 'this_month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Tính toán khoảng thời gian
        $dateRange = ($filter && $filter !== 'all') ? $this->getDateRange($filter, $startDate, $endDate) : ['start' => null, 'end' => null];
        $startDateQuery = $dateRange['start'];
        $endDateQuery = $dateRange['end'];

        // Build query conditions
        $dateCondition = function ($query) use ($startDateQuery, $endDateQuery) {
            if ($startDateQuery && $endDateQuery) {
                $query->whereBetween('created_at', [$startDateQuery, $endDateQuery]);
            }
        };

        // Thống kê tổng quan
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_categories' => Category::count(),
            'total_customers' => User::where('role', 'customer')
                ->when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                    $query->whereBetween('created_at', [$startDateQuery, $endDateQuery]);
                })
                ->count(),
            'new_customers_this_month' => User::where('role', 'customer')
                ->when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                    $query->whereBetween('created_at', [$startDateQuery, $endDateQuery]);
                })
                ->count(),
            'total_revenue' => Order::whereIn('status', ['delivered', 'completed'])
                ->when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                    $query->whereBetween('created_at', [$startDateQuery, $endDateQuery]);
                })
                ->sum('total_amount'),
            'revenue_this_month' => Order::whereIn('status', ['delivered', 'completed'])
                ->when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                    $query->whereBetween('created_at', [$startDateQuery, $endDateQuery]);
                })
                ->sum('total_amount'),
            'total_orders' => Order::when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                $query->whereBetween('created_at', [$startDateQuery, $endDateQuery]);
            })
                ->count(),
            'pending_orders' => Order::where('status', 'pending')
                ->when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                    $query->whereBetween('created_at', [$startDateQuery, $endDateQuery]);
                })
                ->count(),
            'completed_orders' => Order::where('status', 'completed')
                ->when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                    $query->whereBetween('created_at', [$startDateQuery, $endDateQuery]);
                })
                ->count(),
        ];

        // Sản phẩm bán chạy (top 10)
        $best_selling_products = Product::orderBy('sold_count', 'desc')
            ->take(10)
            ->with('category')
            ->get();

        // Tính số tháng cần hiển thị dựa trên khoảng thời gian
        $monthsToShow = 12;
        if ($startDateQuery && $endDateQuery) {
            $diffInMonths = $startDateQuery->diffInMonths($endDateQuery);
            if ($diffInMonths < 12) {
                $monthsToShow = max(1, $diffInMonths + 1);
            }
        }

        // Tạo mảng tháng để hiển thị
        $months = [];
        if ($startDateQuery && $endDateQuery && $monthsToShow <= 12) {
            // Nếu có filter và khoảng thời gian <= 12 tháng, hiển thị các tháng trong khoảng đó
            $current = $startDateQuery->copy()->startOfMonth();
            $end = $endDateQuery->copy()->endOfMonth();
            while ($current <= $end) {
                $months[] = [
                    'year' => $current->year,
                    'month' => $current->month,
                ];
                $current->addMonth();
            }
        } else {
            // Mặc định: 12 tháng gần nhất
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months[] = [
                    'year' => $date->year,
                    'month' => $date->month,
                ];
            }
        }

        // Doanh thu theo tháng
        $revenue_data = Order::whereIn('status', ['delivered', 'completed'])
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                $query->whereBetween('created_at', [$startDateQuery->startOfMonth(), $endDateQuery->endOfMonth()]);
            }, function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth());
            })
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

        // Đơn hàng theo tháng
        $orders_data = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                $query->whereBetween('created_at', [$startDateQuery->startOfMonth(), $endDateQuery->endOfMonth()]);
            }, function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth());
            })
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

        // Khách hàng mới theo tháng
        $customers_data = User::where('role', 'customer')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                $query->whereBetween('created_at', [$startDateQuery->startOfMonth(), $endDateQuery->endOfMonth()]);
            }, function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth());
            })
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

        // Đơn hàng gần đây - filter theo khoảng thời gian
        $recent_orders = Order::with('user')
            ->when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                $query->whereBetween('created_at', [$startDateQuery, $endDateQuery]);
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Khách hàng mới gần đây - filter theo khoảng thời gian
        $recent_customers = User::where('role', 'customer')
            ->when($startDateQuery && $endDateQuery, function ($query) use ($startDateQuery, $endDateQuery) {
                $query->whereBetween('created_at', [$startDateQuery, $endDateQuery]);
            })
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
            'recent_customers',
            'filter',
            'startDate',
            'endDate'
        ));
    }
}
