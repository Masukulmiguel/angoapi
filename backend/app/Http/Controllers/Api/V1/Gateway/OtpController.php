<?php

namespace App\Http\Controllers\Api\V1\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'purpose' => ['sometimes', 'string', 'max:100'],
            'expires_in' => ['sometimes', 'integer', 'min:60', 'max:600'],
        ]);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $token = Str::random(32);
        $expiresIn = $validated['expires_in'] ?? 300;

        Cache::put(
            "otp:{$validated['phone']}:{$token}",
            $code,
            $expiresIn
        );

        return response()->json([
            'success' => true,
            'token' => $token,
            'phone' => $validated['phone'],
            'expires_in' => $expiresIn,
            'environment' => 'sandbox',
            'code' => $code,
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'token' => ['required', 'string'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $cacheKey = "otp:{$validated['phone']}:{$validated['token']}";
        $storedCode = Cache::get($cacheKey);

        if (!$storedCode) {
            return response()->json([
                'valid' => false,
                'message' => 'OTP expired or not found.',
            ], 422);
        }

        if ($storedCode !== $validated['code']) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid OTP code.',
            ], 422);
        }

        Cache::forget($cacheKey);

        return response()->json([
            'valid' => true,
            'phone' => $validated['phone'],
            'message' => 'OTP verified successfully.',
            'environment' => 'sandbox',
        ]);
    }
}
