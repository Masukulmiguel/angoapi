<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\BitPayService;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        protected BitPayService $bitpay,
        protected SubscriptionService $subscriptionService,
    ) {}

    /**
     * Create a Multicaixa Express payment.
     * Returns payment id and status.
     */
    public function createPayment(Request $request): JsonResponse
    {
        $request->validate([
            'plan_id' => ['required', 'exists:plans'],
            'mobile' => ['required', 'string', 'min:9', 'max:12'],
        ]);

        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        $result = $this->bitpay->createPayment(
            (float) $plan->price_monthly,
            $request->mobile,
            [
                'plan_id' => $plan->id,
                'user_id' => $request->user()->id,
            ]
        );

        // Create subscription (pending)
        $subscription = $this->subscriptionService->createForUser(
            $request->user(),
            $plan
        );

        // Store payment ID for status checking
        $subscription->update([
            'external_id' => $result['id'] ?? null,
        ]);

        return response()->json([
            'payment' => $result,
            'subscription_id' => $subscription->id,
            'message' => 'Payment request sent. Check your Multicaixa Express app to confirm.',
        ]);
    }

    /**
     * Webhook callback from BitPay.
     * Verifies signature before processing.
     */
    public function callback(Request $request): JsonResponse
    {
        // Verify webhook signature
        $signature = $request->header('X-BitPay-Signature');
        $secret = config('services.bitpay.webhook_secret');

        if ($secret && $signature) {
            $rawBody = $request->getContent();
            $expectedHash = hash_hmac('sha256', $rawBody, $secret);

            if (!hash_equals($expectedHash, $signature)) {
                Log::warning('BitPay webhook: invalid signature');
                return response()->json(['message' => 'Invalid signature'], 401);
            }
        }

        Log::info('BitPay callback received', $request->all());

        $data = $request->all();
        $paymentId = $data['id'] ?? null;
        $status = $data['status'] ?? null;

        if (!$paymentId) {
            return response()->json(['message' => 'Invalid callback'], 400);
        }

        // Find subscription by external_id
        $subscription = Subscription::where('external_id', $paymentId)->first();

        if (!$subscription) {
            Log::warning('BitPay callback: subscription not found', ['payment_id' => $paymentId]);
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        if ($status === 'SUCCEEDED' || $status === 'APPROVED') {
            $this->subscriptionService->activate($subscription);

            $subscription->payments()->latest()->first()?->update([
                'status' => 'approved',
                'paid_at' => now(),
            ]);

            Log::info('BitPay: subscription activated', ['subscription_id' => $subscription->id]);
        } else {
            $subscription->update(['status' => 'rejected']);
            Log::info('BitPay: payment rejected', ['subscription_id' => $subscription->id, 'status' => $status]);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Check payment status.
     */
    public function status(Request $request, string $paymentId): JsonResponse
    {
        try {
            $payment = $this->bitpay->getPayment($paymentId);
            return response()->json($payment);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    /**
     * Legacy: store payment proof (kept for backward compatibility).
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Please use the new payment flow via /payments/multicaixa-express',
            'redirect' => '/payments/multicaixa-express',
        ], 400);
    }

    public function index(Request $request)
    {
        $payments = $request->user()
            ->payments()
            ->with('proofs')
            ->latest()
            ->get();

        return \App\Http\Resources\PaymentResource::collection($payments);
    }

    public function show(Request $request, \App\Models\Payment $payment)
    {
        if ($payment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $payment->load('proofs');

        return new \App\Http\Resources\PaymentResource($payment);
    }
}
