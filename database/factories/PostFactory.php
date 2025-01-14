<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'slug' => fn (array $attributes) => Str::slug($attributes['title']),
            'created_by_id' => \App\Models\User::factory(),
        ];
    }
} 