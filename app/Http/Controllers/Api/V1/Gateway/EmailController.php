<?php

namespace App\Http\Controllers\Api\V1\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => ['required', 'email'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'from' => ['sometimes', 'email'],
            'cc' => ['sometimes', 'array'],
            'cc.*' => ['email'],
            'bcc' => ['sometimes', 'array'],
            'bcc.*' => ['email'],
        ]);

        return response()->json([
            'success' => true,
            'message_id' => Str::uuid(),
            'to' => $validated['to'],
            'from' => $validated['from'] ?? 'noreply@angoapi.com',
            'subject' => $validated['subject'],
            'status' => 'sent',
            'environment' => 'sandbox',
        ]);
    }
}
