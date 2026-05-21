<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'kode_order',
        'nama_pelanggan',
        'nomor_hp',
        'alamat',
        'catatan',
        'metode_pengiriman',
        'metode_pembayaran',
        'subtotal',
        'total_bayar',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_DIPROSES = 'diproses';
    const STATUS_DIMASAK = 'dimasak';
    const STATUS_SIAP_DIAMBIL = 'siap_diambil';
    const STATUS_DIANTAR = 'diantar';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DIBATALKAN = 'dibatalkan';

    /**
     * Payment status constants
     */
    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PAID = 'paid';

    /**
     * Delivery method constants
     */
    const DELIVERY_PICKUP = 'pickup';
    const DELIVERY_DELIVERY = 'delivery';

    /**
     * Payment method constants
     */
    const PAYMENT_CASH = 'cash';
    const PAYMENT_QRIS = 'qris';

    /**
     * Get status labels for display
     */
    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_DIMASAK => 'Dimasak',
            self::STATUS_SIAP_DIAMBIL => 'Siap Diambil',
            self::STATUS_DIANTAR => 'Dalam Pengantaran',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
        ];
    }

    /**
     * Get payment status labels for display
     */
    public static function getPaymentStatusLabels(): array
    {
        return [
            self::PAYMENT_UNPAID => 'Belum Dibayar',
            self::PAYMENT_PAID => 'Sudah Dibayar',
        ];
    }

    /**
     * Get delivery method labels
     */
    public static function getDeliveryMethodLabels(): array
    {
        return [
            self::DELIVERY_PICKUP => 'Pickup',
            self::DELIVERY_DELIVERY => 'Delivery',
        ];
    }

    /**
     * Get payment method labels
     */
    public static function getPaymentMethodLabels(): array
    {
        return [
            self::PAYMENT_CASH => 'Cash',
            self::PAYMENT_QRIS => 'QRIS',
        ];
    }

    /**
     * Generate unique order code
     */
    public static function generateOrderCode(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return "ORD-{$date}-{$random}";
    }

    /**
     * Relationship to order items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relationship to payment transactions
     */
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /**
     * Relationship to order status histories
     */
    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the latest payment transaction
     */
    public function latestPayment()
    {
        return $this->hasOne(PaymentTransaction::class)->latestOfMany();
    }

    /**
     * Check if order is delivery
     */
    public function isDelivery(): bool
    {
        return $this->metode_pengiriman === self::DELIVERY_DELIVERY;
    }

    /**
     * Check if order is pickup
     */
    public function isPickup(): bool
    {
        return $this->metode_pengiriman === self::DELIVERY_PICKUP;
    }

    /**
     * Check if payment is cash
     */
    public function isCashPayment(): bool
    {
        return $this->metode_pembayaran === self::PAYMENT_CASH;
    }

    /**
     * Check if payment is QRIS
     */
    public function isQRISPayment(): bool
    {
        return $this->metode_pembayaran === self::PAYMENT_QRIS;
    }

    /**
     * Check if order is active (not completed or cancelled)
     */
    public function isActive(): bool
    {
        return !in_array($this->status, [self::STATUS_SELESAI, self::STATUS_DIBATALKAN]);
    }

    /**
     * Get the next possible statuses based on current status and delivery method
     */
    public function getNextStatuses(): array
    {
        $flow = $this->getStatusFlow();

        $currentIndex = array_search($this->status, array_keys($flow));
        if ($currentIndex === false) {
            return [];
        }

        $nextStatuses = [];
        for ($i = $currentIndex + 1; $i < count($flow); $i++) {
            $status = array_keys($flow)[$i];
            $nextStatuses[$status] = $flow[$status];
        }

        return $nextStatuses;
    }

    /**
     * Get the immediate next status
     */
    public function getNextStatus(): ?string
    {
        $nextStatuses = $this->getNextStatuses();
        return !empty($nextStatuses) ? array_key_first($nextStatuses) : null;
    }

    /**
     * Get status flow based on delivery method
     */
    public function getStatusFlow(): array
    {
        $baseFlow = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_DIMASAK => 'Dimasak',
        ];

        if ($this->isPickup()) {
            $baseFlow[self::STATUS_SIAP_DIAMBIL] = 'Siap Diambil';
        } elseif ($this->isDelivery()) {
            $baseFlow[self::STATUS_DIANTAR] = 'Dalam Pengantaran';
        }

        $baseFlow[self::STATUS_SELESAI] = 'Selesai';

        return $baseFlow;
    }

    /**
     * Check if status can be changed to the given status
     */
    public function canChangeToStatus(string $newStatus): bool
    {
        // Cannot change completed or cancelled orders
        if (!$this->isActive()) {
            return false;
        }

        // Cannot go back to previous statuses
        $flow = $this->getStatusFlow();
        $currentIndex = array_search($this->status, array_keys($flow));
        $newIndex = array_search($newStatus, array_keys($flow));

        if ($newIndex === false || $newIndex <= $currentIndex) {
            return false;
        }

        return true;
    }

    /**
     * Get status color for UI
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'amber',
            self::STATUS_DIPROSES => 'blue',
            self::STATUS_DIMASAK => 'orange',
            self::STATUS_SIAP_DIAMBIL => 'green',
            self::STATUS_DIANTAR => 'indigo',
            self::STATUS_SELESAI => 'emerald',
            self::STATUS_DIBATALKAN => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status icon for UI
     */
    public function getStatusIcon(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'fas fa-clock',
            self::STATUS_DIPROSES => 'fas fa-cog',
            self::STATUS_DIMASAK => 'fas fa-utensils',
            self::STATUS_SIAP_DIAMBIL => 'fas fa-check-circle',
            self::STATUS_DIANTAR => 'fas fa-truck',
            self::STATUS_SELESAI => 'fas fa-check-double',
            self::STATUS_DIBATALKAN => 'fas fa-times-circle',
            default => 'fas fa-question-circle',
        };
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by payment status
     */
    public function scopePaymentStatus($query, string $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    /**
     * Scope to filter active orders
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_SELESAI, self::STATUS_DIBATALKAN]);
    }

    /**
     * Scope to filter delivery orders
     */
    public function scopeDelivery($query)
    {
        return $query->where('metode_pengiriman', self::DELIVERY_DELIVERY);
    }

    /**
     * Scope to filter pickup orders
     */
    public function scopePickup($query)
    {
        return $query->where('metode_pengiriman', self::DELIVERY_PICKUP);
    }

    /**
     * Scope to order by latest
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
