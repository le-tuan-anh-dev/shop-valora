<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        return [
            'order_id' => 1, // sẽ override trong seeder
            'product_name' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(1,5),
            'price' => $this->faker->numberBetween(50000, 1000000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
