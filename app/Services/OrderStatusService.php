<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderStatusService
{
    protected WhatsAppNotificationService $whatsappService;

    public function __construct(WhatsAppNotificationService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Update order status with validation and logging
     */
    public function updateStatus(Order $order, string $newStatus, ?int $changedBy = null): bool
    {
        // Validate status change
        if (!$order->canChangeToStatus($newStatus)) {
            throw new \InvalidArgumentException('Status change not allowed');
        }

        $oldStatus = $order->status;

        DB::transaction(function () use ($order, $newStatus, $oldStatus, $changedBy) {
            // Update order status
            $order->status = $newStatus;
            $order->save();

            // Auto set payment status to paid when order is completed
            if ($newStatus === Order::STATUS_SELESAI && $order->payment_status === Order::PAYMENT_UNPAID) {
                $order->payment_status = Order::PAYMENT_PAID;
                $order->save();
            }

            // Log status change
            $this->logStatusChange($order, $newStatus, $oldStatus, $changedBy);

            // Send WhatsApp notification
            $this->whatsappService->sendStatusUpdate($order, $newStatus);
        });

        return true;
    }

    /**
     * Log status change to history
     */
    protected function logStatusChange(Order $order, string $newStatus, string $oldStatus, ?int $changedBy = null): void
    {
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'previous_status' => $oldStatus,
            'changed_by' => $changedBy ?? Auth::id(),
            'metadata' => [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
        ]);
    }

    /**
     * Get status update messages for WhatsApp
     */
    public function getStatusMessages(): array
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
}
