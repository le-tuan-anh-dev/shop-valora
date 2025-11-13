<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique()->comment('Mã đơn hàng');
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained('user_addresses')->nullOnDelete();
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            
            $table->string('customer_name')->nullable()->comment('Tên khách hàng');
            $table->string('customer_phone')->nullable()->comment('Số điện thoại khách hàng');
            $table->string('customer_email')->nullable()->comment('Email khách hàng');
            $table->text('customer_address')->nullable()->comment('Địa chỉ khách hàng');
            
            $table->string('receiver_name')->nullable()->comment('Tên người nhận');
            $table->string('receiver_phone')->nullable()->comment('Số điện thoại người nhận');
            $table->string('receiver_email')->nullable()->comment('Email người nhận');
            $table->text('shipping_address')->nullable()->comment('Địa chỉ giao hàng');
            
            $table->decimal('total_amount', 18, 2)->comment('Tổng tiền đơn hàng');
            $table->decimal('subtotal', 18, 2)->default(0)->comment('Tổng tiền hàng trước khi áp dụng giảm giá');
            $table->decimal('promotion_amount', 18, 2)->default(0)->comment('Số tiền được giảm giá từ khuyến mãi');
            $table->decimal('shipping_fee', 18, 2)->default(0)->comment('Phí vận chuyển');
            $table->enum('payment_method', ['cod','bank_transfer','credit_card','momo','paypal'])->default('cod');
            $table->json('payment_details')->nullable()->comment('Chi tiết thanh toán');
            $table->string('payment_reference', 200)->nullable()->comment('Mã tham chiếu thanh toán');
            $table->enum('payment_status', ['unpaid','paid','refunded'])->default('unpaid');
            
            $table->enum('status', [
                'pending',              // Chờ xác nhận
                'processing',           // Đang xử lý
                'shipped',              // Đang giao
                'completed',            // Đã hoàn thành
                'cancelled'             // Đã hủy
            ])->default('pending');
            
            $table->text('note')->nullable()->comment('Ghi chú từ khách hàng');
            $table->text('admin_note')->nullable()->comment('Ghi chú của admin');
            $table->timestamp('confirmed_at')->nullable()->comment('Thời điểm xác nhận đơn hàng');
            $table->timestamp('completed_at')->nullable()->comment('Thời điểm hoàn thành đơn hàng');
            $table->timestamp('cancelled_at')->nullable()->comment('Thời điểm hủy đơn hàng');
            
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};