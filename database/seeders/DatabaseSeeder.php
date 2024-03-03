<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. You need groups before you can add users to groups.
        $this->call(GroupsTableSeeder::class);
        // 2. Create users, add them to groups, and connect them
        //    with each other.
        $this->call(UsersTableSeeder::class);
    }
}
