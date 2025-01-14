<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'name' => fake()->unique()->word(),
        ];
    }
} 