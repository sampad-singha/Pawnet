<?php

namespace Database\Seeders;

use App\Models\Friend;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a User A (main user)
        $userA = User::factory()->has(UserProfile::factory())->create([
            'name' => 'User A',
            'email' => 'userA@example.com',
        ]);

        // Create 10 friends for User A
        foreach (range(1, 10) as $index) {

            // Create a friendship relationship with 'accepted' status
            Friend::factory()->create([
                'user_id' => $userA->id,
                'status' => 'accepted',
            ]);
        }

        // Create 5 users whose friend is User A (friends who have A as a friend)
        foreach (range(1, 5) as $index) {

            // Create a friendship relationship with 'accepted' status
            Friend::factory()->create([
                'friend_id' => $userA->id,
                'status' => 'accepted',
            ]);
        }

        // Create 5 pending requests from different users to User A
        foreach (range(1, 5) as $index) {

            // Create a friendship with 'pending' status
            Friend::factory()->create([
                'friend_id' => $userA->id,
                'status' => 'pending',
            ]);
        }

        // Create 5 pending requests from User A to different users
        foreach (range(1, 5) as $index) {

            // Create a friendship with 'pending' status
            Friend::factory()->create([
                'user_id' => $userA->id,
                'status' => 'pending',
            ]);
        }

    }
}
