<?php

namespace App\Exceptions\API\Auth;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function render($request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => $this->getMessage()
        ], 401);
    }
}
