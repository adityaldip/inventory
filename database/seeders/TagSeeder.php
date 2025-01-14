<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            'electronics',
            'clothing',
            'food',
            'beverages',
            'accessories'
        ];

        foreach ($tags as $tagName) {
            Tag::create([
                'id' => Str::uuid(),
                'name' => $tagName
            ]);
        }
    }
} 