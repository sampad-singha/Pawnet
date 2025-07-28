<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class TokenNameService
{
    public static function generate(string $userAgent): string
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $platform = $agent->platform() ?: 'Unknown Platform';
        $browser = $agent->browser() ?: 'Unknown Browser';
        $device = $agent->device() ?: 'Unknown Device';

        return "{$browser} on {$platform} ({$device})";
    }
}

