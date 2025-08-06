<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->has(UserProfile::factory())->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(FriendSeeder::class);

        $this->call(RegionsTableSeeder::class);
        $this->call(SubregionsTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
    }
}
