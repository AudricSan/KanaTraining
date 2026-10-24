<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->userName(),
            'avatar' => fake()->imageUrl(96, 96),
            'email' => fake()->unique()->safeEmail(),
            'global_xp' => 0,
            'streak_days' => 0,
            'high_score' => 0,
            'last_score' => 0,
            'last_played_date' => null,
        ];
    }
}
