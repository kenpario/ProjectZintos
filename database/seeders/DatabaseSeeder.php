<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $timestamp = now();

        $groupNames = [
            ['name' => 'Administrator', 'description' => 'Official Zintos community', 'is_admin' => true, 'is_mod' => true, 'is_premium' => true],
            ['name' => 'Moderator', 'description' => 'Software development team', 'is_admin' => false, 'is_mod' => true, 'is_premium' => false],
            ['name' => 'Premium', 'description' => 'Travel stories and advice', 'is_admin' => false, 'is_mod' => false, 'is_premium' => true],
            ['name' => 'Member', 'description' => 'Photography and creative work', 'is_admin' => false, 'is_mod' => false, 'is_premium' => false],
        ];

        foreach ($groupNames as $index => $group) {
            $groupId = DB::table('groups')->insertGetId([
                'user_id' => NULL,
                'name' => $group['name'],
                'description' => $group['description'],
                'is_admin' => $group['is_admin'],
                'is_premium' => $group['is_premium'],
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }
}
