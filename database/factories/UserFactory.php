<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            
            'phone' => fake()->phoneNumber(),
            'image' => 'https://i.pravatar.cc/150?img=' . rand(1, 70),
            'role' => fake()->randomElement(['admin','customer']), // phải hợp enum
            'status' => fake()->randomElement(['active','locked','banned']), // phải hợp enum
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
