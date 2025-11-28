<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->boolean('apply_all_products')->default(1)->after('is_active');
            $table->decimal('min_order_value', 12, 2)->nullable()->after('apply_all_products');
            $table->decimal('max_discount_value', 12, 2)->nullable()->after('min_order_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('apply_all_products');
            $table->dropColumn('min_order_value');
            $table->dropColumn('max_discount_value');
        });
    }
};