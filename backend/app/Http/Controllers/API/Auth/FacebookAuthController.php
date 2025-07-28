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
    public function callback()
    {
        $data = $this->facebookAuthService->handleFacebookCallback();

//        return response()->json([
//            'user' => $data['user'],
//            'token' => $data['token'],
//        ]);
        $redirectUrl = config('app.frontend_url') . '/auth/facebook/callback?token=' . $data['token'];
//        dd($redirectUrl);
        return redirect($redirectUrl);
    }
}
