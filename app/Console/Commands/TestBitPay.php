<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BitPayService;

class TestBitPay extends Command
{
    protected $signature = 'bitpay:test';
    protected $description = 'Test BitPay sandbox connection';

    public function handle(BitPayService $bitpay)
    {
        $this->info('Testing BitPay Sandbox Connection...');
        $this->newLine();

        // Test 1: Create sandbox account
        $this->info('1. Creating sandbox account...');
        try {
            $account = $bitpay->createSandboxAccount('AngoAPI Test');
            $this->info('   Account created successfully!');
            $this->info('   Secret Key: ' . ($account['sk'] ?? 'N/A'));
            $this->info('   Public Key: ' . ($account['pk'] ?? 'N/A'));
            $this->newLine();
        } catch (\Exception $e) {
            $this->error('   Failed to create account: ' . $e->getMessage());
            return 1;
        }

        // Test 2: Create a test payment
        $this->info('2. Creating test payment...');
        try {
            $payment = $bitpay->createPayment(
                1000, // 1,000 Kz
                '923000000', // Test mobile (approved)
                ['test' => true]
            );
            $this->info('   Payment created successfully!');
            $this->info('   Payment ID: ' . ($payment['id'] ?? 'N/A'));
            $this->info('   Status: ' . ($payment['status'] ?? 'N/A'));
            $this->newLine();
        } catch (\Exception $e) {
            $this->error('   Failed to create payment: ' . $e->getMessage());
            return 1;
        }

        // Test 3: Check payment status
        if (isset($payment['id'])) {
            $this->info('3. Checking payment status...');
            try {
                $status = $bitpay->getPayment($payment['id']);
                $this->info('   Status: ' . ($status['status'] ?? 'N/A'));
                $this->newLine();
            } catch (\Exception $e) {
                $this->error('   Failed to check status: ' . $e->getMessage());
            }
        }

        $this->info('All tests completed!');
        return 0;
    }
}
