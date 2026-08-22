<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->latest()
            ->paginate(15);

        return SubscriptionResource::collection($subscriptions);
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'plan', 'payments', 'apiKeys']);

        return new SubscriptionResource($subscription);
    }
}
