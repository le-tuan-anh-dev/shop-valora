<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();

            // User yêu thích sản phẩm
            $table->foreignId('user_id')
                  ->constrained()          // references users(id)
                  ->onDelete('cascade');

            // Sản phẩm được yêu thích
            $table->foreignId('product_id')
                  ->constrained('products') // references products(id)
                  ->onDelete('cascade');

            $table->timestamps();

            // Mỗi user chỉ có 1 dòng cho 1 product
            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};