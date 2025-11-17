<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id(); // id của phương thức thanh toán
            $table->string('name'); // tên phương thức, ví dụ: 'Cash', 'Momo', 'Credit Card'
            $table->string('description')->nullable(); // mô tả, nếu cần
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};