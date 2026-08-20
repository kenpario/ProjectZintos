<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory(20)->create();
        $timestamp = now();
        $categoryNames = [
            ['name' => 'Technology', 'description' => 'Tech discussions'],
            ['name' => 'Travel', 'description' => 'Travel stories'],
            ['name' => 'Food', 'description' => 'Recipes and reviews'],
            ['name' => 'Photography', 'description' => 'Photos and techniques'],
            ['name' => 'Gaming', 'description' => 'Games and releases'],
        ];

        $categoryIds = collect($categoryNames)->map(function (array $category, int $index) use ($users, $timestamp): int {
            return DB::table('post_categories')->insertGetId([
                'user_id' => $users[$index]->id,
                'name' => $category['name'],
                'description' => $category['description'],
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        });

        for ($postNumber = 1; $postNumber <= 100; $postNumber++) {
            $user = $users[($postNumber - 1) % $users->count()];
            $categoryId = $categoryIds[($postNumber - 1) % $categoryIds->count()];
            $likes = fake()->numberBetween(0, 50);
            $views = fake()->numberBetween($likes, 500);
            $postTimestamp = $timestamp->copy()->subDays(100 - $postNumber);

            $postId = DB::table('posts')->insertGetId([
                'user_id' => $user->id,
                'title' => 'Sample post '.$postNumber,
                'message' => fake()->paragraph(3),
                'post_category_id' => $categoryId,
                'likes' => $likes,
                'views' => $views,
                'created_at' => $postTimestamp,
                'updated_at' => $postTimestamp,
            ]);

            for ($interaction = 0; $interaction < min($likes, 5); $interaction++) {
                DB::table('post_likes')->insert([
                    'post_id' => $postId,
                    'user_id' => $users[($postNumber + $interaction) % $users->count()]->id,
                    'created_at' => $postTimestamp,
                    'updated_at' => $postTimestamp,
                ]);
            }

            for ($interaction = 0; $interaction < min($views, 5); $interaction++) {
                DB::table('post_views')->insert([
                    'post_id' => $postId,
                    'user_id' => $users[($postNumber + $interaction + 1) % $users->count()]->id,
                    'created_at' => $postTimestamp,
                    'updated_at' => $postTimestamp,
                ]);
            }

            if ($postNumber % 3 === 0) {
                DB::table('post_comments')->insert([
                    'post_id' => $postId,
                    'user_id' => $users[($postNumber + 2) % $users->count()]->id,
                    'message' => fake()->sentence(),
                    'created_at' => $postTimestamp,
                    'updated_at' => $postTimestamp,
                ]);
            }
        }
    }
}
