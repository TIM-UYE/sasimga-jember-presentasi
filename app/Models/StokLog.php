<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokLog extends Model
{
    protected $table = 'stok_log';

    protected $fillable = [
        'stok_id',
        'tipe',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan',
        'referensi_id',
        'referensi_type',
    ];

    public function stok()
    {
        return $this->belongsTo(Stok::class);
    }

    public function referensi()
    {
        return $this->morphTo();
    }
}