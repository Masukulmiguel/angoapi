<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionService
{
    public function createForUser(User $user, Plan $plan): Subscription
    {
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => Subscription::STATUS_PENDING,
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'amount' => $plan->price_monthly,
            'currency' => 'USD',
            'status' => Payment::STATUS_PENDING,
        ]);

        return $subscription;
    }

    public function activate(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => Subscription::STATUS_ACTIVE,
            'start_date' => now(),
            'end_date' => now()->addMonths(12),
        ]);

        return $subscription->fresh();
    }

    public function expire(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => Subscription::STATUS_EXPIRED,
        ]);

        return $subscription->fresh();
    }

    public function checkExpirations(): int
    {
        $expired = Subscription::active()
            ->where('end_date', '<', now())
            ->update(['status' => Subscription::STATUS_EXPIRED]);

        return $expired;
    }

    public function getActiveForUser(User $user): ?Subscription
    {
        return $user->subscriptions()
            ->active()
            ->with('plan')
            ->latest()
            ->first();
    }
}
