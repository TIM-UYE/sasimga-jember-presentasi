<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $table = 'predictions';

    protected $fillable = [
        'menu_id',
        'menu_name',
        'month',
        'year',
        'predicted_sales',
        'confidence',
        'ai_status',
    ];

    protected $casts = [
        'predicted_sales' => 'integer',
        'confidence' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
