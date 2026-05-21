<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuBahan extends Model
{
    protected $table = 'menu_bahans';

    protected $fillable = [
        'menuable_id',
        'menuable_type',
        'stok_id',
        'jumlah_dibutuhkan',
        'satuan',
    ];

    public function menuable()
    {
        return $this->morphTo();
    }

    public function stok()
    {
        return $this->belongsTo(Stok::class);
    }
}
