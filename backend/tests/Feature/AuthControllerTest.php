<?php
// tests/Feature/AuthControllerTest.php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_authenticated_user_data_when_user_is_logged_in()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/user')
            ->assertStatus(200)
            // Assert the response contains the 'message' and 'user' object with all expected fields
            ->assertJson([
                'message' => 'User authenticated successfully', // Ensure this message is present
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'avatar' => null,  // Default value you expect
                    'avatar_url' => null, // Default value you expect
                    'created_at' => $user->created_at->toISOString(), // Match created_at field format
                    'updated_at' => $user->updated_at->toISOString(), // Match updated_at field format
                ]
            ]);
    }

    #[Test]
    public function it_returns_unauthorized_when_no_user_is_logged_in_for_user_endpoint()
    {
        $this->getJson('/api/user')
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    #[Test]
    public function it_registers_a_new_user_with_valid_data()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $this->postJson('/api/register', $data)
            ->assertStatus(201)
            // Assert the response contains the full user data
            ->assertJson([
                'user' => [
                    'email' => 'test@example.com',
                    'name' => 'Test User',
                    'id' => 2,  // This will depend on the number of users in the database, or can be checked dynamically
                    'created_at' => true,  // Ensure the created_at field is present
                    'updated_at' => true,  // Ensure the updated_at field is present
                    'token' => true,  // Ensure the token is included in the response
                ]
            ]);
    }

    #[Test]
    public function it_fails_registration_with_duplicate_email()
    {
        User::factory()->create(['email' => 'test@example.com']);
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $this->postJson('/api/register', $data)
            ->assertStatus(422);
    }

    #[Test]
    public function it_logs_in_with_valid_credentials()
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);
        $data = [
            'email' => $user->email,
            'password' => 'password123',
        ];
        $this->postJson('/api/login', $data)
            ->assertStatus(200)
            ->assertJsonStructure(['access_token', 'user']);
    }

    #[Test]
    public function it_fails_login_with_invalid_credentials()
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);
        $data = [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ];
        $this->postJson('/api/login', $data)
            ->assertStatus(401)
            ->assertJsonFragment(['error' => 'Invalid credentials provided.']);
    }

    #[Test]
    public function it_logs_out_current_session()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout')
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Logged out successfully']);
    }

    #[Test]
    public function it_logs_out_all_sessions()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout-all')
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'All sessions logged out successfully']);
    }

    #[Test]
    public function it_refreshes_token_for_authenticated_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/refresh-token')
            ->assertStatus(200)
            ->assertJsonStructure(['access_token', 'user']);
    }

    #[Test]
    public function it_sets_password_for_user_who_has_not_set_password()
    {
        $user = User::factory()->create(['set_password' => false]);
        $token = $user->createToken('test')->plainTextToken;
        $data = [
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ];
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/user/set-password', $data)
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Password set successfully']);
    }

    #[Test]
    public function it_fails_to_set_password_if_already_set()
    {
        $user = User::factory()->create(['set_password' => true]);
        $token = $user->createToken('test')->plainTextToken;
        $data = [
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ];
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/user/set-password', $data)
            ->assertStatus(403);
    }

    #[Test]
    public function it_changes_password_with_correct_current_password()
    {
        $user = User::factory()->create(['password' => Hash::make('oldpassword')]);
        $token = $user->createToken('test')->plainTextToken;
        $data = [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ];
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/user/change-password', $data)
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Password changed successfully']);
    }

    #[Test]
    public function it_fails_to_change_password_with_incorrect_current_password()
    {
        $user = User::factory()->create(['password' => Hash::make('oldpassword')]);
        $token = $user->createToken('test')->plainTextToken;
        $data = [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ];
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/user/change-password', $data)
            ->assertStatus(403);
    }

    #[Test]
    public function it_sends_password_reset_link_for_existing_email()
    {
        $user = User::factory()->create();
        Password::shouldReceive('sendResetLink')->once()->andReturn(Password::RESET_LINK_SENT);
        $this->postJson('/api/forgot-password', ['email' => $user->email])
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Reset link sent to your email.']);
    }

    #[Test]
    public function it_fails_to_send_password_reset_link_for_non_existing_email()
    {
        $this->postJson('/api/forgot-password', ['email' => 'nonexistent@example.com'])
            ->assertStatus(422);
    }

    #[Test]
    public function it_resets_password_with_valid_token_and_data()
    {
        Password::shouldReceive('reset')->once()->andReturn(Password::PASSWORD_RESET);
        $data = [
            'token' => Str::random(60),
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];
        $this->postJson('/api/reset-password', $data)
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Password reset successfully.']);
    }

    #[Test]
    public function it_fails_to_reset_password_with_invalid_token()
    {
        Password::shouldReceive('reset')->once()->andReturn('invalid_token');
        $data = [
            'token' => 'invalidtoken',
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];
        $this->postJson('/api/reset-password', $data)
            ->assertStatus(422);
    }

    #[Test]
    public function it_verifies_valid_token_returns_success()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/user/verify-token')
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Token is valid']);
    }

    #[Test]
    public function it_verifies_invalid_token_returns_unauthorized()
    {
        $this->getJson('/api/user/verify-token')
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }
}
