<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuSpecialItem extends Model
{
    use HasFactory;

    protected $table = 'menu_special_items';

    protected $fillable = [
        'menu_special_id',
        'name',
        'price',
        'description',
        'image',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function menuSpecial()
    {
        return $this->belongsTo(MenuSpecial::class, 'menu_special_id');
    }

    public function special()
    {
        return $this->belongsTo(MenuSpecial::class, 'menu_special_id');
    }

    public function komposisiBahan()
    {
        return $this->morphMany(MenuBahan::class, 'menuable');
    }
}