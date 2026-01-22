<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Technology',
            'Health & Fitness',
            'Travel',
            'Food & Cooking',
            'Lifestyle',
            'Business',
            'Finance',
            'Education',
            'Entertainment',
            'Sports',
            'Politics',
            'Science',
            'Art & Design',
            'Photography',
            'Music',
            'Movies',
            'Books',
            'Gaming',
            'DIY & Crafts',
            'Fashion',
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(
                ['slug' => Str::slug($categoryName)],
                [
                    'name' => $categoryName,
                    'parent_id' => null
                ]
            );
        }
    }
}
