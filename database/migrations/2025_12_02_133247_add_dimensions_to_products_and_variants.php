<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('length')->nullable()->after('base_price');
            $table->integer('width')->nullable()->after('length');
            $table->integer('height')->nullable()->after('width');
        });


        Schema::table('product_variants', function (Blueprint $table) {
            $table->integer('length')->nullable()->after('price');
            $table->integer('width')->nullable()->after('length');
            $table->integer('height')->nullable()->after('width');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['length', 'width', 'height']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['length', 'width', 'height']);
        });
    }
};