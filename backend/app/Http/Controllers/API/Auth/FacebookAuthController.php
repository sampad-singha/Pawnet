<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\FacebookAuthService;
use Illuminate\Http\JsonResponse;

class FacebookAuthController extends Controller
{
    public function __construct(
        protected FacebookAuthService $facebookAuthService
    ) {}

    /**
     * Get Facebook OAuth redirect URL
     */
    public function redirect(): JsonResponse
    {
        $url = $this->facebookAuthService->redirectToFacebook();
        return response()->json(['url' => $url]);
    }

    /**
     * Handle Facebook callback and return user info + token
     */
    public function callback(): JsonResponse
    {
        $data = $this->facebookAuthService->handleFacebookCallback();

        return response()->json([
            'user' => $data['user'],
            'token' => $data['token'],
        ]);
    }
}
