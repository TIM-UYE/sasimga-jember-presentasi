<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';

    protected $fillable = [
        'nama_bahan',
        'satuan',
        'jumlah_stok',
        'stok_minimum',
    ];

    public function logs()
    {
        return $this->hasMany(StokLog::class);
    }

    public function menuBahans()
    {
        return $this->hasMany(MenuBahan::class);
    }

    public function getStatusAttribute()
    {
        if ($this->jumlah_stok <= 0) {
            return 'Habis';
        }

        if ($this->jumlah_stok <= $this->stok_minimum) {
            return 'Menipis';
        }

        return 'Aman';
    }
}