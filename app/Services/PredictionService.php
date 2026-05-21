<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Prediction;
use App\Models\MenuBahan;
use App\Models\Stok;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PredictionService
{
    protected string $aiUrl;
    protected string $aiHealthUrl;

    public function __construct()
{
    $this->aiUrl = env(
        'AI_PREDICTION_URL'
    );

    $this->aiHealthUrl = env(
        'AI_HEALTH_URL'
    );
}

    /**
     * Check if AI service is online
     */
    public function checkAiStatus(): string
    {
        try {
            $response = Http::timeout(3)->get($this->aiHealthUrl);
            if ($response->successful()) {
                return 'AI Online';
            }
        } catch (\Exception $e) {
            // AI is offline
        }
        return 'AI Offline';
    }

    /**
     * Run prediction for all active menus in a given month/year
     */
    public function runPrediction(int $month, int $year): array
    {
        $aiStatus = $this->checkAiStatus();
        $menus = Menu::where('is_available', true)->get();
        $results = [];

        if ($aiStatus === 'AI Online') {
            $results = $this->predictWithAi($menus, $month, $year);
        } else {
            $results = $this->predictWithFallback($menus, $month, $year);
        }

        // Save all predictions to database
        foreach ($results as &$result) {
            $this->savePrediction($result, $month, $year, $aiStatus);
        }

        return [
            'ai_status' => $aiStatus,
            'predictions' => $results,
            'total_menus' => count($results),
        ];
    }

    /**
     * Call FastAPI to get predictions
     */
    protected function predictWithAi($menus, int $month, int $year): array
    {
        $results = [];

        foreach ($menus as $menu) {
            $payload = [
                'month' => $month,
                'year' => $year,
                'sales_history' => [
                    [
                        'menu_id' => $menu->id,
                        'menu_name' => $menu->nama_menu,
                        'price' => (float) $menu->harga,
                        'promo' => 0,
                    ],
                ],
            ];

            try {
                $response = Http::timeout(10)->post($this->aiUrl, $payload);

                if ($response->successful()) {
                    $body = $response->json();
                    $prediction = $body['predictions'][0] ?? null;

                    if ($prediction) {
                        $results[] = [
                            'menu_id' => $menu->id,
                            'menu_name' => $menu->nama_menu,
                            'predicted_sales' => (int) ($prediction['predicted_sales'] ?? 0),
                            'confidence' => (int) ($prediction['confidence'] ?? 0),
                        ];
                        continue;
                    }
                }
            } catch (\Exception $e) {
                // Fall through to fallback
            }

            // Fallback for individual menu if AI fails mid-way
            $results[] = $this->generateFallbackPrediction($menu, $month, $year);
        }

        return $results;
    }

    /**
     * Fallback prediction when AI is offline
     */
    protected function predictWithFallback($menus, int $month, int $year): array
    {
        $results = [];

        foreach ($menus as $menu) {
            $results[] = $this->generateFallbackPrediction($menu, $month, $year);
        }

        return $results;
    }

    /**
     * Generate fallback prediction using historical average + 10% growth
     */
    protected function generateFallbackPrediction($menu, int $month, int $year): array
    {
        $avgSales = \App\Models\OrderItem::where('menu_id', $menu->id)
            ->whereHas('order', function ($q) {
                $q->where('status', 'selesai');
            })
            ->selectRaw('COALESCE(AVG(qty), 0) as avg_qty')
            ->value('avg_qty');

        $predictedSales = max(10, (int) round($avgSales * 1.10));

        return [
            'menu_id' => $menu->id,
            'menu_name' => $menu->nama_menu,
            'predicted_sales' => $predictedSales,
            'confidence' => 50,
        ];
    }

    /**
     * Save a single prediction to database
     */
    protected function savePrediction(array &$result, int $month, int $year, string $aiStatus): void
    {
        Prediction::updateOrCreate(
            [
                'menu_id' => $result['menu_id'],
                'month' => $month,
                'year' => $year,
            ],
            [
                'menu_name' => $result['menu_name'],
                'predicted_sales' => $result['predicted_sales'],
                'confidence' => $result['confidence'],
                'ai_status' => $aiStatus,
            ]
        );

        $result['ai_status'] = $aiStatus;
    }

    /**
     * Get latest predictions for a specific month/year
     */
    public function getPredictions(int $month, int $year)
    {
        return Prediction::where('month', $month)
            ->where('year', $year)
            ->orderBy('predicted_sales', 'desc')
            ->get();
    }

    // ========================================================================
    // UNIT CONVERSION HELPERS
    // ========================================================================

    /**
     * Convert value from any unit to GRAMS (internal standard).
     * All internal calculations use grams as base unit.
     */
    protected function toGrams(float $value, string $unit): float
    {
        $unit = strtolower(trim($unit));

        if ($unit === 'kg' || $unit === 'kilogram' || $unit === 'kilo') {
            return $value * 1000;
        }
        if ($unit === 'gram' || $unit === 'g' || $unit === 'gr') {
            return $value;
        }
        // For non-weight units (pcs, liter), return as-is
        return $value;
    }

    /**
     * Convert grams to display value with unit.
     * - grams >= 1000 → show as kg
     * - grams < 1000 → show as grams
     */
    protected function gramToDisplay(float $gramValue): array
    {
        if ($gramValue >= 1000) {
            return [
                'value' => round($gramValue / 1000, 2),
                'unit' => 'kg',
            ];
        }
        return [
            'value' => round($gramValue, 2),
            'unit' => 'gram',
        ];
    }

    /**
     * Format a non-weight value with original unit.
     * For pcs, liter, etc. just return as-is.
     */
    protected function formatNonWeightDisplay(float $value, string $unit): array
    {
        return [
            'value' => round($value, 2),
            'unit' => $unit,
        ];
    }

    /**
     * Convert ingredient requirement to grams.
     * jumlah_dibutuhkan is stored in gram (e.g., 400 = 400 gram per porsi).
     * But the satuan on menu_bahans might differ from stok.satuan.
     */
    protected function ingredientToGrams(float $jumlahDibutuhkan, string $menuBahanSatuan, float $stokJumlahStok, string $stokSatuan): array
    {
        $bahanUnit = strtolower(trim($menuBahanSatuan));
        $stokUnit = strtolower(trim($stokSatuan));

        // If menu_bahan.satuan is 'gram' or empty/null (default gram), treat as grams
        $gramPerPorsi = $this->toGrams($jumlahDibutuhkan, $bahanUnit ?: 'gram');

        // Convert stok to grams too
        $stokGram = $this->toGrams($stokJumlahStok, $stokUnit);

        // Convert stok_minimum to grams
        // stok_minimum is stored in same unit as stok.jumlah_stok

        Log::debug('IngredientCalculation', [
            'jumlah_dibutuhkan' => $jumlahDibutuhkan,
            'menu_bahan_satuan' => $menuBahanSatuan,
            'stok_jumlah' => $stokJumlahStok,
            'stok_satuan' => $stokSatuan,
            'gram_per_porsi' => $gramPerPorsi,
            'stok_gram' => $stokGram,
        ]);

        return [
            'gram_per_porsi' => $gramPerPorsi,
            'stok_gram' => $stokGram,
        ];
    }

    // ========================================================================
    // STOCK REQUIREMENTS CALCULATION
    // ========================================================================

    /**
     * Calculate stock requirements based on predictions.
     * ALL calculations in grams internally, display converted to kg.
     */
    public function calculateStockRequirements(int $month, int $year): array
    {
        $predictions = $this->getPredictions($month, $year);
        $requirements = [];

        foreach ($predictions as $prediction) {
            $menu = $prediction->menu;
            if (!$menu) continue;

            $predictedSales = (int) $prediction->predicted_sales;

            $ingredients = MenuBahan::where('menuable_id', $menu->id)
                ->where('menuable_type', Menu::class)
                ->with('stok')
                ->get();

            foreach ($ingredients as $ingredient) {
                $stok = $ingredient->stok;
                if (!$stok) continue;

                // Convert to grams for calculation
                $converted = $this->ingredientToGrams(
                    (float) $ingredient->jumlah_dibutuhkan,
                    (string) ($ingredient->satuan ?? 'gram'),
                    (float) $stok->jumlah_stok,
                    (string) ($stok->satuan ?? 'gram')
                );

                $gramPerPorsi = $converted['gram_per_porsi'];
                $stokGram = $converted['stok_gram'];

                // Formula: gram_per_porsi × predicted_sales = total gram needed
                $totalGram = $gramPerPorsi * $predictedSales;
                $shortageGram = max(0, $totalGram - $stokGram);

                // Display conversion
                $neededDisplay = $this->gramToDisplay($totalGram);
                $stokDisplay = $this->gramToDisplay($stokGram);
                $shortageDisplay = $this->gramToDisplay($shortageGram);

                Log::info('StockRequirement', [
                    'menu' => $prediction->menu_name,
                    'bahan' => $stok->nama_bahan,
                    'predicted_sales' => $predictedSales,
                    'gram_per_porsi' => $gramPerPorsi,
                    'total_gram' => $totalGram,
                    'total_kg' => $totalGram / 1000,
                    'stok_gram' => $stokGram,
                    'stok_kg' => $stokGram / 1000,
                    'shortage_gram' => $shortageGram,
                    'shortage_kg' => $shortageGram / 1000,
                    'status' => $shortageGram > 0 ? 'Warning' : 'Aman',
                ]);

                $requirements[] = [
                    'menu_name' => $prediction->menu_name,
                    'predicted_sales' => $predictedSales,
                    'bahan' => $stok->nama_bahan,
                    'satuan' => $neededDisplay['unit'],
                    'kebutuhan' => $neededDisplay['value'],
                    'kebutuhan_gram' => $totalGram,
                    'tersedia' => $stokDisplay['value'],
                    'tersedia_gram' => $stokGram,
                    'kekurangan' => $shortageDisplay['value'],
                    'kekurangan_gram' => $shortageGram,
                    'status' => $shortageGram > 0 ? 'Warning' : 'Aman',
                ];
            }
        }

        return $requirements;
    }

    /**
     * Get historical sales data for a menu
     */
    public function getHistoricalSales(int $menuId): array
    {
        $history = \App\Models\OrderItem::where('menu_id', $menuId)
            ->whereHas('order', function ($q) {
                $q->where('status', 'selesai');
            })
            ->selectRaw('MONTH(orders.created_at) as month, YEAR(orders.created_at) as year, SUM(qty) as total')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->groupByRaw('MONTH(orders.created_at), YEAR(orders.created_at)')
            ->orderByRaw('YEAR(orders.created_at) ASC, MONTH(orders.created_at) ASC')
            ->limit(6)
            ->get()
            ->toArray();

        return $history;
    }

    // ========================================================================
    // RESTOCK RECOMMENDATIONS
    // ========================================================================

    /**
     * Generate restock recommendations grouped by bahan.
     * ALL calculations in GRAMS internally.
     * Display converted to KG only in blade.
     */
    public function generateRestockRecommendations(int $month, int $year): array
    {
        $predictions = $this->getPredictions($month, $year);
        $bahanGroups = [];

        foreach ($predictions as $prediction) {
            $menu = $prediction->menu;
            if (!$menu) continue;

            $predictedSales = (int) $prediction->predicted_sales;

            $ingredients = MenuBahan::where('menuable_id', $menu->id)
                ->where('menuable_type', Menu::class)
                ->with('stok')
                ->get();

            foreach ($ingredients as $ingredient) {
                $stok = $ingredient->stok;
                if (!$stok) continue;

                $bahanId = $stok->id;

                // Convert to grams
                $converted = $this->ingredientToGrams(
                    (float) $ingredient->jumlah_dibutuhkan,
                    (string) ($ingredient->satuan ?? 'gram'),
                    (float) $stok->jumlah_stok,
                    (string) ($stok->satuan ?? 'gram')
                );

                $gramPerPorsi = $converted['gram_per_porsi'];
                $stokGram = $converted['stok_gram'];

                // Convert stok_minimum to grams
                $stokMinGram = $this->toGrams((float) $stok->stok_minimum, (string) ($stok->satuan ?? 'gram'));

                // Formula: gram_per_porsi × predicted_sales = total gram needed
                $totalGram = $gramPerPorsi * $predictedSales;
                $shortageGram = max(0, $totalGram - $stokGram);
                $recommendedBuyGram = $shortageGram > 0 ? $shortageGram + $stokMinGram : 0;

                Log::info('RestockCalc', [
                    'menu' => $prediction->menu_name,
                    'bahan' => $stok->nama_bahan,
                    'predicted_sales' => $predictedSales,
                    'gram_per_porsi' => $gramPerPorsi,
                    'total_gram' => $totalGram,
                    'total_kg' => round($totalGram / 1000, 2),
                    'stok_gram' => $stokGram,
                    'stok_kg' => round($stokGram / 1000, 2),
                    'shortage_gram' => $shortageGram,
                    'shortage_kg' => round($shortageGram / 1000, 2),
                    'recommended_buy_gram' => $recommendedBuyGram,
                    'recommended_buy_kg' => round($recommendedBuyGram / 1000, 2),
                ]);

                if (!isset($bahanGroups[$bahanId])) {
                    $bahanGroups[$bahanId] = [
                        'bahan_id' => $stok->id,
                        'nama_bahan' => $stok->nama_bahan,
                        'original_satuan' => $stok->satuan ?? 'gram',
                        // RAW values in grams
                        'total_kebutuhan_gram' => 0,
                        'total_tersedia_gram' => $stokGram,
                        'stok_minimum_gram' => $stokMinGram,
                        'total_kekurangan_gram' => 0,
                        'rekomendasi_beli_gram' => 0,
                        'menus' => [],
                    ];
                }

                // Accumulate grams (NOT kg, NOT double-converted)
                $bahanGroups[$bahanId]['total_kebutuhan_gram'] += $totalGram;
                $bahanGroups[$bahanId]['total_kekurangan_gram'] += $shortageGram;
                $bahanGroups[$bahanId]['rekomendasi_beli_gram'] = $bahanGroups[$bahanId]['total_kekurangan_gram'] > 0
                    ? $bahanGroups[$bahanId]['total_kekurangan_gram'] + $bahanGroups[$bahanId]['stok_minimum_gram']
                    : 0;

                // Format per-menu requirement for display
                $menuDisplay = $this->gramToDisplay($totalGram);
                $bahanGroups[$bahanId]['menus'][] = [
                    'menu_name' => $prediction->menu_name,
                    'predicted_sales' => $predictedSales,
                    'kebutuhan_gram' => $totalGram,
                    'kebutuhan_display' => $menuDisplay['value'],
                    'satuan_display' => $menuDisplay['unit'],
                ];
            }
        }

        // Convert accumulated grams to display values for each group
        foreach ($bahanGroups as &$group) {
            $kebutuhanDisplay = $this->gramToDisplay($group['total_kebutuhan_gram']);
            $tersediaDisplay = $this->gramToDisplay($group['total_tersedia_gram']);
            $kekuranganDisplay = $this->gramToDisplay($group['total_kekurangan_gram']);
            $beliDisplay = $this->gramToDisplay($group['rekomendasi_beli_gram']);
            $minDisplay = $this->gramToDisplay($group['stok_minimum_gram']);

            $group['display_satuan'] = $kebutuhanDisplay['unit'];
            $group['total_kebutuhan_display'] = $kebutuhanDisplay['value'];
            $group['total_tersedia'] = $tersediaDisplay['value'];
            $group['total_kekurangan_display'] = $kekuranganDisplay['value'];
            $group['rekomendasi_beli_display'] = $beliDisplay['value'];
            $group['stok_minimum'] = $minDisplay['value'];

            // Also keep a "total_kebutuhan" (raw grams) for comparison
            $group['total_kebutuhan'] = $group['total_kebutuhan_gram'];
            $group['total_kekurangan'] = $group['total_kekurangan_gram'];
            $group['rekomendasi_beli'] = $group['rekomendasi_beli_gram'];
        }
        unset($group);

        // Sort: bahan with shortage first
        usort($bahanGroups, function ($a, $b) {
            return $b['total_kekurangan_gram'] <=> $a['total_kekurangan_gram'];
        });

        return array_values($bahanGroups);
    }

    /**
     * Get AI status from FastAPI health endpoint
     */
    public function getAiStatus(): string
    {
        return $this->checkAiStatus();
    }
}
