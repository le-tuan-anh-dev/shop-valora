<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Danh sách trạng thái đơn hàng
        $statuses = ['Chờ xử lý', 'Đang giao', 'Hoàn tất', 'Đã hủy'];

        // Xóa dữ liệu cũ (tuỳ chọn)
        DB::table('orders')->truncate();

        // Tạo dữ liệu trong 12 tháng gần nhất
        for ($month = 1; $month <= 12; $month++) {
            for ($i = 1; $i <= 10; $i++) { // mỗi tháng 10 đơn
                DB::table('orders')->insert([
                    'total_amount' => rand(300000, 8000000),
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => Carbon::create(date('Y'), $month, rand(1, 28)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}

