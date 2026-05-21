<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'menu_id',
        'item_type',
        'item_id',
        'nama_menu',
        'qty',
        'harga',
        'subtotal',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function itemable()
    {
        return $this->morphTo(__FUNCTION__, 'item_type', 'item_id');
    }

    public function calculateSubtotal(): void
    {
        $this->subtotal = $this->harga * $this->qty;
    }

    public function getFormattedHarga(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getFormattedSubtotal(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}