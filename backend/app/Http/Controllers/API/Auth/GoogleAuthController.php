<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\GoogleAuthService;
use Illuminate\Http\JsonResponse;

class GoogleAuthController extends Controller
{
    protected GoogleAuthService $googleAuthService;

    public function __construct(GoogleAuthService $googleAuthService)
    {
        $this->googleAuthService = $googleAuthService;
    }

    /**
     * Get Google OAuth redirect URL
     */
    public function redirect(): JsonResponse
    {
        $url = $this->googleAuthService->redirectToGoogle();
        return response()->json(['url' => $url]);
    }

    /**
     * Handle Google callback and return user info + token
     */
    public function callback()
    {
        $data = $this->googleAuthService->handleGoogleCallback();
        $token = $data['token'];

//        return response()->json([
//            'user' => $data['user'],
//            'token' => $data['token'],
//        ]);
        return redirect("http://localhost:5173/auth/google/callback?token=$token");
    }
}
