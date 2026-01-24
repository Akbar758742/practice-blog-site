<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Ensure we have at least one user to assign posts to
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
            ]);
        }

        $categories = Category::all();

        if ($categories->count() == 0) {
            $this->command->info('Please run CategorySeeder first!');
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $title = $faker->sentence(6);
            $slug = Str::slug($title);

            // Ensure unique slug
            $slugCount = Post::where('slug', $slug)->count();
            if ($slugCount > 0) {
                $slug .= '-' . time();
            }

            Post::create([
                'user_id' => $user->id,
                'category_id' => $categories->random()->id,
                'title' => $title,
                'slug' => $slug,
                'content' => $faker->paragraphs(5, true),
                'featured_image' => null, // User will add images later
                'status' => 'published',
                'published_at' => now()->subDays(rand(0, 30)),
                'meta_title' => $title,
                'meta_desc' => $faker->sentence(10),
            ]);
        }
    }
}
