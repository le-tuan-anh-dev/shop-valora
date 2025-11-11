<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\ThongKe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaoCaoThongKeController extends Controller
{
    public function index()
    {
        $totalOrders = ThongKe::tongDonHang();
        $totalRevenue = ThongKe::tongDoanhThu();
        $totalProducts = ThongKe::tongSanPhamBanRa();

        // Doanh thu theo tháng
        $doanhThuTheoThang = ThongKe::doanhThuTheoThang();

        // Danh mục sản phẩm: tổng số lượng theo product_name
        $danhMucSanPham = DB::table('order_items')
            ->select('product_name', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_name')
            ->get();

        return view('thongke.index', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'doanhThuTheoThang', // ✅ nhớ truyền vào đây
            'danhMucSanPham'
        ));
    }
}
