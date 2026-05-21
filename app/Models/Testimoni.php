<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'testimonis';

    protected $fillable = [
        'review_key',
        'author_name',
        'author_url',
        'profile_photo_url',
        'rating',
        'text',
        'relative_time_description',
        'language',
        'review_date',
        'source',
        'place_id',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
        'review_date' => 'datetime',
    ];
}
