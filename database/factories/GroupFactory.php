<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nameSlug = fake()->slug();
        return [
            'name' => $nameSlug,
            'name_full' => fake()->name(),
            'slug' => $nameSlug,
            'description' => fake()->text(50),
            'logo_url' => fake()->imageUrl(200, 200)
        ];
    }
}
