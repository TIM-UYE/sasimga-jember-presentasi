<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'previous_status',
        'changed_by',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship to order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship to user who changed the status
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return Order::getStatusLabels()[$this->status] ?? $this->status;
    }

    /**
     * Get previous status label
     */
    public function getPreviousStatusLabelAttribute(): string
    {
        if (!$this->previous_status) {
            return '-';
        }
        return Order::getStatusLabels()[$this->previous_status] ?? $this->previous_status;
    }
}
