<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Clear out existing test users.
        User::truncate();

        $faker = \Faker\Factory::create();
        $password = bcrypt('secret');

        // Create the primary user.
        $user = User::create([
            'name'     => 'Joe Test',
            'first_name' => 'Joe',
            'last_name' => 'Test',
            'email'    => 'jltippetts@gmail.com',
            'password' => $password,
        ]);

        // Grab parent group (id = 1).
        $group = Group::find(1);

        // Add first user to the group with special rights.
        $group->users()->attach($user, [
            'is_leader' => true,
            'role' => 'admin',
            'title' => $faker->title,
            'member_since' => 1971,
        ]);

        // Create 10 more users and add them to group 1 (parent group).
        for ($i = 0; $i < 10; ++$i) {
            $first = $faker->firstName();
            $last = $faker->lastName();
            $user = User::create([
                'name'     => "$first $last",
                'first_name' => $first,
                'last_name' => $last,
                'email'    => $faker->email,
                'photo_url' => $faker->imageUrl(200, 200),
                'password' => $password,
            ]);
            // First arg (current $user) to attach is the user we're adding to the group.
            // Second arg is array of group_user table attributes saved in the pivot
            // table that joins groups/users in many-to-many relationship.
            $group->users()->attach($user, [
                'is_leader' => true,
                'title' => $faker->title,
                'member_since' => 2024,
            ]);

            // TODO: Also add users to sub-groups.
        }

    }
}
