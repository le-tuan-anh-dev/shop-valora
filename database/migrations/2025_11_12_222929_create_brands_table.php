<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id(); // id INT AI PK
            $table->string('name', 150)->unique(); // Tên thương hiệu
            $table->string('slug', 150)->unique(); // Đường dẫn thân thiện
            $table->text('description')->nullable(); // Mô tả
            $table->string('logo', 255)->nullable(); // Ảnh logo
            $table->tinyInteger('is_active')->default(1); // 1 = hiển thị, 0 = ẩn
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};