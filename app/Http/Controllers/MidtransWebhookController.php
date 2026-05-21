<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\MidtransSnapService;
use App\Services\StokService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    protected $snapService;
    protected $stokService;

    public function __construct(MidtransSnapService $snapService, StokService $stokService)
    {
        $this->snapService = $snapService;
        $this->stokService = $stokService;
    }

    /**
     * Handle Snap webhook notification
     *
     * IMPORTANT: Always return 200 OK to acknowledge receipt to Midtrans.
     * Errors should be in JSON body, not HTTP status code.
     */
    public function handle(Request $request)
    {
        Log::info('===== MIDTRANS WEBHOOK RECEIVED =====');
        Log::info('Request Method: ' . $request->method());
        Log::info('Request IP: ' . $request->ip());
        Log::info('Content-Type: ' . $request->header('Content-Type'));
        Log::info('Request Headers: ' . json_encode($request->headers->all()));
        Log::info('Request Payload: ' . json_encode($request->all()));
        Log::info('======================================');

        try {
            $notification = $request->all();

            if (empty($notification)) {
                Log::warning('Empty webhook payload received');

                return response()->json([
                    'success' => false,
                    'message' => 'Empty payload',
                ], 200);
            }

            Log::info('Webhook Processing:', [
                'order_id' => $notification['order_id'] ?? 'N/A',
                'transaction_id' => $notification['transaction_id'] ?? 'N/A',
                'transaction_status' => $notification['transaction_status'] ?? 'N/A',
                'gross_amount' => $notification['gross_amount'] ?? 'N/A',
            ]);

            $result = $this->snapService->processWebhookNotification($notification);

            if (
                isset($result['order']) &&
                $result['order'] &&
                $result['order']->payment_status === Order::PAYMENT_PAID
            ) {
                $this->stokService->kurangiStokUntukOrder($result['order']->fresh());

                Log::info('[STOK MIDTRANS] Stok diproses setelah webhook paid', [
                    'order_id' => $result['order']->id,
                    'kode_order' => $result['order']->kode_order,
                    'payment_status' => $result['order']->payment_status,
                ]);
            }

            Log::info('✓ Webhook processed successfully', [
                'order_id' => $result['order']->id ?? null,
                'kode_order' => $result['order']->kode_order ?? null,
                'transaction_status' => $result['transaction_status'] ?? null,
                'result_status' => $result['result_status'] ?? null,
                'payment_status' => $result['order']->payment_status ?? null,
                'order_status' => $result['order']->status ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook received and processed successfully',
                'data' => [
                    'order_id' => $result['order']->kode_order ?? null,
                    'payment_status' => $result['order']->payment_status ?? null,
                    'transaction_status' => $result['transaction_status'] ?? null,
                    'processed_at' => now(),
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('✗ Webhook processing failed', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Webhook received but processing failed',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Finish callback - user completed payment
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;

        Log::info('Snap Finish Redirect', ['order_id' => $orderId]);

        $paymentTransaction = PaymentTransaction::where('order_id_midtrans', $orderId)->first();

        if ($paymentTransaction) {
            try {
                $status = \Midtrans\Transaction::status($orderId);

                if ($status) {
                    $notification = json_decode(json_encode($status), true);
                    $result = $this->snapService->processWebhookNotification($notification);

                    if (
                        isset($result['order']) &&
                        $result['order'] &&
                        $result['order']->payment_status === Order::PAYMENT_PAID
                    ) {
                        $this->stokService->kurangiStokUntukOrder($result['order']->fresh());

                        Log::info('[STOK MIDTRANS FINISH] Stok diproses setelah finish callback paid', [
                            'order_id' => $result['order']->id,
                            'kode_order' => $result['order']->kode_order,
                            'payment_status' => $result['order']->payment_status,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Finish status check failed', [
                    'error' => $e->getMessage(),
                ]);
            }

            $paymentTransaction->refresh();

            $order = $paymentTransaction->order;

            if ($order) {
                return redirect()->route('payment.success', $order->kode_order);
            }
        }

        return redirect()->route('frontend.home');
    }

    /**
     * Unfinish callback - user cancelled/back
     */
    public function unfinish(Request $request)
    {
        $orderId = $request->order_id;

        Log::info('Snap Unfinish', ['order_id' => $orderId]);

        $paymentTransaction = PaymentTransaction::where('order_id_midtrans', $orderId)->first();

        if ($paymentTransaction) {
            $order = $paymentTransaction->order;

            if ($order) {
                return redirect()->route('payment.snap', $order->kode_order)
                    ->with('error', 'Pembayaran dibatalkan. Silakan coba lagi.');
            }
        }

        return redirect()->route('frontend.home');
    }

    /**
     * Error callback
     */
    public function error(Request $request)
    {
        $orderId = $request->order_id;

        Log::error('Snap Error', [
            'order_id' => $orderId,
            'body' => $request->all(),
        ]);

        $paymentTransaction = PaymentTransaction::where('order_id_midtrans', $orderId)->first();

        if ($paymentTransaction) {
            $order = $paymentTransaction->order;

            if ($order) {
                return redirect()->route('payment.snap', $order->kode_order)
                    ->with('error', 'Terjadi kesalahan pembayaran. Silakan coba lagi.');
            }
        }

        return redirect()->route('frontend.home')
            ->with('error', 'Terjadi kesalahan pembayaran.');
    }
}