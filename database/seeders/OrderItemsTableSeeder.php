<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        $orders = DB::table('orders')->pluck('id');

        foreach ($orders as $orderId) {
            $itemCount = rand(1, 5); // mỗi đơn 1-5 sản phẩm
            for ($i = 1; $i <= $itemCount; $i++) {
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => rand(1, 20), // giả lập sản phẩm
                    'quantity' => rand(1, 5),
                    'price' => rand(50000, 500000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
