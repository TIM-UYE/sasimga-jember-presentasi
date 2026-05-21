<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use App\Models\Meja;
use App\Models\KursiReservasi;

class Reservasi extends Model
{
    protected $table = 'reservasis';
    use Notifiable;

    protected $fillable = [
        'nama',
        'nomor_wa',
        'tanggal_reservasi',
        'waktu_reservasi',
        'jumlah_orang',
        'status',
        'meja_ids', // Store selected table IDs as JSON
    ];

    protected $casts = [
        'tanggal_reservasi' => 'date',
        'waktu_reservasi' => 'string',
        'jumlah_orang' => 'integer',
        'meja_ids' => 'array',
    ];

    /**
     * Get label for status.
     */
    public function getStatusLabelAttribute(): string
    {
        return [
            'pending' => 'Pending',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
        ][$this->status] ?? $this->status;
    }

    /**
     * Get formatted nomor_wa (prepend 62 if starts with 0).
     */
    public function getFormattedWaAttribute(): string
    {
        $wa = $this->nomor_wa;

        // Hapus semua karakter selain angka
        $wa = preg_replace('/[^0-9]/', '', $wa);

        // Jika diawali 0 → ubah ke 62
        if (str_starts_with($wa, '0')) {
            $wa = '62' . substr($wa, 1);
        }

        // Jika sudah diawali 62 → biarkan
        if (str_starts_with($wa, '62')) {
            return $wa;
        }

        // fallback (kalau format aneh)
        return $wa;
    }

    /**
     * Get all seat reservations for this reservation.
     */
    public function kursiReservasis(): HasMany
    {
        return $this->hasMany(KursiReservasi::class);
    }

    /**
     * Get available tables for a given date and time.
     */
    public static function getAvailableTables(string $tanggal, string $waktu): \Illuminate\Support\Collection
    {
        // Clear stale table holds for past sessions
        KursiReservasi::releaseExpiredTables();

        // Normalize waktu input so callers may pass different formats (e.g. '09:37 PM' or '21:37:00')
        try {
            $waktu = \Carbon\Carbon::parse($waktu)->format('H:i');
        } catch (\Exception $e) {
            // leave as-is if parse fails
        }

        // Get all active tables
        $tables = Meja::where('is_active', true)
            ->orderBy('posisi_row')
            ->orderBy('posisi_col')
            ->get();

        // Consider tables booked if there's a reservation in the SAME HOUR (jam yang sama)
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

        // Mark availability on each table
        $tables->each(function ($table) use ($bookedTableIds) {
            $table->setAttribute('is_available', !in_array($table->id, $bookedTableIds));
        });

        return $tables;
    }
}
