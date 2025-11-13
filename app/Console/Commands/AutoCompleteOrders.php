<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Order;
use Carbon\Carbon;

class AutoCompleteOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động chuyển đơn hàng từ "đã giao hàng" sang "đã hoàn thành" sau 7 ngày';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        
        $orders = Order::where('status', 'delivered')
            ->whereNotNull('delivered_at')
            ->where('delivered_at', '<=', $sevenDaysAgo)
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            $order->status = 'completed';
            if (!$order->completed_at) {
                $order->completed_at = Carbon::now();
            }
            $order->save();
            $count++;
        }

        if ($count > 0) {
            $this->info("Đã tự động hoàn thành {$count} đơn hàng.");
        } else {
            $this->info("Không có đơn hàng nào cần tự động hoàn thành.");
        }

        return Command::SUCCESS;
    }
}
