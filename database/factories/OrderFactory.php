<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'total_amount' => $this->faker->numberBetween(100000, 5000000),
            'status' => 'Chờ xử lý', // default, có thể override trong seeder
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
