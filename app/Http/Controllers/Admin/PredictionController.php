<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prediction;
use App\Models\Menu;
use App\Services\PredictionService;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    protected PredictionService $predictionService;

    public function __construct(PredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    /**
     * Show prediction dashboard
     */
    public function index()
    {
        $now = now();
        $month = request('month', $now->month);
        $year = request('year', $now->year);

        $predictions = Prediction::where('month', $month)
            ->where('year', $year)
            ->orderBy('predicted_sales', 'desc')
            ->get();

        $aiStatus = $this->predictionService->getAiStatus();
        $stockRequirements = $this->predictionService->calculateStockRequirements($month, $year);
        $restockRecommendations = $this->predictionService->generateRestockRecommendations($month, $year);

        // Get top menu for chart
        $topMenus = $predictions->take(10)->values();

        // Get all menus with their predictions for the form
        $allMenus = Menu::where('is_available', true)->count();

        // Get historical sales for the top menu
        $historyData = [];
        if ($predictions->isNotEmpty()) {
            $topMenuId = $predictions->first()->menu_id;
            $historyData = $this->predictionService->getHistoricalSales($topMenuId);
        }

        // Stats
        $totalPredicted = $predictions->sum('predicted_sales');
        $avgConfidence = $predictions->avg('confidence');
        $warningCount = collect($stockRequirements)->where('status', 'Warning')->count();

        return view('admin.prediksi.index', compact(
            'predictions',
            'aiStatus',
            'stockRequirements',
            'restockRecommendations',
            'month',
            'year',
            'topMenus',
            'allMenus',
            'historyData',
            'totalPredicted',
            'avgConfidence',
            'warningCount'
        ));
    }

    /**
     * Run prediction for selected month/year
     */
    public function runPrediction(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024|max:2035',
        ]);

        $month = (int) $request->month;
        $year = (int) $request->year;

        $result = $this->predictionService->runPrediction($month, $year);

        return redirect()
            ->route('admin.prediksi.index', ['month' => $month, 'year' => $year])
            ->with('success', "Prediksi berhasil dijalankan! {$result['total_menus']} menu diprediksi. Status AI: {$result['ai_status']}");
    }

    /**
     * AI Status check endpoint (AJAX)
     */
    public function checkAiStatus()
    {
        $status = $this->predictionService->getAiStatus();
        return response()->json([
            'status' => $status,
            'is_online' => $status === 'AI Online',
        ]);
    }
}
