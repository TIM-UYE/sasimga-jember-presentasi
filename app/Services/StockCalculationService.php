<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuBahan;
use App\Models\Stok;
use App\Models\MenuSpecial;
use App\Models\MenuSpecialItem;
use Illuminate\Support\Collection;

class StockCalculationService
{
    protected UnitConversionService $unitConverter;

    public function __construct(UnitConversionService $unitConverter)
    {
        $this->unitConverter = $unitConverter;
    }

    /**
     * Calculate the available stock for a menu based on its ingredients.
     *
     * Uses the ingredient which yields the fewest portions as the limiting factor.
     *
     * @param Menu $menu The menu to calculate stock for
     * @return array{stock: int, details: Collection}
     */
    public function calculateMenuStock(Menu $menu): array
    {
        $ingredients = $menu->komposisiBahan()->with('stok')->get();

        if ($ingredients->isEmpty()) {
            return [
                'stock' => 0,
                'details' => collect([]),
            ];
        }

        $details = collect([]);
        $minPortions = PHP_INT_MAX;

        foreach ($ingredients as $ingredient) {
            $stok = $ingredient->stok;

            if (!$stok) {
                $details->push([
                    'bahan' => 'Unknown',
                    'stok_tersedia' => 0,
                    'satuan_stok' => '',
                    'kebutuhan_per_poris' => $ingredient->jumlah_dibutuhkan,
                    'satuan_kebutuhan' => $ingredient->satuan ?? $stok->satuan ?? 'gram',
                    'cukup_untuk' => 0,
                    'status' => 'Tidak ada data bahan',
                ]);
                $minPortions = 0;
                continue;
            }

            $stockUnit = $stok->satuan ?? 'gram';
            $neededUnit = $ingredient->satuan ?? $stockUnit;

            try {
                $portions = $this->unitConverter->calculatePortions(
                    (float) $stok->jumlah_stok,
                    $stockUnit,
                    (float) $ingredient->jumlah_dibutuhkan,
                    $neededUnit
                );
            } catch (\InvalidArgumentException $e) {
                // If units are incompatible, use raw number
                $stockQuantity = (float) $stok->jumlah_stok;
                $neededQty = (float) $ingredient->jumlah_dibutuhkan;

                // Attempt numeric comparison
                if ($neededQty > 0 && $stockQuantity > 0) {
                    $portions = (int) floor($stockQuantity / $neededQty);
                } else {
                    $portions = 0;
                }
            }

            // Format display
            $formattedStok = $this->unitConverter->formatQuantity((float) $stok->jumlah_stok, $stockUnit);
            $formattedNeed = $this->unitConverter->formatQuantity((float) $ingredient->jumlah_dibutuhkan, $neededUnit);

            $status = $portions <= 0 ? 'Habis' : ($stok->jumlah_stok <= $stok->stok_minimum ? 'Menipis' : 'Tersedia');

            $details->push([
                'bahan' => $stok->nama_bahan,
                'stok_tersedia' => (float) $stok->jumlah_stok,
                'satuan_stok' => $stockUnit,
                'stok_formatted' => $formattedStok,
                'kebutuhan_per_poris' => (float) $ingredient->jumlah_dibutuhkan,
                'satuan_kebutuhan' => $neededUnit,
                'kebutuhan_formatted' => $formattedNeed,
                'cukup_untuk' => $portions,
                'status' => $status,
                'stok_minimum' => (float) $stok->stok_minimum,
            ]);

            if ($portions < $minPortions) {
                $minPortions = $portions;
            }
        }

        $stock = ($minPortions === PHP_INT_MAX || $minPortions < 0) ? 0 : $minPortions;

        return [
            'stock' => $stock,
            'details' => $details,
        ];
    }

    /**
     * Calculate stock for multiple menus at once.
     *
     * @param Collection|array $menus
     * @return array [menu_id => ['stock' => int, 'details' => Collection]]
     */
    public function calculateMenusStock($menus): array
    {
        $results = [];

        foreach ($menus as $menu) {
            $results[$menu->id] = $this->calculateMenuStock($menu);
        }

        return $results;
    }

