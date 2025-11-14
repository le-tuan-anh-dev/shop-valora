<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ThongKe extends Model
{
    // Tên bảng chính là orders
    protected $table = 'orders';

    // Tổng doanh thu
    public static function tongDoanhThu()
    {
        return DB::table('orders')->sum('total_amount');
    }

    // Tổng đơn hàng
    public static function tongDonHang()
    {
        return DB::table('orders')->count();
    }

    // Tổng sản phẩm bán ra
    public static function tongSanPhamBanRa()
    {
        return DB::table('order_items')->sum('quantity');
    }

    // Doanh thu theo tháng (mặc định năm hiện tại)
    public static function doanhThuTheoThang($year = null)
    {
        $year = $year ?? date('Y');

        return DB::table('orders')
            ->select(DB::raw('MONTH(created_at) as thang'), DB::raw('SUM(total_amount) as doanh_thu'))
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('thang')
            ->get();
    }
    
}
