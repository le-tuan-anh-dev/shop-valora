<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Khóa chính, auto increment
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT

            // Thông tin cơ bản
            $table->string('name', 100);           // Họ tên
            $table->string('email', 100)->unique(); // Email đăng nhập (duy nhất)
            $table->string('password', 255);        // Mật khẩu đã mã hóa
            $table->string('phone', 20)->nullable(); // Số điện thoại (có thể null)

            // Ảnh đại diện
            $table->string('image', 255)->nullable(); // Ảnh đại diện (có thể null)

            // Phân quyền
            $table->enum('role', ['admin', 'customer'])->default('customer'); 

            // Trạng thái tài khoản
            $table->enum('status', ['active', 'locked', 'banned'])->default('active');

            // Thời gian tạo/cập nhật
            $table->timestamps(); // created_at, updated_at: DATETIME (hoặc TIMESTAMP tuỳ DB)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};