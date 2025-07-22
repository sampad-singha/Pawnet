<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private \App\Services\Interfaces\AuthServiceInterface $authService
    ) {}

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = $this->authService->register($data);

        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            $response = $this->authService->login($credentials);
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function logout()
    {
        $user = $this->authService->getUser();
        if ($user) {
            $this->authService->logout($user);
            return response()->json(['message' => 'Logged out successfully'], 200);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function refreshToken()
    {
        $user = $this->authService->getUser();
        if ($user) {
            $response = $this->authService->refreshToken($user);
            return response()->json($response, 200);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
