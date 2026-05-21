<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestMidtransWebhook extends Command
{
    protected $signature = 'midtrans:test-webhook {--order-id=}';
    protected $description = 'Test Midtrans webhook with a real order';

    public function handle()
    {
        $this->line('');
        $this->line('═══════════════════════════════════════════════════════════');
        $this->line('  MIDTRANS WEBHOOK TEST');
        $this->line('═══════════════════════════════════════════════════════════');
        $this->line('');

        // Step 1: Find order
        $orderId = $this->option('order-id');
        if (!$orderId) {
            $order = Order::latest()->first();
            if (!$order) {
                $this->error('❌ No orders found. Create an order first.');
                return;
            }
            $this->info("ℹ️  Using latest order: {$order->kode_order}");
        } else {
            $order = Order::find($orderId);
            if (!$order) {
                $this->error("❌ Order #{$orderId} not found");
                return;
            }
        }

        $this->line('');
        $this->line('ORDER DETAILS:');
        $this->table(
            ['Field', 'Value'],
            [
                ['ID', $order->id],
                ['Order Code', $order->kode_order],
                ['Total', $order->total_bayar],
                ['Payment Status', $order->payment_status],
                ['Order Status', $order->status],
            ]
        );

        // Step 2: Find payment transaction
        $paymentTrx = PaymentTransaction::where('order_id', $order->id)
            ->latest()
            ->first();

        if (!$paymentTrx) {
            $this->warn('⚠️  No payment transaction found for this order');
            $this->line('Creating test transaction...');

            $paymentTrx = PaymentTransaction::create([
                'order_id' => $order->id,
                'order_id_midtrans' => 'ORDER-TEST-' . time(),
                'gross_amount' => $order->total_bayar,
                'transaction_status' => 'pending',
                'snap_token' => null,
                'raw_response' => json_encode([]),
            ]);

            $this->info('✓ Test transaction created');
        }

        $this->line('');
        $this->line('PAYMENT TRANSACTION DETAILS:');
        $this->table(
            ['Field', 'Value'],
            [
                ['ID', $paymentTrx->id],
                ['Midtrans Order ID', $paymentTrx->order_id_midtrans],
                ['Transaction ID', $paymentTrx->transaction_id ?? 'NULL'],
                ['Amount', $paymentTrx->gross_amount],
                ['Status', $paymentTrx->transaction_status],
                ['Snap Token', $paymentTrx->snap_token ? substr($paymentTrx->snap_token, 0, 30) . '...' : 'NULL'],
            ]
        );

        // Step 3: Simulate webhook
        $this->line('');
        $this->line('SIMULATING WEBHOOK PAYLOAD:');
        $this->line('');

        $testPayload = [
            'transaction_id' => 'test_trx_' . time(),
            'order_id' => $paymentTrx->order_id_midtrans,
            'merchant_id' => config('midtrans.merchant_id'),
            'amount' => $paymentTrx->gross_amount,
            'currency' => 'IDR',
            'transaction_time' => now()->subMinutes(1)->format('Y-m-d H:i:s'),
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'payment_type' => 'qris',
            'payment_code' => 'qris123',
            'gross_amount' => (string) $paymentTrx->gross_amount,
            'settlement_time' => now()->format('Y-m-d H:i:s'),
        ];

        $this->info('Test Payload:');
        $this->line(json_encode($paymentTrx->order_id_midtrans, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->line(json_encode($testPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Step 4: Test webhook handler
        $this->line('');
        $this->line('PROCESSING WEBHOOK:');
        $this->line('');

        try {
            $snapService = app(\App\Services\MidtransSnapService::class);
            $result = $snapService->processWebhookNotification($testPayload);

            $this->line('');
            $this->info('✓ WEBHOOK PROCESSED SUCCESSFULLY');
            $this->line('');
            $this->line('RESULT:');
            $this->table(
                ['Field', 'Value'],
                [
                    ['Order ID', $result['order']->kode_order],
                    ['Payment Status', $result['order']->payment_status],
                    ['Order Status', $result['order']->status],
                    ['Transaction Status', $result['transaction_status']],
                    ['Result Status', $result['result_status']],
                ]
            );

        } catch (\Exception $e) {
            $this->line('');
            $this->error('✗ WEBHOOK PROCESSING FAILED');
            $this->line('');
            $this->error('Error: ' . $e->getMessage());
            $this->line('');
            $this->line('File: ' . $e->getFile());
            $this->line('Line: ' . $e->getLine());
            return;
        }

        // Step 5: Verify database changes
        $this->line('');
        $this->line('VERIFYING DATABASE CHANGES:');
        $this->line('');

        $order->refresh();
        $paymentTrx->refresh();

        $this->table(
            ['Table', 'Field', 'New Value'],
            [
                ['orders', 'payment_status', $order->payment_status],
                ['orders', 'status', $order->status],
                ['payment_transactions', 'transaction_id', $paymentTrx->transaction_id ?? 'NULL'],
                ['payment_transactions', 'transaction_status', $paymentTrx->transaction_status],
                ['payment_transactions', 'fraud_status', $paymentTrx->fraud_status ?? 'NULL'],
            ]
        );

        // Step 6: Check logs
        $this->line('');
        $this->line('CHECKING LOGS:');
        $this->line('');

        Log::info('Test webhook command completed successfully', [
            'order_id' => $order->id,
            'kode_order' => $order->kode_order,
            'payment_status' => $order->payment_status,
        ]);

        $this->info('✓ Logs written to: storage/logs/laravel.log');

        $this->line('');
        $this->line('═══════════════════════════════════════════════════════════');
        $this->info('✓ WEBHOOK TEST COMPLETED SUCCESSFULLY');
        $this->line('═══════════════════════════════════════════════════════════');
        $this->line('');
        $this->line('Next steps:');
        $this->line('  1. Check logs: tail -f storage/logs/laravel.log');
        $this->line('  2. Test with Midtrans Dashboard: Settings → Test Notification');
        $this->line('  3. Verify order status is "paid" after test');
        $this->line('');
    }
}
