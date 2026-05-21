<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'order_id_midtrans',
        'snap_token',
        'gross_amount',
        'payment_type',
        'transaction_status',
        'fraud_status',
        'qr_string',
        'qr_image_url',
        'actions',
        'va_number',
        'bank',
        'expiry_time',
        'settlement_time',
        'raw_response',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'expiry_time' => 'datetime',
        'settlement_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Status constants matching Midtrans
     */
    const STATUS_PENDING = 'pending';
    const STATUS_SETTLEMENT = 'settlement';
    const STATUS_CAPTURE = 'capture';
    const STATUS_EXPIRE = 'expire';
    const STATUS_CANCEL = 'cancel';
    const STATUS_DENY = 'deny';
    const STATUS_CHALLENGE = 'challenge';

    /**
     * Relationship to order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if transaction is successful (paid)
     */
    public function isSuccess(): bool
    {
        return in_array($this->transaction_status, [
            self::STATUS_SETTLEMENT,
            self::STATUS_CAPTURE,
        ]);
    }

    /**
     * Check if transaction is expired
     */
    public function isExpired(): bool
    {
        return $this->transaction_status === self::STATUS_EXPIRE;
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return $this->transaction_status === self::STATUS_PENDING;
    }
}
