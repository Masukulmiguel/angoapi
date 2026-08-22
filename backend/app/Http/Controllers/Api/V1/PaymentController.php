<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentProofRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\PaymentProof;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
    ) {}

    public function store(StorePaymentProofRequest $request): JsonResponse
    {
        $subscription = $this->subscriptionService->createForUser(
            $request->user(),
            $request->input('plan')
        );

        $payment = Payment::create([
            'user_id' => $request->user()->id,
            'subscription_id' => $subscription->id,
            'amount' => $request->amount,
            'currency' => 'USD',
            'method' => 'manual',
            'status' => Payment::STATUS_PENDING,
            'paid_by_name' => $request->paid_by_name,
            'paid_by_phone' => $request->paid_by_phone,
            'payment_date' => $request->payment_date,
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('payment-proofs', 'public');

            PaymentProof::create([
                'payment_id' => $payment->id,
                'user_id' => $request->user()->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'payment' => new PaymentResource($payment->fresh(['proofs'])),
            'message' => 'Payment proof submitted successfully.',
        ], 201);
    }

    public function index(Request $request)
    {
        $payments = $request->user()
            ->payments()
            ->with('proofs')
            ->latest()
            ->get();

        return PaymentResource::collection($payments);
    }

    public function show(Request $request, Payment $payment)
    {
        if ($payment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $payment->load('proofs');

        return new PaymentResource($payment);
    }
}
