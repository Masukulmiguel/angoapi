<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = $request->user()
            ->subscriptions()
            ->with('plan')
            ->latest()
            ->get();

        return SubscriptionResource::collection($subscriptions);
    }

    public function show(Request $request, Subscription $subscription)
    {
        if ($subscription->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $subscription->load('plan');

        return new SubscriptionResource($subscription);
    }

    public function current(Request $request)
    {
        $subscription = $request->user()
            ->subscriptions()
            ->active()
            ->with('plan')
            ->latest()
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription.'], 404);
        }

        return new SubscriptionResource($subscription);
    }
}
