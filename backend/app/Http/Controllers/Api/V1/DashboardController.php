<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ApiRequest;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        $activeSubscription = $this->subscriptionService->getActiveForUser($user);

        $totalRequests = ApiRequest::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->count();

        $requestLimit = 0;
        $remainingRequests = null;

        if ($activeSubscription && $activeSubscription->plan) {
            $requestLimit = $activeSubscription->plan->request_limit_monthly;
            $remainingRequests = max(0, $requestLimit - $totalRequests);
        }

        $recentActivity = $user->apiRequests()
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'active_subscription' => $activeSubscription ? [
                'id' => $activeSubscription->id,
                'plan' => $activeSubscription->plan->name ?? null,
                'status' => $activeSubscription->status,
                'end_date' => $activeSubscription->end_date,
                'days_until_expiration' => $activeSubscription->daysUntilExpiration(),
            ] : null,
            'requests_this_month' => $totalRequests,
            'request_limit' => $requestLimit,
            'remaining_requests' => $remainingRequests,
            'recent_activity' => $recentActivity,
        ]);
    }
}
