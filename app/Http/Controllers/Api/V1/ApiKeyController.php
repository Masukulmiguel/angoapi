<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApiKeyRequest;
use App\Http\Resources\ApiKeyResource;
use App\Models\ApiKey;
use App\Services\ApiKeyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function __construct(
        protected ApiKeyService $apiKeyService,
    ) {}

    public function index(Request $request)
    {
        $keys = $this->apiKeyService->getForUser($request->user());

        return ApiKeyResource::collection($keys);
    }

    public function store(StoreApiKeyRequest $request): JsonResponse
    {
        $subscription = $request->user()
            ->subscriptions()
            ->active()
            ->latest()
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription.'], 422);
        }

        $result = $this->apiKeyService->generate($request->user(), $subscription);

        return response()->json([
            'key' => $result['key'],
            'api_key' => new ApiKeyResource($result['api_key']),
            'message' => 'API key generated. Store it securely - it won\'t be shown again.',
        ], 201);
    }

    public function revoke(Request $request, ApiKey $key): JsonResponse
    {
        if ($key->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $this->apiKeyService->revoke($key);

        return response()->json(['message' => 'API key revoked.']);
    }

    public function regenerate(Request $request, ApiKey $key): JsonResponse
    {
        if ($key->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $result = $this->apiKeyService->regenerate($key);

        return response()->json([
            'key' => $result['key'],
            'api_key' => new ApiKeyResource($result['api_key']),
            'message' => 'API key regenerated. Store it securely - it won\'t be shown again.',
        ]);
    }

    public function toggle(Request $request, ApiKey $key): JsonResponse
    {
        if ($key->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $key->update(['is_active' => !$key->is_active]);

        return response()->json([
            'api_key' => new ApiKeyResource($key->fresh()),
        ]);
    }
}
