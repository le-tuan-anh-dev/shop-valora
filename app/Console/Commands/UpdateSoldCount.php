<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\DB;

class UpdateSoldCount extends Command
{
    protected $signature = 'orders:update-sold-count {order_id?}';
    protected $description = 'Update sold_count for delivered orders';

    public function handle()
    {
        $orderId = $this->argument('order_id');

        if ($orderId) {
            $orders = Order::where('id', $orderId)->whereIn('status', ['delivered', 'completed'])->get();
        } else {
            $orders = Order::whereIn('status', ['delivered', 'completed'])->get();
        }

        DB::statement('UPDATE products SET sold_count = 0');

        $soldCounts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['delivered', 'completed'])
            ->whereNotNull('order_items.product_id')
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id')
            ->get();

        foreach ($soldCounts as $item) {
            Product::where('id', $item->product_id)->update(['sold_count' => $item->total_sold]);
        }

        return 0;
    }
}
