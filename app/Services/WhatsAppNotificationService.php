<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send order created notification to customer
     */
    public function sendOrderCreated(Order $order): bool
    {
        $messages = $this->getOrderCreatedMessages();

        $message = $this->formatMessage($messages[$order->metode_pembayaran], $order);

        // Replace payment link placeholder for QRIS payments
        if ($order->isQRISPayment()) {
            $paymentLink = route('payment.snap', $order->kode_order);
            $message = str_replace('{payment_link}', $paymentLink, $message);
        }

        return $this->whatsappService->sendToCustomer($order->nomor_hp, $message);
    }

    /**
     * Send payment success notification to customer
     */
    public function sendPaymentSuccess(Order $order): bool
    {
        \Illuminate\Support\Facades\Log::info('WhatsAppNotificationService: sendPaymentSuccess called', [
            'order_id' => $order->id,
            'kode_order' => $order->kode_order,
            'nomor_hp' => $order->nomor_hp,
        ]);

        $message = $this->getPaymentSuccessMessage($order);

        \Illuminate\Support\Facades\Log::info('WhatsAppNotificationService: sending message', [
            'order_id' => $order->id,
            'message' => $message,
        ]);

        return $this->whatsappService->sendToCustomer($order->nomor_hp, $message);
    }

    /**
     * Get status update messages
     */
    protected function getStatusMessages(): array
    {
        return [
            Order::STATUS_PENDING => "Halo {nama_pelanggan}, pesanan Anda sedang menunggu konfirmasi restoran.",
            Order::STATUS_DIPROSES => "Halo {nama_pelanggan}, pesanan Anda sedang diproses oleh restoran.",
            Order::STATUS_DIMASAK => "Pesanan Anda sedang dimasak 👨‍🍳",
            Order::STATUS_SIAP_DIAMBIL => "Pesanan Anda sudah siap diambil di restoran.",
            Order::STATUS_DIANTAR => "Pesanan Anda sedang diantar driver.",
            Order::STATUS_SELESAI => "Pesanan selesai. Terima kasih telah memesan ❤️",
        ];
    }

    /**
     * Get order created messages based on payment method
     */
    protected function getOrderCreatedMessages(): array
    {
        return [
            Order::PAYMENT_QRIS => "Halo {nama_pelanggan}, pesanan Anda dengan ID {order_id} telah diterima! 🎉\n\nSilakan lakukan pembayaran melalui QRIS agar pesanan segera diproses.\n\n💳 Link pembayaran: {payment_link}\n\nMohon segera bayar agar tidak expired.",
            Order::PAYMENT_CASH => "Halo {nama_pelanggan}, pesanan Anda dengan ID {order_id} telah diterima! 🎉\n\nPesanan Anda sedang diproses. Mohon tunggu sebentar ya.",
        ];
    }

    /**
     * Get payment success message
     */
    protected function getPaymentSuccessMessage(Order $order): string
    {
        $message = "Halo {nama_pelanggan}, pembayaran untuk pesanan {order_id} telah berhasil! ✅\n\n";
        $message .= "Pesanan Anda sedang diproses oleh restoran. Mohon ditunggu ya.";

        return $this->formatMessage($message, $order);
    }

    /**
     * Format message with order data
     */
    protected function formatMessage(string $template, Order $order): string
    {
        return str_replace(
            ['{nama_pelanggan}', '{order_id}'],
            [$order->nama_pelanggan, $order->kode_order],
            $template
        );
    }
}
