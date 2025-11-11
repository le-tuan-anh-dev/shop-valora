<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Tạo User test, nếu đã tồn tại email thì không tạo nữa
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('123456'), // gán password mặc định
            ]
        );

        // Danh sách trạng thái đơn hàng
        $statuses = ['Chờ xử lý', 'Đang giao', 'Hoàn tất', 'Đã hủy'];

        // ⚠️ Tạm thời tắt kiểm tra FK trước khi truncate bảng
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tạo 50 đơn hàng với factory
        $orders = Order::factory()->count(50)->create([
            'status' => function() use ($statuses) {
                return $statuses[array_rand($statuses)];
            }
        ]);

        // Tạo sản phẩm cho từng đơn
        foreach ($orders as $order) {
            OrderItem::factory()->count(rand(1,5))->create([
                'order_id' => $order->id,
            ]);
        }

        echo "✅ Seeder chạy xong: user test, 50 đơn hàng và sản phẩm đã tạo.\n";
    }
}
