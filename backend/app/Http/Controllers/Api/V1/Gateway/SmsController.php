<?php

namespace App\Http\Controllers\Api\V1\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SmsController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => ['required', 'string'],
            'message' => ['required', 'string', 'max:1600'],
            'sender_id' => ['sometimes', 'string', 'max:20'],
        ]);

        return response()->json([
            'success' => true,
            'message_id' => Str::uuid(),
            'to' => $validated['to'],
            'sender_id' => $validated['sender_id'] ?? 'AngoAPI',
            'status' => 'sent',
            'cost' => 0,
            'environment' => 'sandbox',
        ]);
    }
}
