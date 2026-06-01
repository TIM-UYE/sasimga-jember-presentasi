<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lantai extends Model
{
    protected $table = 'lantai';

    protected $fillable = [
        'nama',
        'slug',
        'preview_image',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'is_active' => 'boolean',
    ];

    public function mejas(): HasMany
    {
        return $this->hasMany(Meja::class);
    }

    public function getPreviewImageUrlAttribute(): string
    {
        if ($this->preview_image) {
            return asset('storage/' . $this->preview_image);
        }
        return asset('images/default-layout.png');
    }
}
