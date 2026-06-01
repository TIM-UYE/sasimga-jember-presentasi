<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\KursiReservasi;
use App\Models\Meja;
use App\Models\Lantai;
use App\Notifications\ReservasiStatusNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservasiController extends Controller
{
    /**
     * =========================
     * FRONTEND RESERVASI PAGE
     * =========================
     */
    public function frontend()
    {
        $lantais = Lantai::where('is_active', true)->orderBy('urutan')->get();
        return view('frontend.reservasi.index', compact('lantais'));
    }

    /**
     * =========================
     * GET FLOORS (AJAX)
     * =========================
     */
    public function getFloors()
    {
        $lantais = Lantai::where('is_active', true)
            ->orderBy('urutan')
            ->get()
            ->map(function ($lantai) {
                return [
                    'id' => $lantai->id,
                    'nama' => $lantai->nama,
                    'slug' => $lantai->slug,
                    'preview_image' => $lantai->preview_image_url,
                ];
            });

        return response()->json(['floors' => $lantais]);
    }

    /**
     * =========================
     * GET TABLES BY FLOOR (AJAX)
     * =========================
     */
    public function getTablesByFloor(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'lantai_id' => 'required|integer|exists:lantai,id',
            'jumlah_orang' => 'required|integer|min:1',
        ]);

        $tanggal = $request->tanggal;
        $lantaiId = $request->lantai_id;
        $jumlahOrang = (int) $request->jumlah_orang;

        // Normalize waktu
        try {
            $waktu = Carbon::parse($request->waktu)->format('H:i');
        } catch (\Exception $e) {
            $waktu = $request->waktu;
        }

        // Validasi jam operasional 11:00 - 21:00 untuk reservasi
        $waktuCarbon = Carbon::createFromFormat('H:i', $waktu);
        $buka = Carbon::createFromTime(11, 0);
        $maksimalReservasi = Carbon::createFromTime(21, 0);

        if ($waktuCarbon->lt($buka)) {
            return response()->json([
                'error' => 'Reservasi hanya dapat dilakukan mulai pukul 11:00.',
                'tables' => [],
                'grouped_tables' => [],
            ], 422);
        }

        if ($waktuCarbon->gt($maksimalReservasi)) {
            return response()->json([
                'error' => 'Reservasi maksimal pada pukul 21:00.',
                'tables' => [],
                'grouped_tables' => [],
            ], 422);
        }

        // Validasi minimal 2 jam sebelumnya hanya untuk reservasi hari ini
        $selectedDateTime = Carbon::parse($tanggal . ' ' . $waktu);
        $today = Carbon::today();
        $now = Carbon::now();
        $selectedDate = Carbon::parse($tanggal);
        if ($selectedDate->isSameDay($today)) {
            $minDateTime = $now->copy()->addHours(2);
            if ($selectedDateTime->lt($minDateTime)) {
                return response()->json([
                    'error' => 'Reservasi hari ini harus dibuat minimal 2 jam sebelumnya. Waktu terdekat: ' . $minDateTime->format('d M Y H:i'),
                    'tables' => [],
                    'grouped_tables' => [],
                ], 422);
            }
        }

        // Hitung jumlah meja yang dibutuhkan
        $requiredTables = (int) ceil($jumlahOrang / 4);

        // Dapatkan meja berdasarkan lantai dengan status ketersediaan
        $groupedTables = Meja::getAvailableByFloor($lantaiId, $tanggal, $waktu);

        // Cari kombinasi meja yang valid berdasarkan merge groups
        $validCombinations = $this->findValidTableCombinations($groupedTables, $requiredTables, $lantaiId);

        // Semua meja (flat) untuk display
        $allTables = $groupedTables->flatten()->map(function ($table) {
            return [
                'id' => $table->id,
                'nama_meja' => $table->nama_meja,
                'nomor_meja' => $table->nomor_meja,
                'kapasitas' => $table->kapasitas,
                'pos_x' => $table->pos_x,
                'pos_y' => $table->pos_y,
                'merge_group' => $table->merge_group,
                'is_mergeable' => $table->is_mergeable,
                'is_available' => $table->is_available,
                'table_image' => $table->table_image_url,
                'kategori' => $table->kategori,
            ];
        });

        return response()->json([
            'tables' => $allTables,
            'grouped_tables' => $groupedTables->map(function ($group, $key) {
                return [
                    'group' => $key,
                    'tables' => $group->map(function ($table) {
                        return [
                            'id' => $table->id,
                            'nama_meja' => $table->nama_meja,
                            'nomor_meja' => $table->nomor_meja,
                            'kapasitas' => $table->kapasitas,
                            'is_available' => $table->is_available,
                            'merge_group' => $table->merge_group,
                        ];
                    }),
                ];
            })->values(),
            'valid_combinations' => $validCombinations,
            'required_tables' => $requiredTables,
            'jumlah_orang' => $jumlahOrang,
        ]);
    }

    /**
     * =========================
     * FIND VALID TABLE COMBINATIONS
     * =========================
     */
    private function findValidTableCombinations($groupedTables, int $requiredTables, int $lantaiId): array
    {
        $validCombinations = [];

        foreach ($groupedTables as $groupKey => $tables) {
            $availableInGroup = $tables->filter(function ($table) {
                return $table->is_available;
            })->values();

            $totalAvailable = $availableInGroup->count();

            // Jika grup memiliki jumlah meja yang cukup
            if ($totalAvailable >= $requiredTables) {
                // Jika meja standalone (tidak bisa digabung) tapi butuh lebih dari 1 meja
                if (str_starts_with($groupKey, 'standalone_') && $requiredTables > 1) {
                    continue; // Skip standalone tables that can't merge
                }

                // Jika meja dalam grup mergeable
                if (!str_starts_with($groupKey, 'standalone_')) {
                    $combination = $availableInGroup->take($requiredTables);
                    $validCombinations[] = [
                        'group' => $groupKey,
                        'table_ids' => $combination->pluck('id')->toArray(),
                        'table_names' => $combination->pluck('nama_meja')->toArray(),
                        'total_capacity' => $combination->sum('kapasitas'),
                    ];
                }

                // Untuk standalone, hanya 1 meja per kombinasi
                if (str_starts_with($groupKey, 'standalone_') && $requiredTables === 1) {
                    $table = $availableInGroup->first();
                    if ($table) {
                        $validCombinations[] = [
                            'group' => $groupKey,
                            'table_ids' => [$table->id],
                            'table_names' => [$table->nama_meja],
                            'total_capacity' => $table->kapasitas,
                        ];
                    }
                }
            }

            // Cari kombinasi dari beberapa grup (hanya untuk meja mergeable)
            if (!str_starts_with($groupKey, 'standalone_') && $totalAvailable > 0 && $totalAvailable < $requiredTables) {
                // Coba gabung dengan grup lain yang berdekatan (merge group sama)
                $complementGroups = $groupedTables->filter(function ($otherGroup, $otherKey) use ($groupKey) {
                    return $otherKey !== $groupKey && !str_starts_with($otherKey, 'standalone_');
                });

                foreach ($complementGroups as $otherKey => $otherTables) {
                    $availableOther = $otherTables->filter(function ($table) {
                        return $table->is_available;
                    });

                    $totalFromBoth = $totalAvailable + $availableOther->count();
                    if ($totalFromBoth >= $requiredTables) {
                        $neededFromOther = $requiredTables - $totalAvailable;
                        $combined = $availableInGroup->merge($availableOther->take($neededFromOther));
                        $validCombinations[] = [
                            'group' => $groupKey . '+' . $otherKey,
                            'table_ids' => $combined->pluck('id')->toArray(),
                            'table_names' => $combined->pluck('nama_meja')->toArray(),
                            'total_capacity' => $combined->sum('kapasitas'),
                            'is_cross_group' => true,
                        ];
                    }
                }
            }
        }

        // Untuk standalone yang butuh 1 meja
        if ($requiredTables === 1) {
            foreach ($groupedTables as $groupKey => $tables) {
                $available = $tables->filter(function ($table) {
                    return $table->is_available;
                })->first();
                if ($available) {
                    // Check if already added
                    $exists = false;
                    foreach ($validCombinations as $comb) {
                        if (in_array($available->id, $comb['table_ids'])) {
                            $exists = true;
                            break;
                        }
                    }
                    if (!$exists) {
                        $validCombinations[] = [
                            'group' => $groupKey,
                            'table_ids' => [$available->id],
                            'table_names' => [$available->nama_meja],
                            'total_capacity' => $available->kapasitas,
                        ];
                    }
                }
            }
        }

        return $validCombinations;
    }

    /**
     * =========================
     * GET TABLE DETAIL (AJAX)
     * =========================
     */
    public function getTableDetail(Request $request, $id)
    {
        $table = Meja::with('lantai')->findOrFail($id);

        return response()->json([
            'id' => $table->id,
            'nama_meja' => $table->nama_meja,
            'nomor_meja' => $table->nomor_meja,
            'kapasitas' => $table->kapasitas,
            'kategori' => $table->kategori,
            'kategori_label' => $table->kategori_label,
            'lantai' => $table->lantai ? $table->lantai->nama : null,
            'pos_x' => $table->pos_x,
            'pos_y' => $table->pos_y,
            'table_image' => $table->table_image_url,
            'is_mergeable' => $table->is_mergeable,
            'merge_group' => $table->merge_group,
        ]);
    }

    /**
     * =========================
     * GET AVAILABLE TABLES (AJAX) - Legacy
     * =========================
     */
    public function getAvailableTables(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required',
        ]);

        $tanggal = $request->tanggal;

        try {
            $waktu = Carbon::parse($request->waktu)->format('H:i');
        } catch (\Exception $e) {
            $waktu = $request->waktu;
        }

        // Validasi jam operasional 11:00 - 21:00
        $waktuCarbon = Carbon::createFromFormat('H:i', $waktu);
        $buka = Carbon::createFromTime(11, 0);
        $maksimalReservasi = Carbon::createFromTime(21, 0);

        if ($waktuCarbon->lt($buka) || $waktuCarbon->gt($maksimalReservasi)) {
            return response()->json([
                'tables' => [],
                'available_count' => 0,
                'all_full' => true,
                'error' => 'Reservasi hanya dapat dilakukan pada jam 11:00 - 21:00.'
            ], 422);
        }

        $reservationDateTime = Carbon::parse($tanggal . ' ' . $waktu);
        $now = Carbon::now();
        if ($reservationDateTime->isSameDay($now) && $reservationDateTime->lt($now->copy()->addHours(2))) {
            return response()->json([
                'tables' => [],
                'available_count' => 0,
                'all_full' => true,
                'error' => 'Reservasi hari ini harus dibuat minimal 2 jam sebelumnya.'
            ], 422);
        }

        $tables = Reservasi::getAvailableTables($tanggal, $waktu);
        $availableCount = $tables->where('is_available', true)->count();

        return response()->json([
            'tables' => $tables,
            'available_count' => $availableCount,
            'all_full' => $tables->count() > 0 && $availableCount === 0,
        ]);
    }

    /**
     * =========================
     * ADMIN RESERVASI LIST
     * =========================
     */
    public function index()
    {
        $reservasis = Reservasi::with('lantai')->orderBy('created_at', 'desc')->get();
        return view('admin.reservasi.index', compact('reservasis'));
    }

    /**
     * =========================
     * STORE RESERVASI
     * =========================
     */
    public function store(Request $request)
    {
        $mejaIds = $this->normalizeMejaIds($request->input('meja_ids', []));

        $request->merge([
            'meja_ids' => $mejaIds,
        ]);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_wa' => 'required|string|max:20',
            'tanggal_reservasi' => 'required|date|after_or_equal:today',
            'waktu_reservasi' => 'required',
            'jumlah_orang' => 'required|integer|min:1',
            'lantai_id' => 'required|integer|exists:lantai,id',
            'meja_ids' => 'required|array|min:1',
            'meja_ids.*' => 'integer|exists:meja,id',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'tanggal_reservasi.required' => 'Tanggal reservasi wajib diisi.',
            'tanggal_reservasi.after_or_equal' => 'Tanggal reservasi tidak boleh kurang dari hari ini.',
            'waktu_reservasi.required' => 'Waktu reservasi wajib diisi.',
            'jumlah_orang.required' => 'Jumlah orang wajib diisi.',
            'jumlah_orang.min' => 'Jumlah orang minimal 1.',
            'lantai_id.required' => 'Lantai wajib dipilih.',
            'meja_ids.required' => 'Silakan pilih minimal 1 meja.',
        ]);

        // Normalize waktu
        try {
            $reservationDateTime = Carbon::parse($request->tanggal_reservasi . ' ' . $request->waktu_reservasi);
            $normalizedWaktu = $reservationDateTime->format('H:i');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['waktu_reservasi' => 'Format waktu tidak valid.'])
                ->withInput();
        }

        $waktuCarbon = Carbon::createFromFormat('H:i', $normalizedWaktu);
        $buka = Carbon::createFromTime(11, 0);
        $maksimalReservasi = Carbon::createFromTime(21, 0);

        // Validasi jam operasional 11:00 - 21:00
        if ($waktuCarbon->lt($buka)) {
            return redirect()->back()
                ->withErrors(['waktu_reservasi' => 'Reservasi hanya dapat dilakukan mulai pukul 11:00.'])
                ->withInput();
        }

        if ($waktuCarbon->gt($maksimalReservasi)) {
            return redirect()->back()
                ->withErrors(['waktu_reservasi' => 'Reservasi maksimal pada pukul 21:00.'])
                ->withInput();
        }

        // Validasi minimal 2 jam sebelumnya hanya untuk reservasi hari ini
        $now = Carbon::now();
        $today = Carbon::today();
        $reservationDate = Carbon::parse($request->tanggal_reservasi);
        if ($reservationDate->isSameDay($today)) {
            $minReservationTime = $now->copy()->addHours(2);

            if ($reservationDateTime->lt($minReservationTime)) {
                return redirect()->back()
                    ->withErrors([
                        'waktu_reservasi' => 'Reservasi hari ini harus dibuat minimal 2 jam sebelumnya. Waktu terdekat yang tersedia: ' . $minReservationTime->format('d M Y H:i'),
                    ])
                    ->withInput();
            }
        }

        // Validasi tanggal tidak di masa lalu
        if ($reservationDate->lt($today)) {
            return redirect()->back()
                ->withErrors(['tanggal_reservasi' => 'Tanggal reservasi tidak boleh di masa lalu.'])
                ->withInput();
        }

        // Validasi kapasitas meja dan jumlah meja yang dibutuhkan
        $selectedTables = Meja::whereIn('id', $mejaIds)->get();
        $totalCapacity = $selectedTables->sum('kapasitas');
        $requiredTables = (int) ceil($request->jumlah_orang / 4);

        if ($totalCapacity < $request->jumlah_orang) {
            return redirect()->back()
                ->withErrors([
                    'meja_ids' => 'Kapasitas meja tidak mencukupi untuk ' . $request->jumlah_orang . ' orang.',
                ])
                ->withInput();
        }

        if (count($mejaIds) !== $requiredTables) {
            return redirect()->back()
                ->withErrors([
                    'meja_ids' => 'Untuk ' . $request->jumlah_orang . ' orang, pilih tepat ' . $requiredTables . ' meja.',
                ])
                ->withInput();
        }

        // Validasi penggabungan meja (merge groups)
        $mergeValidation = $this->validateMergeGroups($selectedTables);
        if ($mergeValidation !== true) {
            return redirect()->back()
                ->withErrors(['meja_ids' => $mergeValidation])
                ->withInput();
        }

        // Release stale reservations
        KursiReservasi::releaseExpiredTables();

        // Gunakan database transaction untuk mencegah double booking
        try {
            DB::beginTransaction();

            // Check table availability (lock for update to prevent race condition)
            $hour = $waktuCarbon->format('H');

            $bookedTables = KursiReservasi::whereIn('meja_id', $mejaIds)
                ->where('tanggal', $request->tanggal_reservasi)
                ->whereRaw('HOUR(waktu_sesi) = ?', [$hour])
                ->where('tersedia', false)
                ->lockForUpdate()
                ->count();

            if ($bookedTables > 0) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors([
                        'meja_ids' => 'Beberapa meja sudah dipesan. Silakan pilih meja lain.',
                    ])
                    ->withInput();
            }

            // Buat reservasi
            $reservasi = Reservasi::create([
                'nama' => $request->nama,
                'nomor_wa' => $request->nomor_wa,
                'tanggal_reservasi' => $request->tanggal_reservasi,
                'waktu_reservasi' => $normalizedWaktu,
                'jumlah_orang' => $request->jumlah_orang,
                'lantai_id' => $request->lantai_id,
                'jumlah_meja' => count($mejaIds),
                'status' => 'pending',
                'meja_ids' => $mejaIds,
            ]);

            // Mark tables as booked
            foreach ($mejaIds as $mejaId) {
                KursiReservasi::updateOrCreate(
                    [
                        'meja_id' => $mejaId,
                        'tanggal' => $request->tanggal_reservasi,
                        'waktu_sesi' => $normalizedWaktu,
                    ],
                    [
                        'tersedia' => false,
                        'reservasi_id' => $reservasi->id,
                    ]
                );
            }

            DB::commit();

            // Send WhatsApp notification
            try {
                $reservasi->notify(new ReservasiStatusNotification($reservasi));
            } catch (\Exception $e) {
                Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
            }

            return redirect()->back()->with(
                'success',
                'Reservasi berhasil dikirim! Silakan cek WhatsApp Anda untuk detail reservasi.'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reservation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan sistem. Silakan coba lagi.'])
                ->withInput();
        }
    }

    /**
     * =========================
     * VALIDATE MERGE GROUPS
     * =========================
     */
    private function validateMergeGroups($selectedTables): bool|string
    {
        if ($selectedTables->count() <= 1) {
            return true;
        }

        $firstTable = $selectedTables->first();

        foreach ($selectedTables as $table) {
            // Each table in multi-table selection must be mergeable
            if (!$table->is_mergeable) {
                return 'Meja ' . $table->nama_meja . ' tidak dapat digabung dengan meja lain.';
            }

            // All tables must be in the same merge group
            if ($table->merge_group !== $firstTable->merge_group) {
                return 'Meja ' . $table->nama_meja . ' tidak dapat digabung dengan meja ' . $firstTable->nama_meja . ' karena berada di grup yang berbeda.';
            }
        }

        // Validasi jumlah meja dalam grup
        $allTablesInGroup = Meja::where('merge_group', $firstTable->merge_group)
            ->where('is_active', true)
            ->count();

        if ($selectedTables->count() > $allTablesInGroup) {
            return 'Jumlah meja yang dipilih melebihi jumlah meja yang tersedia dalam grup ini.';
        }

        return true;
    }

    /**
     * =========================
     * UPDATE STATUS RESERVASI
     * =========================
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $reservasi = Reservasi::with('lantai')->findOrFail($id);

        $reservasi->update([
            'status' => $request->status,
        ]);

        // If cancelled, free up the tables
        if ($request->status === 'cancelled') {
            if ($reservasi->meja_ids) {
                foreach ($reservasi->meja_ids as $mejaId) {
                    KursiReservasi::where('meja_id', $mejaId)
                        ->where('tanggal', $reservasi->tanggal_reservasi)
                        ->where('waktu_sesi', $reservasi->waktu_reservasi)
                        ->update([
                            'tersedia' => true,
                            'reservasi_id' => null,
                        ]);
                }
            }
        }

        return redirect()
            ->route('admin.reservasi.index')
            ->with(
                'success',
                'Status reservasi berhasil diperbarui. Notifikasi WhatsApp telah dikirim.'
            );
    }

    /**
     * =========================
     * RESERVASI DETAIL (AJAX)
     * =========================
     */
    public function show($id)
    {
        $reservasi = Reservasi::with('lantai')->findOrFail($id);
        $selectedTables = collect([]);

        if ($reservasi->meja_ids) {
            $selectedTables = Meja::whereIn('id', $reservasi->meja_ids)->get();
        }

        $totalCapacity = $selectedTables->sum('kapasitas');

        return view('admin.reservasi.show', compact('reservasi', 'selectedTables', 'totalCapacity'));
    }

    /**
     * =========================
     * DELETE RESERVASI
     * =========================
     */
    public function destroy($id)
    {
        $reservasi = Reservasi::findOrFail($id);

        // Free up the tables first
        if ($reservasi->meja_ids) {
            foreach ($reservasi->meja_ids as $mejaId) {
                KursiReservasi::where('meja_id', $mejaId)
                    ->where('tanggal', $reservasi->tanggal_reservasi)
                    ->where('waktu_sesi', $reservasi->waktu_reservasi)
                    ->delete();
            }
        }

        $reservasi->delete();

        return redirect()
            ->route('admin.reservasi.index')
            ->with(
                'success',
                'Reservasi berhasil dihapus.'
            );
    }

    /**
     * =========================
     * NORMALIZE MEJA IDS
     * =========================
     */
    private function normalizeMejaIds(mixed $value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            } else {
                $value = explode(',', $value);
            }
        }

        if (!is_array($value)) {
            return [];
        }

        return collect($value)
            ->map(fn($id) => filter_var($id, FILTER_VALIDATE_INT))
            ->filter(fn($id) => $id !== false && $id > 0)
            ->unique()
            ->values()
            ->all();
    }
}
