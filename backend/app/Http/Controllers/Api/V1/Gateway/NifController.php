<?php

namespace App\Http\Controllers\Api\V1\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NifController extends Controller
{
    public function validate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nif' => ['required', 'string', 'size:10', 'digits'],
        ]);

        return response()->json([
            'valid' => true,
            'nif' => $validated['nif'],
            'company_name' => 'Empresa Demo Lda.',
            'company_type' => 'Sociedade por Quotas',
            'status' => 'Activo',
            'registration_date' => '2020-01-15',
            'activity' => 'Comércio Geral',
            'province' => 'Luanda',
            'municipality' => 'Município de Luanda',
            'environment' => 'sandbox',
        ]);
    }
}
