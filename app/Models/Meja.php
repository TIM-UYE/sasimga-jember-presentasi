<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meja extends Model
{
    protected $table = 'meja';

    protected $fillable = [
        'nama_meja',
        'nomor_meja',
        'kategori',
        'kapasitas',
        'lantai_id',
        'posisi_row',
        'posisi_col',
        'pos_x',
        'pos_y',
        'merge_group',
        'is_mergeable',
        'preview_image',
        'table_image',
        'is_active',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'posisi_col' => 'integer',
        'pos_x' => 'integer',
        'pos_y' => 'integer',
        'is_active' => 'boolean',
        'is_mergeable' => 'boolean',
    ];

    /**
     * Get all seat reservations for this table.
     */
    public function kursiReservasis(): HasMany
    {
        return $this->hasMany(KursiReservasi::class);
    }

    /**
     * Get the floor (lantai) this table belongs to.
     */
    public function lantai(): BelongsTo
    {
        return $this->belongsTo(Lantai::class);
    }

    /**
     * Get label for category.
     */
    public function getKategoriLabelAttribute(): string
    {
        return [
            'regular' => 'Regular',
            'vip' => 'VIP',
            'booth' => 'Booth',
        ][$this->kategori] ?? $this->kategori;
    }

    /**
     * Get table image URL.
     */
    public function getTableImageUrlAttribute(): string
    {
        if ($this->table_image) {
            return asset('storage/' . $this->table_image);
        }
        return asset('images/default-table.png');
    }

    /**
     * Get preview image URL.
     */
    public function getPreviewImageUrlAttribute(): string
    {
        if ($this->preview_image) {
            return asset('storage/' . $this->preview_image);
        }
        return $this->table_image_url;
    }

    /**
     * Get all tables in the same merge group.
     */
    public function getMergeGroupTables()
    {
        if (!$this->merge_group) {
            return collect([$this]);
        }
        return self::where('merge_group', $this->merge_group)
            ->where('lantai_id', $this->lantai_id)
            ->orderBy('nomor_meja')
            ->get();
    }

    /**
     * Check if this table can be merged with another table.
     */
    public function canMergeWith(Meja $otherTable): bool
    {
        if (!$this->is_mergeable || !$otherTable->is_mergeable) {
            return false;
        }

        if ($this->lantai_id !== $otherTable->lantai_id) {
            return false;
        }

        if (!$this->merge_group || !$otherTable->merge_group) {
            return false;
        }

        return $this->merge_group === $otherTable->merge_group;
    }

    /**
     * Get available tables grouped by merge groups for a given date/time.
     */
    public static function getAvailableByFloor(int $lantaiId, string $tanggal, string $waktu): \Illuminate\Support\Collection
    {
        KursiReservasi::releaseExpiredTables();

        try {
            $hour = \Carbon\Carbon::parse($waktu)->format('H');
        } catch (\Exception $e) {
            $hour = substr($waktu, 0, 2);
        }

        $bookedTableIds = KursiReservasi::where('tanggal', $tanggal)
            ->whereRaw('HOUR(waktu_sesi) = ?', [$hour])
            ->where('tersedia', false)
            ->pluck('meja_id')
            ->toArray();

        $tables = self::where('lantai_id', $lantaiId)
            ->where('is_active', true)
            ->orderBy('nomor_meja')
            ->get()
            ->map(function ($table) use ($bookedTableIds) {
                $table->is_available = !in_array($table->id, $bookedTableIds);
                return $table;
            });

        // Group by merge_group
        $grouped = $tables->groupBy(function ($table) {
            return $table->merge_group ?? 'standalone_' . $table->id;
        });

        return $grouped;
    }
}
