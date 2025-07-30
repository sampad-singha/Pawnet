<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
//            'user_id' => User::factory(),
            'bio' => $this->faker->paragraph,
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male','female','other']),
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'visibility' => $this->faker->randomElement(['public', 'private']),
        ];
    }
}
