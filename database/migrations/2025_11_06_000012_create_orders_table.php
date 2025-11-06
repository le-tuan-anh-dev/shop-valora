<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_number', 50)->unique();
            $table->foreignId('address_id')->constrained('user_addresses')->cascadeOnDelete();
            $table->enum('payment_method', ['cod','bank_transfer','credit_card','momo','paypal'])->default('cod');
            $table->enum('payment_status', ['unpaid','paid','refunded'])->default('unpaid');
            $table->json('payment_details')->nullable();
            $table->string('payment_reference', 200)->nullable();
            $table->enum('status', ['pending','processing','shipped','completed','cancelled'])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};