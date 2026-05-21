<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSpecial extends Model
{
    use HasFactory;

    protected $table = 'menu_specials';
    public $timestamps = true;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'banner_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(MenuSpecialItem::class, 'menu_special_id', 'id');
    }
}
