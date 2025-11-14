<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->comment('Mã đơn hàng');
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('promotion_id')->nullable();
            $table->unsignedBigInteger('shipping_provider_id')->nullable();

            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('customer_address');

            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('receiver_email')->nullable();
            $table->text('shipping_address');

            $table->decimal('total_amount', 18, 2);
            $table->decimal('subtotal', 18, 2)->comment('Tổng tiền hàng trước khi áp dụng giảm giá');
            $table->decimal('promotion_amount', 18, 2)->default(0)->comment('Số tiền được giảm giá từ khuyến mãi');
            $table->decimal('shipping_fee', 18, 2)->default(0);
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->json('payment_details')->nullable();
            $table->enum('payment_status', [
                'unpaid',      // Chưa thanh toán
                'paid',        // Đã thanh toán
                'failed'       // Thanh toán thất bại
            ])->default('unpaid');

            // Trạng thái đơn hàng
            $table->enum('status', [
                'pending',              // Chờ xác nhận
                'confirmed',            // Đã xác nhận
                'awaiting_pickup',      // Chờ lấy hàng
                'shipping',             // Đang giao
                'delivered',            // Đã giao hàng
                'completed',            // Đã hoàn thành
                'cancelled_by_customer', // Khách hủy đơn
                'cancelled_by_admin',    // Admin hủy đơn
                'delivery_failed'       // Giao thất bại
            ])->default('pending');

            $table->unsignedBigInteger('cancellation_reason_id')->nullable();

            $table->text('note')->nullable()->comment('Ghi chú từ khách hàng');
            $table->text('admin_note')->nullable()->comment('Ghi chú của admin');
            $table->timestamp('confirmed_at')->nullable()->comment('Thời điểm xác nhận đơn hàng');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};