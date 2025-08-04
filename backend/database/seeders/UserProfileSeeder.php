<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserProfile::factory(10)->create();
        // Optionally, you can create a specific user profile for a known user
        UserProfile::factory()->create([
            'user_id' => User::find(2)->id,
            'visibility' => 'public',
        ]);
    }
}