    /**
     * Deduct ingredients from stock when an order is placed.
     *
     * @param Menu $menu
     * @param int $qty Number of portions ordered
     * @param string $referenceType Type for StokLog reference
     * @param int $referenceId ID for StokLog reference
     * @return array{success: bool, message: string, deductions: array}
     */
    public function deductIngredientsForOrder(
        Menu $menu,
        int $qty,
        string $referenceType = '',
        int $referenceId = 0
    ): array {
        $ingredients = $menu->komposisiBahan()->with('stok')->get();
        $deductions = [];
        $canDeduct = true;

        // First pass: check if all ingredients are sufficient
        foreach ($ingredients as $ingredient) {
            $stok = $ingredient->stok;

            if (!$stok) {
                $deductions[] = [
                    'bahan' => 'Unknown',
                    'status' => 'gagal',
                    'message' => 'Data bahan tidak ditemukan',
                ];
                $canDeduct = false;
                continue;
            }

            $stockUnit = $stok->satuan ?? 'gram';
            $neededUnit = $ingredient->satuan ?? $stockUnit;

            try {
                $totalNeeded = $this->unitConverter->convert(
                    (float) $ingredient->jumlah_dibutuhkan * $qty,
                    $neededUnit,
                    $stockUnit
                );
            } catch (\InvalidArgumentException $e) {
                $totalNeeded = (float) $ingredient->jumlah_dibutuhkan * $qty;
            }

            if ($stok->jumlah_stok < $totalNeeded) {
                $deductions[] = [
                    'bahan' => $stok->nama_bahan,
                    'dibutuhkan' => $totalNeeded,
                    'satuan' => $stockUnit,
                    'tersedia' => (float) $stok->jumlah_stok,
                    'status' => 'gagal',
                    'message' => "Stok {$stok->nama_bahan} tidak mencukupi. Dibutuhkan {$totalNeeded} {$stockUnit}, tersedia {$stok->jumlah_stok} {$stockUnit}.",
                ];
                $canDeduct = false;
            }
        }

        if (!$canDeduct) {
            return [
                'success' => false,
                'message' => 'Stok bahan tidak mencukupi untuk pesanan ini.',
                'deductions' => $deductions,
            ];
        }

        // Second pass: perform the deduction
        foreach ($ingredients as $ingredient) {
            $stok = $ingredient->stok;

            if (!$stok) {
                continue;
            }

            $stockUnit = $stok->satuan ?? 'gram';
            $neededUnit = $ingredient->satuan ?? $stockUnit;

            try {
                $totalNeeded = $this->unitConverter->convert(
                    (float) $ingredient->jumlah_dibutuhkan * $qty,
                    $neededUnit,
                    $stockUnit
                );
            } catch (\InvalidArgumentException $e) {
                $totalNeeded = (float) $ingredient->jumlah_dibutuhkan * $qty;
            }

            $stokSebelum = (float) $stok->jumlah_stok;
            $stok->jumlah_stok = $stokSebelum - $totalNeeded;
            $stok->save();

            // Log the deduction
            \App\Models\StokLog::create([
                'stok_id' => $stok->id,
                'tipe' => 'keluar',
                'jumlah' => $totalNeeded,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => (float) $stok->jumlah_stok,
                'keterangan' => "Penggunaan untuk menu: {$menu->nama_menu} ({$qty} porsi)",
                'referensi_id' => $referenceId ?: null,
                'referensi_type' => $referenceType ?: null,
            ]);

            $deductions[] = [
                'bahan' => $stok->nama_bahan,
                'dibutuhkan' => $totalNeeded,
                'satuan' => $stockUnit,
                'sebelum' => $stokSebelum,
                'sesudah' => (float) $stok->jumlah_stok,
                'status' => 'berhasil',
            ];
        }

        return [
            'success' => true,
            'message' => 'Stok bahan berhasil dikurangi.',
            'deductions' => $deductions,
        ];
    }

    /**
     * Get all low stock ingredients.
     *
     * @return Collection
     */
    public function getLowStockIngredients(): Collection
    {
        return Stok::whereColumn('jumlah_stok', '<=', 'stok_minimum')
            ->orWhere('jumlah_stok', '<=', 0)
            ->orderBy('jumlah_stok', 'asc')
            ->get()
            ->map(function ($item) {
                $item->status_label = $item->status;
                $item->status_color = match ($item->status) {
                    'Habis' => 'red',
                    'Menipis' => 'orange',
                    default => 'green',
                };
                $item->formatted_stok = $this->unitConverter->formatQuantity(
                    (float) $item->jumlah_stok,
                    $item->satuan
                );
                return $item;
            });
    }
}
