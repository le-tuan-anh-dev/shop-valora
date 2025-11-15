<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//run php artisan orders:auto-complete
Schedule::command('orders:auto-complete')
    ->daily()
    ->at('00:00')
    ->description('Tự động chuyển đơn hàng từ "đã giao hàng" sang "đã hoàn thành" sau 7 ngày');
