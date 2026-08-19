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
        $users = User::factory(4)->create();
        $timestamp = now();

        $technologyCategoryId = DB::table('post_categories')->insertGetId([
            'user_id' => $users[0]->id,
            'name' => 'Technology',
            'description' => 'Tech discussions',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        $travelCategoryId = DB::table('post_categories')->insertGetId([
            'user_id' => $users[1]->id,
            'name' => 'Travel',
            'description' => 'Travel stories',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        $firstPostId = DB::table('posts')->insertGetId([
            'user_id' => $users[0]->id,
            'title' => 'My first post',
            'message' => 'This is sample post content for testing.',
            'post_category_id' => $technologyCategoryId,
            'likes' => 2,
            'views' => 3,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        $secondPostId = DB::table('posts')->insertGetId([
            'user_id' => $users[1]->id,
            'title' => 'A weekend in the mountains',
            'message' => 'A short sample travel post for testing.',
            'post_category_id' => $travelCategoryId,
            'likes' => 1,
            'views' => 2,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        DB::table('post_likes')->insert([
            ['post_id' => $firstPostId, 'user_id' => $users[1]->id, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['post_id' => $firstPostId, 'user_id' => $users[2]->id, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['post_id' => $secondPostId, 'user_id' => $users[0]->id, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ]);

        DB::table('post_views')->insert([
            ['post_id' => $firstPostId, 'user_id' => $users[1]->id, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['post_id' => $firstPostId, 'user_id' => $users[2]->id, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['post_id' => $firstPostId, 'user_id' => $users[3]->id, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['post_id' => $secondPostId, 'user_id' => $users[0]->id, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['post_id' => $secondPostId, 'user_id' => $users[2]->id, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ]);

        DB::table('post_comments')->insert([
            [
                'post_id' => $firstPostId,
                'user_id' => $users[3]->id,
                'message' => 'This is a sample comment.',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'post_id' => $secondPostId,
                'user_id' => $users[2]->id,
                'message' => 'I would like to visit there too.',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ]);
    }
}
