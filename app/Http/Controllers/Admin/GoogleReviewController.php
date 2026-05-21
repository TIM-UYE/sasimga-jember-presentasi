<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GoogleReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GoogleReviewController extends Controller
{
    protected GoogleReviewService $googleReviewService;

    public function __construct(GoogleReviewService $googleReviewService)
    {
        $this->googleReviewService = $googleReviewService;
    }

    /**
     * Display dashboard Google Reviews
     */
    public function index(): View
    {
        $stats = $this->googleReviewService->getDashboardStats();
        $ratingDistribution = $this->googleReviewService->getRatingDistribution();
        $recentReviews = $this->googleReviewService->getRecentReviews(10);
        $reviews = $this->googleReviewService->getReviews(request()->all());

        return view('admin.google-reviews.index', compact(
            'stats',
            'ratingDistribution',
            'recentReviews',
            'reviews'
        ));
    }

    /**
     * SCRAPING ALL: Ambil SEMUA rating & review dari Google Maps
     * Full scraping - mengambil semua review (sesuai maxReviews config)
     */
    public function syncScrapeAll(): JsonResponse
    {
        try {
            Log::info('[GoogleReviewController] Scrape All triggered');

            $result = $this->googleReviewService->runFullScraping();

            if ($result['success']) {
                $stats = $result['stats'];
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'stats' => $stats,
                    'status' => $result['status'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'status' => $result['status'],
            ], 422);
        } catch (\Throwable $e) {
            Log::error('[GoogleReviewController] Scrape All error', [
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'status' => 'ERROR',
            ], 500);
        }
    }

    /**
     * UPDATE DATA: Hanya ambil review TERBARU yang belum discrape
     * Incremental scraping - lebih cepat, hemat API request
     */
    public function syncUpdateData(): JsonResponse
    {
        try {
            Log::info('[GoogleReviewController] Update Data triggered');

            $result = $this->googleReviewService->runIncrementalScraping();

            if ($result['success']) {
                $stats = $result['stats'];
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'stats' => $stats,
                    'status' => $result['status'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'status' => $result['status'],
            ], 422);
        } catch (\Throwable $e) {
            Log::error('[GoogleReviewController] Update Data error', [
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'status' => 'ERROR',
            ], 500);
        }
    }

    /**
     * Trigger scraping async (trigger only, return run ID)
     */
    public function syncAsync(): JsonResponse
    {
        try {
            $result = $this->googleReviewService->triggerScraping();

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Scraping dimulai secara async.',
                    'run_id' => $result['run_id'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check status of an async scraping run
     */
    public function checkStatus(Request $request): JsonResponse
    {
        $request->validate(['run_id' => 'required|string']);

        $status = $this->googleReviewService->getRunStatus($request->run_id);

        if ($status['success'] && in_array($status['status'], ['SUCCEEDED', 'FINISHED'])) {
            // Auto fetch jika sudah selesai
            $result = $this->googleReviewService->scrapeThenFetch($request->run_id);

            return response()->json([
                'success' => true,
                'run_status' => $status['status'],
                'fetched' => $result['success'],
                'stats' => $result['stats'] ?? null,
                'message' => $result['message'] ?? 'Scraping selesai.',
            ]);
        }

        return response()->json([
            'success' => true,
            'run_status' => $status['status'],
            'message' => $status['message'] ?? "Status: {$status['status']}",
        ]);
    }

    /**
     * API: Get reviews with filters (for AJAX)
     */
    public function apiReviews(Request $request): JsonResponse
    {
        $reviews = $this->googleReviewService->getReviews($request->all());

        return response()->json([
            'success' => true,
            'data' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    /**
     * API: Get dashboard stats (for AJAX refresh)
     */
    public function apiStats(): JsonResponse
    {
        $stats = $this->googleReviewService->getDashboardStats();
        $ratingDistribution = $this->googleReviewService->getRatingDistribution();

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'rating_distribution' => $ratingDistribution,
        ]);
    }

    /**
     * API: Get recent reviews (for AJAX refresh)
     */
    public function apiRecentReviews(): JsonResponse
    {
        $reviews = $this->googleReviewService->getRecentReviews(10);

        return response()->json([
            'success' => true,
            'data' => $reviews,
        ]);
    }

    /**
     * API: Get sync status info
     */
    public function apiSyncStatus(): JsonResponse
    {
        $stats = $this->googleReviewService->getDashboardStats();

        return response()->json([
            'success' => true,
            'last_scraped' => $stats['last_scraped'],
            'total_reviews' => $stats['total_reviews'],
            'average_rating' => $stats['average_rating'],
        ]);
    }

    /**
     * Delete a review
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $review = \App\Models\GoogleReview::findOrFail($id);
            $review->delete();

            Log::info('[GoogleReviewController] Review deleted', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dihapus.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus review: ' . $e->getMessage(),
            ], 500);
        }
    }
}
