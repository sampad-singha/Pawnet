<?php

namespace App\Http\Controllers\API\Auth;

use App\Exceptions\API\Auth\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Services\Auth\Interfaces\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = $this->authService->register($data);
            return response()->json(['user' => $user], 201);
        } catch (\Throwable $e) {
            Log::error('Registration failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Registration failed'], 500);
        }
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $userAgent = $request->header('User-Agent') ?? 'Unknown device';

        try {
            $response = $this->authService->login($credentials,$userAgent);
            return response()->json($response, 200);
        } catch (InvalidCredentialsException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        } catch (\Throwable $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Login failed'], 500);
        }
    }

    public function logout(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            throw new UnauthorizedHttpException('', 'Unauthorized');
        }

        $userAgent = $request->header('User-Agent') ?? null;

        $this->authService->logout($user, $userAgent);

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function logoutAll(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            throw new UnauthorizedHttpException('', 'Unauthorized');
        }

        $this->authService->logout($user);

        return response()->json(['message' => 'All sessions logged out successfully'], 200);
    }

    public function refreshToken(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            throw new UnauthorizedHttpException('', 'Unauthorized');
        }

        $userAgent = $request->header('User-Agent') ?? 'Unknown device';

        $response = $this->authService->refreshToken($user, $userAgent);
        return response()->json($response, 200);
    }


    public function setPassword(Request $request)
    {
        $data = $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $this->authService->setPassword(auth()->user(), $data['new_password']);
            return response()->json(['message' => 'Password set successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $this->authService->changePassword(auth()->user(), $data['current_password'], $data['new_password']);
            return response()->json(['message' => 'Password changed successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}
