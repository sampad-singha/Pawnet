<?php
namespace App\Services\Auth;

use App\Events\NewLogin;
use App\Events\PasswordChange;
use App\Exceptions\API\Auth\InvalidCredentialsException;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Auth\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        protected TokenNameService $tokenNameService
    ) {}

    public function register(array $data): User
    {
        $data['email'] = Str::lower(trim($data['email']));
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return $user->setAttribute('token', $token);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function login(array $credentials, string $userAgent): array
    {
        $email = Str::lower(trim($credentials['email']));
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new InvalidCredentialsException('Invalid credentials');
        }
        event(new NewLogin($user));
        return $this->generateAuthResponse($user, $userAgent);
    }

    public function logout(User $user, ?string $userAgent = null): void
    {
        if ($userAgent) {
            $tokenName = $this->tokenNameService->generate($userAgent);
            $user->tokens()->where('name', $tokenName)->delete();
            $user->currentAccessToken()?->delete();
        } else {
            $user->tokens()->delete();
        }
    }

    public function getUser(): ?User
    {
        return auth()->user();
    }

    public function refreshToken(User $user, string $userAgent): array
    {
        $this->logout($user);
        return $this->generateAuthResponse($user, $userAgent);
    }

    private function generateAuthResponse(User $user, string $userAgent): array
    {
        $tokenName = $this->tokenNameService->generate($userAgent);
        $token = $user->createToken($tokenName)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ];
    }

    /**
     * @throws \Exception
     */
    public function setPassword(User $user, string $newPassword): void
    {
        if ($user->set_password) {
            throw new \Exception('Password already set.');
        }

        $this->userRepository->updatePassword($user, Hash::make($newPassword));
        $this->userRepository->markPasswordSet($user);
        event(new PasswordChange($user));
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new \Exception('Current password is incorrect.');
        }

        $this->userRepository->updatePassword($user, Hash::make($newPassword));
        event(new PasswordChange($user));
    }

    public function sendResetLink(string $email): void
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }
    }

    public function resetPassword(array $data): void
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $this->userRepository->updatePassword($user, bcrypt($password));
                $this->userRepository->markPasswordSet($user);
                event(new PasswordChange($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'token' => __($status),
            ]);
        }

    }
}
