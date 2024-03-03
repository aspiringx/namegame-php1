<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Empty the table to start.
        Group::truncate();

        // TODO: We currently have to manually delete all records in:
        //   user_user, group_user, then group, then user
        // Before we can re-run this (it's not idempotent).
        // We need to cascade deletes so if users or groups are deleted,
        // it also deletes the relationship/pivot table records

        $faker = \Faker\Factory::create();

        // Here's how to output to the console while the seeder is running.
        // $this->command->info("A THING");

        for ($i = 0; $i < 6; ++$i) {
            $name_full = $faker->name;
            $name = explode(" ", $name_full)[0];
            Group::create([
                'name'     => $name,
                'name_full' => $name_full,
                'description' => $faker->text(50),
                'slug' => strtolower($name),
                'logo_url' => $faker->imageUrl(200, 50),
            ]);
        }
    }
}
