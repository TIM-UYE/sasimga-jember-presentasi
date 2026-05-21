<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meja extends Model
{
    protected $table = 'meja';

    protected $fillable = [
        'nama_meja',
        'kategori',
        'kapasitas',
        'posisi_row',
        'posisi_col',
        'is_active',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'posisi_col' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get all seat reservations for this table.
     */
    public function kursiReservasis(): HasMany
    {
        return $this->hasMany(KursiReservasi::class);
    }

    /**
     * Get label for category.
     */
    public function getKategoriLabelAttribute(): string
    {
        return [
            'regular' => 'Regular',
            'vip' => 'VIP',
            'booth' => 'Booth',
        ][$this->kategori] ?? $this->kategori;
    }
}
