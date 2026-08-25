<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $authorization = $request->header('Authorization');

        if (!$authorization || !str_starts_with($authorization, 'Bearer ')) {
            return response()->json(['message' => 'API key required.'], 401);
        }

        $plainKey = substr($authorization, 7);
        $keyHash = hash('sha256', $plainKey);

        $apiKey = ApiKey::where('key_hash', $keyHash)->first();

        if (!$apiKey) {
            return response()->json(['message' => 'Invalid API key.'], 401);
        }

        if (!$apiKey->isValid()) {
            return response()->json(['message' => 'API key is inactive or expired.'], 403);
        }

        if (!$apiKey->subscription || !$apiKey->subscription->isActive()) {
            return response()->json(['message' => 'No active subscription for this API key.'], 403);
        }

        $apiKey->recordUsage();

        $request->merge([
            'api_key' => $apiKey,
            'api_user' => $apiKey->user,
        ]);

        return $next($request);
    }
}
