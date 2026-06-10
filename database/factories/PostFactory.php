<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(6);
        return [
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title) . '-' . time(),
            'image' => null,
            'content' => $this->faker->paragraphs(3, true),
            'author' => $this->faker->name(),
            'published_at' => now(),
        ];
    }
}
