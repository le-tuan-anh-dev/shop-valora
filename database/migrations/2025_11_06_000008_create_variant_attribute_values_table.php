<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('variant_attribute_values', function (Blueprint $table) {
            // Composite Primary Key
            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('attribute_value_id');  // ✅ Đổi từ product_attribute_value_id
            
            $table->primary(['variant_id', 'attribute_value_id']);

            // Foreign Keys
            $table->foreign('variant_id')
                ->references('id')
                ->on('product_variants')
                ->onDelete('cascade');

            $table->foreign('attribute_value_id')  // ✅ Liên kết trực tiếp tới attribute_values
                ->references('id')
                ->on('attribute_values')
                ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('variant_attribute_values');
    }
};