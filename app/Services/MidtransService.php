<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\CoreApi;
use Midtrans\Transaction as MidtransTransaction;

class MidtransService
{
    /**
     * Initialize Midtrans configuration
     */
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.environment') === 'production';
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Create QRIS charge transaction for an order
     *
     * @param Order $order
     * @return array
     * @throws \Exception
     */
    public function createQrisCharge(Order $order): array
    {
        // Validate order is unpaid
        if ($order->payment_status !== Order::PAYMENT_UNPAID) {
            throw new \Exception('Pesanan ini sudah dibayar.');
        }

        // Validate payment method is QRIS
        if (!$order->isQRISPayment()) {
            throw new \Exception('Metode pembayaran bukan QRIS.');
        }

        // Check if there's already an active transaction
        $existingTransaction = PaymentTransaction::where('order_id', $order->id)
            ->where('transaction_status', PaymentTransaction::STATUS_PENDING)
            ->latest()
            ->first();

        if ($existingTransaction) {
            return [
                'transaction' => $existingTransaction,
                'qr_image_url' => $existingTransaction->qr_image_url,
                'expiry_time' => $existingTransaction->expiry_time,
                'is_new' => false,
            ];
        }

        // Generate unique order ID for Midtrans
        $orderIdMidtrans = 'ORDER-' . $order->kode_order . '-' . time();

        // Set transaction expiry
        $expiryDuration = config('midtrans.expiry_duration', 60);
        $expiryUnit = config('midtrans.expiry_unit', 'minutes');

        // Build transaction details
        $transactionDetails = [
            'order_id' => $orderIdMidtrans,
            'gross_amount' => (int) round($order->total_bayar),
        ];

        // Build customer details
        $customerDetails = [
            'first_name' => $order->nama_pelanggan,
            'phone' => $order->nomor_hp,
        ];

        // Build item details
        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => (string) $item->menu_id,
                'price' => (int) round($item->harga),
                'quantity' => $item->qty,
                'name' => $item->nama_menu,
            ];
        }

        // Build QRIS specific parameters
        // Using gopay as QRIS acquirer (most common, auto-generates QR code)
        $params = [
            'payment_type' => 'gopay',
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'gopay' => [
                'enable_callback' => true,
                'callback_url' => route('payment.qris.status', $order->kode_order),
            ],
            'expiry' => [
                'duration' => (int) $expiryDuration,
                'unit' => $expiryUnit,
            ],
            'custom_field1' => $order->kode_order,
            'custom_field2' => route('payment.qris.status', $order->kode_order),
        ];

        Log::info('Midtrans QRIS Charge Request', [
            'order_id' => $order->id,
            'kode_order' => $order->kode_order,
            'amount' => $transactionDetails['gross_amount'],
        ]);

        try {
            // Use Core API to create QRIS transaction
            $response = CoreApi::charge($params);

            Log::info('Midtrans QRIS Charge Response', [
                'order_id' => $order->id,
                'response' => json_encode($response),
            ]);

            // Parse response
            $transactionId = $response->transaction_id ?? null;
            $transactionStatus = $response->transaction_status ?? 'pending';
            $paymentType = $response->payment_type ?? 'qris';

            // Get QRIS details - the QR code is in actions
            $qrImageUrl = null;
            $qrString = null;
            $actions = [];

            // For QRIS, Midtrans returns the QR code URL in actions array
            if (isset($response->actions) && is_array($response->actions)) {
                $actions = $response->actions;
                foreach ($response->actions as $action) {
                    if (isset($action->name)) {
                        if ($action->name === 'generate-qr-code') {
                            $qrImageUrl = $action->url ?? null;
                        }
                    }
                }
            }

            // Alternative: if QR image URL is in qr_url field
            if (!$qrImageUrl && isset($response->qr_url)) {
                $qrImageUrl = $response->qr_url;
            }

            // Get expiry time
            $expiryTime = null;
            if (isset($response->expiry_time)) {
                $expiryTime = $response->expiry_time;
            } elseif (isset($response->transaction_details->expiry_time)) {
                $expiryTime = $response->transaction_details->expiry_time;
            }

            // Calculate expiry from duration if not provided
            if (!$expiryTime) {
                $expiryTime = now()->addMinutes((int) $expiryDuration);
            } else {
                try {
                    $expiryTime = \Carbon\Carbon::parse($expiryTime);
                } catch (\Exception $e) {
                    $expiryTime = now()->addMinutes((int) $expiryDuration);
                }
            }

            // Handle fraud status
            $fraudStatus = $response->fraud_status ?? null;

            // Use DB transaction to save payment record
            $paymentTransaction = DB::transaction(function () use (
                $order, $transactionId, $orderIdMidtrans, $transactionDetails,
                $transactionStatus, $paymentType, $fraudStatus,
                $qrImageUrl, $qrString, $actions, $expiryTime, $response
            ) {
                // If there's an old pending transaction, expire it
                PaymentTransaction::where('order_id', $order->id)
                    ->where('transaction_status', PaymentTransaction::STATUS_PENDING)
                    ->update(['transaction_status' => PaymentTransaction::STATUS_EXPIRE]);

                // Create new payment transaction record
                return PaymentTransaction::create([
                    'order_id' => $order->id,
                    'transaction_id' => $transactionId,
                    'order_id_midtrans' => $orderIdMidtrans,
                    'gross_amount' => $transactionDetails['gross_amount'],
                    'payment_type' => $paymentType,
                    'transaction_status' => $transactionStatus,
                    'fraud_status' => $fraudStatus,
                    'qr_string' => $qrString,
                    'qr_image_url' => $qrImageUrl,
                    'actions' => json_encode($actions),
                    'expiry_time' => $expiryTime,
                    'raw_response' => json_encode($response),
                ]);
            });

            return [
                'transaction' => $paymentTransaction,
                'qr_image_url' => $qrImageUrl,
                'expiry_time' => $expiryTime,
                'is_new' => true,
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans QRIS Charge Failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new \Exception('Gagal membuat pembayaran QRIS: ' . $e->getMessage());
        }
    }

    /**
     * Verify transaction status from Midtrans
     *
     * @param string $orderIdMidtrans
     * @return object|null
     */
    public function verifyTransaction(string $orderIdMidtrans): ?object
    {
        try {
            $status = MidtransTransaction::status($orderIdMidtrans);
            return $status;
        } catch (\Exception $e) {
            Log::error('Midtrans Verify Transaction Failed', [
                'order_id_midtrans' => $orderIdMidtrans,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Process webhook notification from Midtrans
     *
     * @param array $notification
     * @return array
     * @throws \Exception
     */
    public function processWebhookNotification(array $notification): array
    {
        // Log notification
        Log::info('Midtrans Webhook Notification Received', [
            'notification' => json_encode($notification),
        ]);

        // Validate required fields
        if (!isset($notification['order_id']) || !isset($notification['transaction_status'])) {
            throw new \Exception('Invalid notification: missing required fields.');
        }

        $orderIdMidtrans = $notification['order_id'];
        $transactionStatus = $notification['transaction_status'];
        $fraudStatus = $notification['fraud_status'] ?? null;
        $transactionId = $notification['transaction_id'] ?? null;
        $paymentType = $notification['payment_type'] ?? null;
        $grossAmount = $notification['gross_amount'] ?? 0;
        $settlementTime = $notification['settlement_time'] ?? null;

        // Verify transaction with Midtrans API for security
        $verifiedStatus = $this->verifyTransaction($orderIdMidtrans);

        if ($verifiedStatus) {
            // Use the verified status data
            $transactionStatus = $verifiedStatus->transaction_status ?? $transactionStatus;
            $fraudStatus = $verifiedStatus->fraud_status ?? $fraudStatus;
            $transactionId = $verifiedStatus->transaction_id ?? $transactionId;
            $paymentType = $verifiedStatus->payment_type ?? $paymentType;
            $grossAmount = $verifiedStatus->gross_amount ?? $grossAmount;
            $settlementTime = $verifiedStatus->settlement_time ?? $settlementTime;
        }

        // Find the payment transaction
        $paymentTransaction = PaymentTransaction::where('order_id_midtrans', $orderIdMidtrans)->first();

        if (!$paymentTransaction) {
            throw new \Exception("Payment transaction not found for order_id: {$orderIdMidtrans}");
        }

        $order = $paymentTransaction->order;

        if (!$order) {
            throw new \Exception("Order not found for payment transaction: {$paymentTransaction->id}");
        }

        // Update payment transaction
        $paymentTransaction->update([
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $paymentType,
            'transaction_id' => $transactionId ?? $paymentTransaction->transaction_id,
            'settlement_time' => $settlementTime ? \Carbon\Carbon::parse($settlementTime) : null,
            'raw_response' => json_encode($notification),
        ]);

        // Determine actual status based on Midtrans status mapping
        switch ($transactionStatus) {
            case 'settlement':
            case 'capture':
                // For capture, check fraud status for credit cards
                if ($transactionStatus === 'capture' && $fraudStatus === 'deny') {
                    // Transaction denied
                    $this->updateOrderStatus($order, Order::PAYMENT_UNPAID, 'dibatalkan');
                    $resultStatus = 'failed';
                } elseif ($transactionStatus === 'capture' && $fraudStatus === 'challenge') {
                    // Transaction challenged, still pending
                    $resultStatus = 'pending';
                } else {
                    // Success!
                    $this->updateOrderStatus($order, Order::PAYMENT_PAID, null);
                    $resultStatus = 'paid';
                }
                break;

            case 'pending':
                $resultStatus = 'pending';
                break;

            case 'deny':
            case 'cancel':
                $this->updateOrderStatus($order, Order::PAYMENT_UNPAID, null);
                $resultStatus = 'failed';
                break;

            case 'expire':
                $this->updateOrderStatus($order, Order::PAYMENT_UNPAID, null);
                $resultStatus = 'expired';
                break;

            default:
                $resultStatus = $transactionStatus;
                break;
        }

        return [
            'order' => $order,
            'payment_transaction' => $paymentTransaction,
            'transaction_status' => $transactionStatus,
            'result_status' => $resultStatus,
        ];
    }

    /**
     * Update order payment status and optionally order status
     *
     * @param Order $order
     * @param string $paymentStatus
     * @param string|null $orderStatus
     */
    private function updateOrderStatus(Order $order, string $paymentStatus, ?string $orderStatus): void
    {
        $updateData = ['payment_status' => $paymentStatus];

        // If payment is paid, update order status to 'diproses' (processed)
        if ($paymentStatus === Order::PAYMENT_PAID) {
            $updateData['status'] = Order::STATUS_DIPROSES;
        }

        // If explicit order status is provided
        if ($orderStatus !== null) {
            $updateData['status'] = $orderStatus;
        }

        $order->update($updateData);
    }

    /**
     * Check and process pending transactions expiry
     */
    public function processExpiredTransactions(): void
    {
        $expiredTransactions = PaymentTransaction::where('transaction_status', PaymentTransaction::STATUS_PENDING)
            ->where('expiry_time', '<=', now())
            ->get();

        foreach ($expiredTransactions as $transaction) {
            try {
                $transaction->update([
                    'transaction_status' => PaymentTransaction::STATUS_EXPIRE,
                ]);

                $order = $transaction->order;
                if ($order && $order->payment_status === Order::PAYMENT_UNPAID) {
                    $order->update(['status' => Order::STATUS_DIBATALKAN]);
                }

                Log::info('Payment transaction expired', [
                    'transaction_id' => $transaction->id,
                    'order_id' => $transaction->order_id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to process expired transaction', [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
