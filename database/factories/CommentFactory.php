<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->sentence,
            'user_id' => rand(1, \App\Models\User::count()), // Assuming you have users with IDs from 1 to 10
            'post_id' => rand(1, \App\Models\Post::count()), // Assuming you have posts with IDs from 1 to 10
        ];
    }
}
