<?php

namespace Database\Factories\admin;

use App\Models\admin\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        // Lấy 1 user có sẵn làm tác giả, nếu chưa có thì tạo mới
        $author = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'author_id' => $author->id,
            'title' => fake()->sentence(6), // tạo tiêu đề ngẫu nhiên
            'content' => fake()->paragraphs(3, true), // tạo nội dung
            'thumbnail' => 'https://picsum.photos/seed/' . rand(1, 1000) . '/300/200', // ảnh ngẫu nhiên
            'views' => rand(0, 1000), // số lượt xem
            'likes' => rand(0, 500), // số lượt thích
            'is_published' => fake()->boolean(80), // 80% bài được xuất bản
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
