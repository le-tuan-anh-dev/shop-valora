<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('voucher_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            
            // Tránh trùng lặp: 1 voucher + 1 variant chỉ xuất hiện 1 lần
            $table->unique(['voucher_id', 'product_variant_id']);
            
            // Index để query nhanh
            $table->index('voucher_id');
            $table->index('product_variant_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('voucher_variants');
    }
};