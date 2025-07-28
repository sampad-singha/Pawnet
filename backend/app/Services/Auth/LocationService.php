<?php
namespace App\Services\Auth;

use Illuminate\Support\Facades\Http;

class LocationService
{
    public function getLocation(string $ip): array
    {
        $response = Http::get("https://ipinfo.io/{$ip}?token=" . config('services.ipinfo.token'));

        if (!$response->successful()) {
            return [];
        }

        $data = $response->json();
        $coords = explode(',', $data['loc'] ?? ',');

        return [
            'ip' => $data['ip'] ?? null,
            'city' => $data['city'] ?? null,
            'region' => $data['region'] ?? null,
            'country' => $data['country'] ?? null,
            'latitude' => $coords[0] ?? null,
            'longitude' => $coords[1] ?? null,
        ];
    }


}
