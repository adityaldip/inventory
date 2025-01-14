<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 10, 1000),
            'unit' => fake()->randomElement(['pcs', 'kg', 'box']),
            'quantity' => fake()->numberBetween(0, 100),
            'created_by_id' => \App\Models\User::factory(),
        ];
    }
} 