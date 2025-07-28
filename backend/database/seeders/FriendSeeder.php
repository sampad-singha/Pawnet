<?php

namespace Database\Seeders;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a User A (main user)
        $userA = User::factory()->create([
            'name' => 'User A',
            'email' => 'userA@example.com',
        ]);

        // Create 10 friends for User A
        foreach (range(1, 10) as $index) {
            $friend = User::factory()->create(); // Create a new user (friend)

            // Create a friendship relationship with 'accepted' status
            Friend::factory()->create([
                'user_id' => $userA->id,
                'friend_id' => $friend->id,
                'status' => 'accepted',
                'requested_at' => now(),
                'accepted_at' => now(),
            ]);
        }

        // Create 5 users whose friend is User A (friends who have A as a friend)
        foreach (range(1, 5) as $index) {
            $userB = User::factory()->create(); // Create a new user (userB)

            // Create a friendship relationship with 'accepted' status
            Friend::factory()->create([
                'user_id' => $userB->id,
                'friend_id' => $userA->id,
                'status' => 'accepted',
                'requested_at' => now(),
                'accepted_at' => now(),
            ]);
        }

        // Create 5 pending requests from different users to User A
        foreach (range(1, 5) as $index) {
            $userB = User::factory()->create(); // Create a new user (userB)

            // Create a friendship with 'pending' status
            Friend::factory()->create([
                'user_id' => $userB->id,
                'friend_id' => $userA->id,
                'status' => 'pending',
                'requested_at' => now(),
            ]);
        }

        // Create 5 pending requests from User A to different users
        foreach (range(1, 5) as $index) {
            $userB = User::factory()->create(); // Create a new user (userB)

            // Create a friendship with 'pending' status
            Friend::factory()->create([
                'user_id' => $userA->id,
                'friend_id' => $userB->id,
                'status' => 'pending',
                'requested_at' => now(),
            ]);
        }

        // Create 3 users that User A blocked
        foreach (range(1, 3) as $index) {
            $userB = User::factory()->create(); // Create a new user (userB)

            // Create a friendship with 'blocked' status
            Friend::factory()->create([
                'user_id' => $userA->id,
                'friend_id' => $userB->id,
                'status' => 'blocked',
                'blocked_at' => now(),
            ]);
        }
    }
}
