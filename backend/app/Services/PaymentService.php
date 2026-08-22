<?php

namespace App\Services;

use App\Models\ApiKey;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;

class PaymentService
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
        protected ApiKeyService $apiKeyService,
    ) {}

    public function approve(Payment $payment, int $adminId): Payment
    {
        $payment->approve($adminId);

        $subscription = $payment->subscription;
        if ($subscription && $subscription->status === Subscription::STATUS_PENDING) {
            $this->subscriptionService->activate($subscription);

            $this->apiKeyService->generate(
                $payment->user,
                $subscription
            );
        }

        return $payment->fresh(['user', 'subscription', 'proofs']);
    }

    public function reject(Payment $payment, int $adminId, string $reason): Payment
    {
        $payment->reject($adminId, $reason);

        return $payment->fresh(['user', 'subscription', 'proofs']);
    }

    public function getPending(): Collection
    {
        return Payment::where('status', Payment::STATUS_PENDING)
            ->with(['user', 'subscription', 'proofs'])
            ->latest()
            ->get();
    }
}
