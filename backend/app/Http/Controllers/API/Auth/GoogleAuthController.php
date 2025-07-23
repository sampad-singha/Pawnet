<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Services\GoogleAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function callback(): JsonResponse
    {
        $data = $this->googleAuthService->handleGoogleCallback();

        return response()->json([
            'user' => $data['user'],
            'token' => $data['token'],
        ]);
    }
}
