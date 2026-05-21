<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriMenu extends Model
{
    use HasFactory;

    protected $table = 'kategori_menu';
    public $timestamps = true;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'ikon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'kategori_id', 'id');
    }
}
