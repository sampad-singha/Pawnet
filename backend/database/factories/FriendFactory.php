<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Friend>
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
        // Randomly select two users who are not the same
        $user = User::factory()->create(); // Create a user (user A)
        $friend = User::factory()->create(); // Create a second user (friend)

        // Randomly assign a friendship status
        $status = $this->faker->randomElement(['pending', 'accepted', 'blocked']);

        // Depending on the status, we generate appropriate timestamps
        return [
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'status' => $status,
            'requested_at' => $status === 'pending' ? $this->faker->dateTimeThisYear : null,
            'accepted_at' => $status === 'accepted' ? $this->faker->dateTimeThisYear : null,
            'blocked_at' => $status === 'blocked' ? $this->faker->dateTimeThisYear : null,
            'rejected_at' => $status === 'rejected' ? $this->faker->dateTimeThisYear : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
