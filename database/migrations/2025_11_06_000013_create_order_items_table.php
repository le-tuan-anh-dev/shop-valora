<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            
            $table->string('product_name')->comment('Tên sản phẩm tại thời điểm mua');
            $table->string('product_sku')->nullable()->comment('Mã SKU sản phẩm');
            $table->text('product_image')->nullable()->comment('Ảnh sản phẩm tại thời điểm mua');
            $table->text('product_description')->nullable()->comment('Mô tả sản phẩm tại thời điểm mua');
            
            $table->string('variant_name')->nullable()->comment('Tên biến thể (nếu có)');
            $table->string('variant_sku')->nullable()->comment('Mã SKU biến thể');
            $table->json('variant_attributes')->nullable()->comment('Các thuộc tính của biến thể (size, color, ...)');
            
            $table->decimal('unit_price', 18, 2)->comment('Giá đơn vị tại thời điểm mua');
            $table->integer('quantity')->default(1)->comment('Số lượng');
            $table->decimal('subtotal', 18, 2)->comment('Tổng tiền = unit_price * quantity');
            $table->decimal('discount_amount', 18, 2)->default(0)->comment('Số tiền giảm giá cho item này');
            $table->decimal('total_price', 18, 2)->comment('Tổng tiền sau giảm giá = subtotal - discount_amount');
            
            $table->json('product_options')->nullable()->comment('Các tùy chọn của sản phẩm (size, color, ...)');
            $table->text('note')->nullable()->comment('Ghi chú cho item này');
            
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};