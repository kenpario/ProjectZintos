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
            ['name' => 'Administrator', 'description' => 'Administrator Group', 'is_admin' => true, 'is_mod' => true, 'is_premium' => true],
            ['name' => 'Moderator', 'description' => 'Moderator Group', 'is_admin' => false, 'is_mod' => true, 'is_premium' => false],
            ['name' => 'Premium', 'description' => 'Premium Group', 'is_admin' => false, 'is_mod' => false, 'is_premium' => true],
            ['name' => 'Member', 'description' => 'Member Group', 'is_admin' => false, 'is_mod' => false, 'is_premium' => false],
        ];

        foreach ($groupNames as $index => $group) {
            $groupId = DB::table('groups')->insertGetId([
                'user_id' => NULL,
                'name' => $group['name'],
                'description' => $group['description'],
                'is_admin' => $group['is_admin'],
                'is_mod' => $group['is_mod'],
                'is_premium' => $group['is_premium'],
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }
}
