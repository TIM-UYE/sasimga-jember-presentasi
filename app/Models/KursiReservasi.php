<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KursiReservasi extends Model
{
    protected $table = 'kursi_reservasi';

    protected $fillable = [
        'meja_id',
        'tanggal',
        'waktu_sesi',
        'tersedia',
        'reservasi_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_sesi' => 'string',
        'tersedia' => 'boolean',
    ];

    /**
     * Get the table that owns this seat reservation.
     */
    public function meja(): BelongsTo
    {
        return $this->belongsTo(Meja::class);
    }

    /**
     * Get the reservation that owns this seat.
     */
    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class);
    }

    /**
     * Check if this seat is available for booking.
     */
    public function isTersedia(): bool
    {
        return $this->tersedia && $this->meja && $this->meja->is_active;
    }

    /**
     * Release expired reservations that are older than the configured window.
     */
    public static function releaseExpiredTables(int $hours = 2): int
    {
        $threshold = Carbon::now()->subHours($hours);

        $expiredIds = self::where('tersedia', false)
            ->get()
            ->filter(function ($item) use ($threshold) {
                $reservationDateTime = Carbon::parse($item->tanggal->format('Y-m-d') . ' ' . $item->waktu_sesi);
                return $reservationDateTime->lte($threshold);
            })
            ->pluck('id')
            ->all();

        if (empty($expiredIds)) {
            return 0;
        }

        return self::whereIn('id', $expiredIds)
            ->update([
                'tersedia' => true,
                'reservasi_id' => null,
            ]);
    }
}
