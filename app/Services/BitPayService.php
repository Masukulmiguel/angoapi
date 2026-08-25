<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BitPayService
{
    private string $baseUrl;
    private string $secretKey;
    private string $publicKey;

    public function __construct()
    {
        $this->baseUrl = config('services.bitpay.base_url');
        $this->secretKey = config('services.bitpay.secret_key');
        $this->publicKey = config('services.bitpay.public_key');
    }

    /**
     * Create a payment intent for Multicaixa Express.
     * Returns payment id, status, and checkout info.
     */
    public function createPayment(float $amount, string $customerMobile, array $metadata = []): array
    {
        $webhookUrl = config('services.bitpay.webhook_url');

        $payload = [
            'amount' => (int) $amount,
            'currency' => 'AOA',
            'payment_method' => 'multicaixa_express',
            'customer' => [
                'mobile' => $customerMobile,
            ],
            'metadata' => $metadata,
        ];

        if ($webhookUrl) {
            $payload['webhook_url'] = $webhookUrl;
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Idempotency-Key' => \Str::uuid()->toString(),
        ])->post($this->baseUrl . '/v1/payment_intents', $payload);

        if ($response->failed()) {
            Log::error('BitPay payment creation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Failed to create payment: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Get payment status by ID.
     */
    public function getPayment(string $paymentId): array
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->secretKey,
        ])->get($this->baseUrl . '/v1/payment_intents/' . $paymentId);

        if ($response->failed()) {
            throw new \Exception('Failed to get payment: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Create a sandbox account (for testing only).
     */
    public function createSandboxAccount(string $name): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api-sandbox.bitpay.ao/v1/sandbox/accounts', [
            'name' => $name,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to create sandbox account: ' . $response->body());
        }

        return $response->json();
    }
}
