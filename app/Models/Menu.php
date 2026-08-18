<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';
    public $timestamps = true;

    protected $fillable = [
        'nama_menu',
        'deskripsi',
        'harga',
        'kategori_id',
        'gambar',
        'is_available',
        'stok',
        'ukuran',
        'bahan',
        'durasi_persiapan',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_available' => 'boolean',
        'durasi_persiapan' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriMenu::class, 'kategori_id', 'id');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'menu_id', 'id');
    }
}

