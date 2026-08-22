<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiKeyResource;
use App\Models\ApiKey;
use App\Services\ApiKeyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminApiKeyController extends Controller
{
    public function __construct(
        protected ApiKeyService $apiKeyService,
    ) {}

    public function index()
    {
        $keys = ApiKey::with(['user', 'subscription.plan'])
            ->latest()
            ->paginate(15);

        return ApiKeyResource::collection($keys);
    }

    public function revoke(ApiKey $key): JsonResponse
    {
        $this->apiKeyService->revoke($key);

        return response()->json(['message' => 'API key revoked.']);
    }
}
