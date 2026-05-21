<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MenuBahan;
use App\Services\StockCalculationService;

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

    protected $appends = ['calculated_stock'];

    public function kategori()
    {
        return $this->belongsTo(KategoriMenu::class, 'kategori_id', 'id');
    }

    public function komposisiBahan()
    {
        return $this->morphMany(MenuBahan::class, 'menuable');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'menu_id', 'id');
    }

    public function stokModel()
    {
        return $this->hasOne(Stok::class, 'menu_id', 'id');
    }

    /**
     * Get the auto-calculated stock based on ingredient availability.
     */
    public function getCalculatedStockAttribute(): int
    {
        try {
            $service = app(StockCalculationService::class);
            $result = $service->calculateMenuStock($this);
            return $result['stock'];
        } catch (\Exception $e) {
            return (int) $this->stok;
        }
    }

    /**
     * Get detailed stock calculation breakdown.
     */
    public function getStockCalculationDetails(): array
    {
        try {
            $service = app(StockCalculationService::class);
            return $service->calculateMenuStock($this);
        } catch (\Exception $e) {
            return [
                'stock' => (int) $this->stok,
                'details' => collect([]),
            ];
        }
    }
}
