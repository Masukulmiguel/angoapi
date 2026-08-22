<?php

namespace App\Http\Controllers\Api\V1\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    public function validate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'country_code' => ['sometimes', 'string', 'default:AO'],
        ]);

        $phone = preg_replace('/\s+/', '', $validated['phone']);

        if (!preg_match('/^(\+?244)?[9][123456789][0-9]{7}$/', $phone)) {
            return response()->json([
                'valid' => false,
                'phone' => $phone,
                'message' => 'Invalid Angolan phone number format.',
            ], 422);
        }

        $cleanPhone = $phone;
        if (!str_starts_with($phone, '+244')) {
            $cleanPhone = '+244' . ltrim($phone, '0');
        }

        $operator = match(substr($cleanPhone, 4, 1)) {
            '9' => 'Unitel (Movicel)',
            default => 'Desconhecido',
        };

        return response()->json([
            'valid' => true,
            'phone' => $cleanPhone,
            'international' => $cleanPhone,
            'local' => ltrim($cleanPhone, '+244'),
            'country_code' => '+244',
            'country' => 'Angola',
            'operator' => $operator,
            'environment' => 'sandbox',
        ]);
    }
}
