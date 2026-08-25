<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovePaymentRequest;
use App\Http\Requests\RejectPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {}

    public function index()
    {
        $payments = $this->paymentService->getPending();

        return PaymentResource::collection($payments);
    }

    public function approve(ApprovePaymentRequest $request, Payment $payment): JsonResponse
    {
        $payment = $this->paymentService->approve($payment, $request->user()->id);

        return response()->json([
            'payment' => new PaymentResource($payment),
            'message' => 'Payment approved successfully.',
        ]);
    }

    public function reject(RejectPaymentRequest $request, Payment $payment): JsonResponse
    {
        $payment = $this->paymentService->reject(
            $payment,
            $request->user()->id,
            $request->rejection_reason
        );

        return response()->json([
            'payment' => new PaymentResource($payment),
            'message' => 'Payment rejected.',
        ]);
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'subscription.plan', 'proofs', 'approver']);

        return new PaymentResource($payment);
    }
}
