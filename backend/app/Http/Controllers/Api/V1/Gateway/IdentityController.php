<?php

namespace App\Http\Controllers\Api\V1\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IdentityController extends Controller
{
    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bi_number' => ['required', 'string', 'max:20'],
            'full_name' => ['sometimes', 'string', 'max:255'],
            'date_of_birth' => ['sometimes', 'date'],
        ]);

        return response()->json([
            'valid' => true,
            'bi_number' => $validated['bi_number'],
            'full_name' => $validated['full_name'] ?? 'Cidadão Demo',
            'date_of_birth' => $validated['date_of_birth'] ?? '1990-01-01',
            'gender' => 'M',
            'nationality' => 'Angolana',
            'province_of_birth' => 'Luanda',
            'municipality_of_birth' => 'Município de Luanda',
            'issue_date' => '2020-06-15',
            'expiry_date' => '2030-06-15',
            'status' => 'Válido',
            'photo_url' => null,
            'environment' => 'sandbox',
        ]);
    }
}
