<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransSnapService
{
    protected WhatsAppNotificationService $whatsappNotificationService;

    /**
     * Initialize Midtrans configuration
     */
    public function __construct(WhatsAppNotificationService $whatsappNotificationService)
    {
        $this->whatsappNotificationService = $whatsappNotificationService;

        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.environment') === 'production';
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Create Snap transaction and get snap token
     *
     * @param Order $order
     * @return array
     * @throws \Exception
     */
    public function createSnapTransaction(Order $order): array
    {
        if ($order->payment_status !== Order::PAYMENT_UNPAID) {
            throw new \Exception('Pesanan ini sudah dibayar.');
        }

        if (!$order->isQRISPayment()) {
            throw new \Exception('Metode pembayaran bukan QRIS.');
        }

        // Cek existing pending transaction
        $existingTransaction = PaymentTransaction::where('order_id', $order->id)
            ->whereNull('snap_token')
            ->whereIn('transaction_status', [
                PaymentTransaction::STATUS_PENDING,
            ])
            ->latest()
            ->first();

        if ($existingTransaction && $existingTransaction->snap_token) {
            return [
                'transaction' => $existingTransaction,
                'snap_token' => $existingTransaction->snap_token,
                'is_new' => false,
            ];
        }

        $orderIdMidtrans = 'ORDER-' . $order->kode_order . '-' . time();
        $expiryDuration = config('midtrans.expiry_duration', 60);
        $expiryUnit = config('midtrans.expiry_unit', 'minutes');

        $transactionDetails = [
            'order_id' => $orderIdMidtrans,
            'gross_amount' => (int) round($order->total_bayar),
        ];

        $customerDetails = [
            'first_name' => $order->nama_pelanggan,
            'phone' => $order->nomor_hp,
        ];

        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => (string) $item->menu_id,
                'price' => (int) round($item->harga),
                'quantity' => $item->qty,
                'name' => substr($item->nama_menu, 0, 50),
            ];
        }

        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'expiry' => [
                'duration' => (int) $expiryDuration,
                'unit' => $expiryUnit,
            ],
            'callbacks' => [
                'finish' => route('midtrans.finish'),
                'unfinish' => route('midtrans.unfinish'),
                'error' => route('midtrans.error'),
            ],
        ];

        Log::info('Snap Transaction Request', [
            'order_id' => $order->id,
            'kode_order' => $order->kode_order,
            'amount' => $transactionDetails['gross_amount'],
        ]);

        try {
            $snapToken = Snap::getSnapToken($params);

            Log::info('Snap Token Generated', [
                'order_id' => $order->id,
                'snap_token' => substr($snapToken, 0, 20) . '...',
            ]);

            // Save to database
            $paymentTransaction = DB::transaction(function () use (
                $order, $orderIdMidtrans, $transactionDetails,
                $snapToken, $expiryDuration
            ) {
                // Expire old pending transactions
                PaymentTransaction::where('order_id', $order->id)
                    ->where('transaction_status', PaymentTransaction::STATUS_PENDING)
                    ->update(['transaction_status' => PaymentTransaction::STATUS_EXPIRE]);

                return PaymentTransaction::create([
                    'order_id' => $order->id,
                    'order_id_midtrans' => $orderIdMidtrans,
                    'gross_amount' => $transactionDetails['gross_amount'],
                    'transaction_status' => PaymentTransaction::STATUS_PENDING,
                    'snap_token' => $snapToken,
                    'expiry_time' => now()->addMinutes((int) $expiryDuration),
                    'raw_response' => json_encode(['snap_token' => $snapToken]),
                ]);
            });

            return [
                'transaction' => $paymentTransaction,
                'snap_token' => $snapToken,
                'is_new' => true,
            ];

        } catch (\Exception $e) {
            Log::error('Snap Transaction Failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Verify Snap webhook notification with signature
     *
     * @param array $notification
     * @return bool
     */
    public function verifySignature(array $notification): bool
    {
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $serverKey = config('midtrans.server_key');

        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        $providedSignature = $notification['signature_key'] ?? '';

        return $signature === $providedSignature;
    }

    /**
     * Process Snap webhook notification
     *
     * @param array $notification
     * @return array
     * @throws \Exception
     */
    public function processWebhookNotification(array $notification): array
    {
        Log::info('Snap Webhook Received', ['notification' => $notification]);

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
        $signatureKey = $notification['signature_key'] ?? null;

        // Signature verification
        if ($signatureKey) {
            if (!$this->verifySignature($notification)) {
                Log::warning('Invalid signature', [
                    'order_id' => $orderIdMidtrans,
                    'provided' => $signatureKey,
                ]);
                throw new \Exception('Invalid signature key.');
            }
        }

        // Find payment transaction
        $paymentTransaction = PaymentTransaction::where('order_id_midtrans', $orderIdMidtrans)
            ->orderBy('id', 'desc')
            ->first();

        if (!$paymentTransaction) {
            throw new \Exception("Payment transaction not found for: {$orderIdMidtrans}");
        }

        $order = $paymentTransaction->order;
        if (!$order) {
            throw new \Exception("Order not found.");
        }

        // Prevent duplicate processing
        if ($paymentTransaction->transaction_status === $transactionStatus) {
            return [
                'order' => $order,
                'payment_transaction' => $paymentTransaction,
                'transaction_status' => $transactionStatus,
                'result_status' => $this->mapStatus($transactionStatus, $fraudStatus),
                'already_processed' => true,
            ];
        }

        $paymentTransaction->update([
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'transaction_id' => $transactionId ?? $paymentTransaction->transaction_id,
            'payment_type' => $paymentType,
            'settlement_time' => $settlementTime ? \Carbon\Carbon::parse($settlementTime) : null,
            'raw_response' => json_encode($notification),
        ]);

        $resultStatus = $this->mapStatus($transactionStatus, $fraudStatus);

        switch ($resultStatus) {
            case 'paid':
                $order->update([
                    'payment_status' => Order::PAYMENT_PAID,
                    'status' => Order::STATUS_DIPROSES,
                ]);

                // Send WhatsApp notification for successful payment
                try {
                    Log::info('Sending payment success WA notification', [
                        'order_id' => $order->id,
                        'kode_order' => $order->kode_order,
                        'nomor_hp' => $order->nomor_hp,
                    ]);
                    $this->whatsappNotificationService->sendPaymentSuccess($order);
                    Log::info('Payment success WA notification sent successfully', [
                        'order_id' => $order->id,
                        'kode_order' => $order->kode_order,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send payment success WA notification', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
                break;

            case 'failed':
            case 'expired':
                $order->update([
                    'payment_status' => Order::PAYMENT_UNPAID,
                    'status' => Order::STATUS_PENDING,
                ]);
                break;
        }

        return [
            'order' => $order,
            'payment_transaction' => $paymentTransaction,
            'transaction_status' => $transactionStatus,
            'result_status' => $resultStatus,
            'already_processed' => false,
        ];
    }

    /**
     * Map Midtrans status to our status
     */
    public function mapStatus(string $transactionStatus, ?string $fraudStatus): string
    {
        switch ($transactionStatus) {
            case 'settlement':
            case 'capture':
                if ($fraudStatus === 'deny' || $fraudStatus === 'challenge') {
                    return 'pending';
                }
                return 'paid';

            case 'pending':
                return 'pending';

            case 'deny':
            case 'cancel':
            case 'failure':
                return 'failed';

            case 'expire':
                return 'expired';

            default:
                return 'pending';
        }
    }
}
