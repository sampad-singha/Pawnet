<?php

namespace Database\Factories;

use App\Models\Friend;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Friend>
 */
class FriendFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Randomly assign a friendship status
        $status = $this->faker->randomElement(['pending', 'accepted']);

        return [
            'user_id' => User::factory()->has(UserProfile::factory()),
            'friend_id' => User::factory()->has(userprofile::factory()),
            'status' => $status,
            'created_at' => now(),
        ];
    }
}
