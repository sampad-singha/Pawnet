<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Util\City;
use App\Models\Util\Country;
use App\Models\Util\State;
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
        // Ensure that the cities, states, and countries exist in the database before creating the profile
        $city = City::inRandomOrder()->first();  // Get a random city
        $state = $city->state;  // Get the state associated with the city
        $country = $city->country;  // Get the country associated with the city
        return [
//            'user_id' => User::factory(),
            'bio' => $this->faker->paragraph,
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male','female','other']),
            'phone_number' => $this->faker->phoneNumber,
            'phone_verified' => $this->faker->boolean(20), // 20% chance of being true
            'address' => $this->faker->address,
            'city_id' => $city? $city->id : null,
            'state_id' => $state ? $state->id : null,
            'country_id' => $country ? $country->id : null,
            'visibility' => $this->faker->randomElement(['public', 'private']),
        ];
    }
}
