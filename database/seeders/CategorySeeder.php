<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Board Game',
            'Card Game',
            'Tabletop RPG',
            'Miniature Wargaming',
            'Adventure Game',
            'Strategy Game',
        ];

        foreach ($categories as $c) {
            Category::updateOrCreate(['nama_kategori' => $c]);
        }
    }
}
