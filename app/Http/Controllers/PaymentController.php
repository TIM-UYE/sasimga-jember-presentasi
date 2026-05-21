<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\MidtransSnapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $snapService;

    public function __construct(MidtransSnapService $snapService)
    {
        $this->snapService = $snapService;
    }

    /**
     * Show Snap payment page with snap token
     */
    public function showSnap($kodeOrder)
    {
        $order = Order::where('kode_order', $kodeOrder)->firstOrFail();

        if (!$order->isQRISPayment()) {
            return redirect()->route('checkout.success', $kodeOrder);
        }

        if ($order->payment_status === Order::PAYMENT_PAID) {
            return redirect()->route('payment.success', $kodeOrder);
        }

        $snapToken = null;
        $error = null;

        try {
            $result = $this->snapService->createSnapTransaction($order);
            $snapToken = $result['snap_token'];
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::error('Snap payment error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }

        return view('frontend.checkout.snap', compact('order', 'snapToken', 'error'));
    }

    /**
     * Check payment status via AJAX
     */
    public function checkStatus($kodeOrder)
    {
        $order = Order::where('kode_order', $kodeOrder)->firstOrFail();

        $paid = $order->payment_status === Order::PAYMENT_PAID;

        return response()->json([
            'success' => true,
            'paid' => $paid,
            'status' => $order->payment_status,
            'order_status' => $order->status,
            'kode_order' => $order->kode_order,
            'redirect_url' => $paid ? route('payment.success', $kodeOrder) : null,
        ]);
    }

    /**
     * Show payment success page
     */
    public function success($kodeOrder)
    {
        $order = Order::where('kode_order', $kodeOrder)->firstOrFail();
        $paymentTransaction = $order->latestPayment;

        return view('frontend.checkout.payment-success', compact('order', 'paymentTransaction'));
    }
}
