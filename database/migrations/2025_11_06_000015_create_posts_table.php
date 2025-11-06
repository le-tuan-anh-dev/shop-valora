<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 200);
            $table->longText('content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('posts');
    }
};