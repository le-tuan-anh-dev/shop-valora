<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('voucher_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            // Tránh trùng lặp: 1 voucher + 1 user chỉ xuất hiện 1 lần
            $table->unique(['voucher_id', 'user_id']);
            
            // Index để query nhanh
            $table->index('voucher_id');
            $table->index('user_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('voucher_users');
    }
};