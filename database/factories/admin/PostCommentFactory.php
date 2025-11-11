<?php

namespace Database\Factories\admin;

use App\Models\admin\PostComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentFactory extends Factory
{
    protected $model = PostComment::class;

    public function definition(): array
    {
        return [
            'post_id' => rand(1, 5),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'content' => fake()->sentence(10),
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
