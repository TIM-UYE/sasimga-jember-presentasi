<?php

namespace App\Services;

use App\Models\GoogleReview;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleReviewService
{
    protected ?string $apifyToken = '';
    protected string $actorId = 'compass~crawler-google-places';
    protected string $searchUrl = '';
    protected int $maxReviews = 50;
    protected array $placeIds = [];
    protected int $timeout = 120;

    public function __construct()
    {
        $this->apifyToken = config('services.google_reviews.apify_token', env('APIFY_TOKEN', ''));
        $this->actorId = config('services.google_reviews.actor_id', 'compass~crawler-google-places');
        $this->searchUrl = config('services.google_reviews.search_url', '');
        $this->maxReviews = (int) config('services.google_reviews.max_reviews', 50);
        $this->placeIds = config('services.google_reviews.place_ids', []);
        $this->timeout = (int) config('services.google_reviews.timeout', 120);
    }

    /**
     * ================================================================
     *  1. TRIGGER SCRAPING APIFY
     * ================================================================
     * Memulai run baru di Apify untuk mengambil data Google Maps
     */
    public function triggerScraping(): array
    {
        $startTime = microtime(true);

        try {
            $this->validateConfig();

            $input = $this->buildApifyInput();

            Log::info('[GoogleReviewService] Triggering Apify scraping', [
                'actor' => $this->actorId,
                'max_reviews' => $this->maxReviews,
                'search_url' => $this->searchUrl,
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post("https://api.apify.com/v2/acts/{$this->actorId}/runs?token={$this->apifyToken}", $input);

            if (!$response->successful()) {
                $statusCode = $response->status();
                $body = $response->body();

                Log::error('[GoogleReviewService] Failed to trigger Apify', [
                    'status' => $statusCode,
                    'body' => $body,
                ]);

                if ($statusCode === 401 || $statusCode === 403) {
                    throw new \RuntimeException('Token Apify tidak valid atau tidak memiliki akses.');
                }

                if ($statusCode === 429) {
                    throw new \RuntimeException('Quota Apify habis. Silakan upgrade akun atau tunggu reset quota.');
                }

                throw new \RuntimeException("Gagal memulai scraping Apify. HTTP {$statusCode}");
            }

            $data = $response->json();
            $runId = data_get($data, 'data.id');

            if (!$runId) {
                throw new \RuntimeException('Apify tidak mengembalikan Run ID.');
            }

            $duration = round(microtime(true) - $startTime, 2);

            Log::info('[GoogleReviewService] Apify scraping triggered successfully', [
                'run_id' => $runId,
                'duration_seconds' => $duration,
            ]);

            return [
                'success' => true,
                'run_id' => $runId,
                'message' => 'Scraping berhasil dimulai.',
                'actor_id' => $this->actorId,
                'duration_seconds' => $duration,
            ];
        } catch (\Throwable $e) {
            Log::error('[GoogleReviewService] Error triggering Apify scraping', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'run_id' => null,
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * ================================================================
     *  2. CEK STATUS RUN
     * ================================================================
     */
    public function getRunStatus(string $runId): array
    {
        try {
            $response = Http::timeout(15)
                ->get("https://api.apify.com/v2/acts/{$this->actorId}/runs/{$runId}?token={$this->apifyToken}");

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'status' => 'UNKNOWN',
                    'message' => 'Gagal mendapatkan status run.',
                ];
            }

            $data = $response->json();
            $status = data_get($data, 'data.status', 'UNKNOWN');

            $result = [
                'success' => true,
                'status' => $status,
                'run_id' => $runId,
                'dataset_id' => data_get($data, 'data.defaultDatasetId'),
                'started_at' => data_get($data, 'data.startedAt'),
                'finished_at' => data_get($data, 'data.finishedAt'),
            ];

            Log::info('[GoogleReviewService] Run status checked', [
                'run_id' => $runId,
                'status' => $status,
            ]);

            return $result;
        } catch (\Throwable $e) {
            Log::error('[GoogleReviewService] Error checking run status', [
                'run_id' => $runId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * ================================================================
     *  3. AMBIL DATASET HASIL SCRAPING
     * ================================================================
     */
    public function getDataset(string $datasetId): array
    {
        try {
            $response = Http::timeout(60)
                ->get("https://api.apify.com/v2/datasets/{$datasetId}/items?token={$this->apifyToken}&format=json&limit={$this->maxReviews}");

            if (!$response->successful()) {
                Log::error('[GoogleReviewService] Failed to fetch dataset', [
                    'dataset_id' => $datasetId,
                    'status' => $response->status(),
                ]);

                return [];
            }

            $items = $response->json();

            if (empty($items)) {
                Log::warning('[GoogleReviewService] Dataset kosong', [
                    'dataset_id' => $datasetId,
                ]);

                return [];
            }

            Log::info('[GoogleReviewService] Dataset fetched successfully', [
                'dataset_id' => $datasetId,
                'items_count' => count($items),
            ]);

            return $items;
        } catch (\Throwable $e) {
            Log::error('[GoogleReviewService] Error fetching dataset', [
                'dataset_id' => $datasetId,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * ================================================================
     *  4. PARSE DAN SIMPAN REVIEW KE DATABASE
     * ================================================================
     */
    public function parseAndSaveReviews(array $items): array
    {
        $stats = [
            'total' => 0,
            'new' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => 0,
        ];

        $placeName = null;
        $placeId = null;
        $totalRatingValue = 0;
        $totalReviewsCount = 0;

        foreach ($items as $item) {
            try {
                $stats['total']++;

                // Ambil metadata tempat dari item
                $itemPlaceName = data_get($item, 'title', data_get($item, 'name', 'Unknown Place'));
                $itemPlaceId = data_get($item, 'placeId', data_get($item, 'place_id', ''));
                $itemTotalScore = data_get($item, 'totalScore', data_get($item, 'averageRating', 0));
                $itemTotalReviews = data_get($item, 'totalReviews', data_get($item, 'reviewCount', 0));

                if ($placeName === null && $itemPlaceName !== 'Unknown Place') {
                    $placeName = $itemPlaceName;
                    $placeId = $itemPlaceId;
                    $totalRatingValue = (float) $itemTotalScore;
                    $totalReviewsCount = (int) $itemTotalReviews;
                }

                // Cek apakah item ini memiliki array reviews di dalamnya
                $nestedReviews = data_get($item, 'reviews', []);

                if (!empty($nestedReviews) && is_array($nestedReviews)) {
                    // Proses setiap review dalam array reviews
                    foreach ($nestedReviews as $review) {
                        Log::info('[GoogleReviewService] Processing nested review', ['review_keys' => array_keys($review)]);
                        $parsedReview = $this->parseApifyReviewItem($review);
                        if ($parsedReview !== null) {
                            $stats = $this->saveSingleReview($parsedReview, $placeName, $placeId, $totalRatingValue, $totalReviewsCount, $stats);
                        } else {
                            $stats['skipped']++;
                            Log::info('[GoogleReviewService] Nested review skipped', ['review' => json_encode($review)]);
                        }
                    }
                    continue; // Skip item utama karena sudah diproses via nested reviews
                }

                // Coba parse sebagai item review tunggal
                $parsed = $this->parseApifyItem($item);

                if ($parsed === null) {
                    $stats['skipped']++;
                    Log::info('[GoogleReviewService] Item skipped, raw:', ['item' => json_encode($item)]);
                    continue;
                }

                // Simpan metadata tempat dari item pertama jika belum ada
                if ($placeName === null) {
                    $placeName = $parsed['place_name'] ?? $itemPlaceName;
                    $placeId = $parsed['place_id'] ?? $itemPlaceId;
                    $totalRatingValue = $parsed['total_rating'] ?: (float) $itemTotalScore;
                    $totalReviewsCount = $parsed['total_reviews'] ?: (int) $itemTotalReviews;
                }

                $stats = $this->saveSingleReview($parsed, $placeName, $placeId, $totalRatingValue, $totalReviewsCount, $stats);
            } catch (\Throwable $e) {
                $stats['errors']++;
                Log::error('[GoogleReviewService] Error processing item', [
                    'error' => $e->getMessage(),
                    'item' => json_encode($item),
                ]);
            }
        }

        // Update semua record dengan metadata tempat jika ada
        if ($placeName && $placeName !== 'Unknown Place') {
            GoogleReview::whereNull('place_name')
                ->orWhere('place_name', '')
                ->update([
                    'place_name' => $placeName,
                    'place_id' => $placeId ?: GoogleReview::raw('place_id'),
                    'total_rating' => $totalRatingValue,
                    'total_reviews' => $totalReviewsCount,
                ]);
        }

        Log::info('[GoogleReviewService] Parse and save completed', $stats);

        return $stats;
    }

    /**
     * Simpan satu review ke database
     */
    private function saveSingleReview(array $parsed, ?string $placeName, ?string $placeId, float $totalRatingValue, int $totalReviewsCount, array $stats): array
    {
        try {
            // Analisa sentiment
            $sentiment = $this->analyzeSentiment($parsed['rating'], $parsed['review_text']);

            // Cek duplikasi
            if (GoogleReview::isDuplicate(
                $parsed['review_id'],
                $parsed['author_name'],
                $parsed['review_text'],
                $parsed['review_date']
            )) {
                // Update metadata tempat dan foto profil
                $updateData = [
                    'total_rating' => $totalRatingValue,
                    'total_reviews' => $totalReviewsCount,
                    'place_name' => $placeName,
                    'place_id' => $placeId,
                    'scraped_at' => now(),
                ];

                // Update foto profil jika ada yang baru
                if (!empty($parsed['profile_photo'])) {
                    $updateData['profile_photo'] = $parsed['profile_photo'];
                }
                if (!empty($parsed['author_url'])) {
                    $updateData['author_url'] = $parsed['author_url'];
                }

                GoogleReview::where('review_id', $parsed['review_id'])
                    ->orWhere(function ($q) use ($parsed) {
                        $q->where('author_name', $parsed['author_name'])
                            ->where('review_text', $parsed['review_text']);
                    })
                    ->update($updateData);

                $stats['updated']++;
                return $stats;
            }

            // Generate review_id jika tidak ada
            $reviewId = $parsed['review_id'] ?: md5(
                $parsed['author_name'] . '|' .
                $parsed['review_text'] . '|' .
                ($parsed['review_date'] ? $parsed['review_date']->format('Y-m-d H:i:s') : '')
            );

            // Simpan review baru
            GoogleReview::updateOrCreate(
                ['review_id' => $reviewId],
                [
                    'place_name' => $placeName ?: $parsed['place_name'],
                    'place_id' => $placeId ?: $parsed['place_id'],
                    'author_name' => $parsed['author_name'],
                    'author_url' => $parsed['author_url'],
                    'rating' => $parsed['rating'],
                    'review_text' => $parsed['review_text'],
                    'review_date' => $parsed['review_date'],
                    'total_rating' => $totalRatingValue,
                    'total_reviews' => $totalReviewsCount,
                    'profile_photo' => $parsed['profile_photo'],
                    'review_photo' => $parsed['review_photo'],
                    'sentiment' => $sentiment['sentiment'],
                    'sentiment_score' => $sentiment['score'],
                    'scraped_at' => now(),
                ]
            );

            $stats['new']++;
        } catch (\Throwable $e) {
            $stats['errors']++;
            Log::error('[GoogleReviewService] Error saving single review', [
                'error' => $e->getMessage(),
                'review' => json_encode($parsed),
            ]);
        }

        return $stats;
    }

    /**
     * ================================================================
     *  5. PROSES SCRAPING LENGKAP (Trigger -> Tunggu -> Ambil -> Simpan)
     * ================================================================
     */
    public function runFullScraping(): array
    {
        $result = [
            'success' => false,
            'status' => 'STARTED',
            'stats' => null,
            'run_id' => null,
            'message' => '',
        ];

        try {
            // 1. Trigger scraping
            $trigger = $this->triggerScraping();

            if (!$trigger['success']) {
                $result['message'] = $trigger['message'];
                $result['status'] = 'FAILED';
                return $result;
            }

            $runId = $trigger['run_id'];
            $result['run_id'] = $runId;

            // 2. Tunggu hingga selesai (polling)
            $maxWaitSeconds = config('services.google_reviews.max_wait_seconds', 300);
            $waitInterval = config('services.google_reviews.wait_interval', 10);
            $elapsed = 0;

            while ($elapsed < $maxWaitSeconds) {
                sleep(min($waitInterval, $maxWaitSeconds - $elapsed));
                $elapsed += $waitInterval;

                $status = $this->getRunStatus($runId);

                if (!$status['success']) {
                    $result['message'] = 'Gagal memeriksa status scraping.';
                    $result['status'] = 'ERROR';
                    return $result;
                }

                if (in_array($status['status'], ['SUCCEEDED', 'FINISHED'])) {
                    $datasetId = $status['dataset_id'];

                    if (empty($datasetId)) {
                        $result['message'] = 'Dataset ID tidak ditemukan.';
                        $result['status'] = 'ERROR';
                        return $result;
                    }

                    // 3. Ambil dataset
                    $items = $this->getDataset($datasetId);

                    if (empty($items)) {
                        $result['message'] = 'Dataset kosong, tidak ada review ditemukan.';
                        $result['status'] = 'COMPLETED';
                        $result['stats'] = ['total' => 0, 'new' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0];
                        $result['success'] = true;
                        return $result;
                    }

                    // 4. Parse dan simpan
                    $stats = $this->parseAndSaveReviews($items);

                    $result['success'] = true;
                    $result['status'] = 'COMPLETED';
                    $result['stats'] = $stats;
                    $result['message'] = "Scraping selesai. {$stats['new']} review baru, {$stats['updated']} review diupdate.";
                    return $result;
                }

                if (in_array($status['status'], ['FAILED', 'ABORTED', 'TIMED-OUT'])) {
                    $result['message'] = "Scraping gagal dengan status: {$status['status']}";
                    $result['status'] = 'FAILED';
                    return $result;
                }

                // Status masih RUNNING, lanjut polling
                Log::info('[GoogleReviewService] Waiting for scraping to complete', [
                    'run_id' => $runId,
                    'status' => $status['status'],
                    'elapsed_seconds' => $elapsed,
                ]);
            }

            // Timeout
            $result['message'] = "Waktu tunggu scraping habis ({$maxWaitSeconds} detik).";
            $result['status'] = 'TIMEOUT';

            return $result;
        } catch (\Throwable $e) {
            Log::error('[GoogleReviewService] Error in full scraping', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $result['message'] = $e->getMessage();
            $result['status'] = 'ERROR';
            return $result;
        }
    }

    /**
     * ================================================================
     *  6. SCRAPING TANPA POLLING (untuk async / queue)
     * ================================================================
     */
    public function scrapeThenFetch(string $runId): array
    {
        try {
            $status = $this->getRunStatus($runId);

            if (!$status['success']) {
                return [
                    'success' => false,
                    'message' => 'Gagal mendapatkan status run.',
                ];
            }

            if (!in_array($status['status'], ['SUCCEEDED', 'FINISHED'])) {
                return [
                    'success' => false,
                    'status' => $status['status'],
                    'message' => "Scraping belum selesai. Status: {$status['status']}",
                ];
            }

            $datasetId = $status['dataset_id'];

            if (empty($datasetId)) {
                return [
                    'success' => false,
                    'message' => 'Dataset ID tidak ditemukan.',
                ];
            }

            $items = $this->getDataset($datasetId);

            if (empty($items)) {
                return [
                    'success' => true,
                    'stats' => ['total' => 0, 'new' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0],
                    'message' => 'Dataset kosong.',
                ];
            }

            $stats = $this->parseAndSaveReviews($items);

            return [
                'success' => true,
                'stats' => $stats,
                'message' => "Data berhasil diproses: {$stats['new']} baru, {$stats['updated']} diupdate.",
            ];
        } catch (\Throwable $e) {
            Log::error('[GoogleReviewService] Error in scrapeThenFetch', [
                'run_id' => $runId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * ================================================================
     *  7. ANALISA SENTIMENT
     * ================================================================
     */
    public function analyzeSentiment(float $rating, ?string $text): array
    {
        // Simple rule-based sentiment analysis
        // Bisa ditingkatkan dengan API NLP atau library seperti PHP-Sentiment

        $positiveKeywords = [
            'bagus', 'enak', 'mantap', 'puas', 'recommended', 'recommend',
            'terbaik', 'ramah', 'cepat', 'bersih', 'nyaman', 'murah',
            'lezat', 'nikmat', 'sempurna', 'excellent', 'good', 'great',
            'love', 'wonderful', 'fantastic', 'amazing', 'delicious',
            'good', 'nice', 'best', 'sukses', 'berhasil', 'oke',
            'top', 'keren', 'wow', 'cocok', 'pas', 'sangat',
        ];

        $negativeKeywords = [
            'buruk', 'jelek', 'kecewa', 'lambat', 'mahal', 'tidak enak',
            'mengecewakan', 'kurang', 'tidak puas', 'parah', 'muak',
            'jijik', 'busuk', 'basi', 'kotor', 'sampah', 'terrible',
            'bad', 'awful', 'horrible', 'disappointed', 'worst',
            'nggak enak', 'gak enak', 'tidak ramah', 'lama', 'pendingin',
            'rusak', 'berantakan', 'ac', 'panas', 'pengap',
        ];

        $textLower = strtolower($text ?? '');

        // Scoring
        $positiveScore = 0;
        $negativeScore = 0;

        foreach ($positiveKeywords as $keyword) {
            if (str_contains($textLower, $keyword)) {
                $positiveScore++;
            }
        }

        foreach ($negativeKeywords as $keyword) {
            if (str_contains($textLower, $keyword)) {
                $negativeScore++;
            }
        }

        // Combine dengan rating
        $ratingScore = ($rating - 3) * 2; // -4 to +4

        $totalScore = $positiveScore - $negativeScore + $ratingScore;

        // Normalisasi ke 0-1
        $normalizedScore = ($totalScore + 6) / 12; // range -6 to +6 -> 0 to 1
        $normalizedScore = max(0, min(1, $normalizedScore));

        if ($normalizedScore >= 0.6) {
            $sentiment = 'positif';
        } elseif ($normalizedScore <= 0.4) {
            $sentiment = 'negatif';
        } else {
            $sentiment = 'netral';
        }

        return [
            'sentiment' => $sentiment,
            'score' => round($normalizedScore, 4),
        ];
    }

    /**
     * ================================================================
     *  8. GET REVIEW DENGAN FILTER (untuk API/Controller)
     * ================================================================
     */
    public function getReviews(array $filters = [])
    {
        $query = GoogleReview::query();

        // Filter rating
        if (!empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        // Filter rating range
        if (!empty($filters['rating_min'])) {
            $query->where('rating', '>=', $filters['rating_min']);
        }
        if (!empty($filters['rating_max'])) {
            $query->where('rating', '<=', $filters['rating_max']);
        }

        // Filter sentiment
        if (!empty($filters['sentiment'])) {
            $query->where('sentiment', $filters['sentiment']);
        }

        // Search text
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('author_name', 'like', "%{$search}%")
                  ->orWhere('review_text', 'like', "%{$search}%");
            });
        }

        // Filter date range
        if (!empty($filters['date_from'])) {
            $query->where('review_date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('review_date', '<=', $filters['date_to']);
        }

        // Sorting
        $sortField = $filters['sort_by'] ?? 'review_date';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $allowedSorts = ['review_date', 'rating', 'author_name', 'created_at'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest('review_date');
        }

        // Pagination
        $perPage = $filters['per_page'] ?? 15;

        return $query->paginate($perPage);
    }

    /**
     * ================================================================
     *  9. DASHBOARD STATISTICS
     * ================================================================
     */
    public function getDashboardStats(): array
    {
        return GoogleReview::getRatingStats();
    }

    /**
     * ================================================================
     *  10. GET RATING DISTRIBUTION (untuk chart)
     * ================================================================
     */
    public function getRatingDistribution(): array
    {
        $stats = GoogleReview::getRatingStats();

        return [
            ['rating' => 5, 'count' => $stats['rating_5']],
            ['rating' => 4, 'count' => $stats['rating_4']],
            ['rating' => 3, 'count' => $stats['rating_3']],
            ['rating' => 2, 'count' => $stats['rating_2']],
            ['rating' => 1, 'count' => $stats['rating_1']],
        ];
    }

    /**
     * ================================================================
     *  11. GET RECENT REVIEWS
     * ================================================================
     */
    public function getRecentReviews(int $limit = 10)
    {
        return GoogleReview::terbaru()->take($limit)->get();
    }

    /**
     * ================================================================
     *  12. SCRAPING INCREMENTAL (hanya review terbaru)
     * ================================================================
     * Trigger scraping dengan maxReviews kecil + filter tanggal.
     * Hanya mengambil review yang belum pernah di-scrape sebelumnya.
     */
    public function runIncrementalScraping(): array
    {
        $result = [
            'success' => false,
            'status' => 'STARTED',
            'stats' => null,
            'run_id' => null,
            'message' => '',
        ];

        try {
            // Dapatkan tanggal review terakhir di database
            $lastReview = GoogleReview::latest('review_date')->first();
            $lastReviewDate = $lastReview?->review_date;

            Log::info('[GoogleReviewService] Starting incremental scraping', [
                'last_review_date' => $lastReviewDate?->format('Y-m-d H:i:s'),
            ]);

            // Trigger dengan limit lebih kecil
            $this->maxReviews = min($this->maxReviews, 20); // Ambil lebih sedikit untuk update
            $trigger = $this->triggerScraping();

            if (!$trigger['success']) {
                $result['message'] = $trigger['message'];
                $result['status'] = 'FAILED';
                return $result;
            }

            $runId = $trigger['run_id'];
            $result['run_id'] = $runId;

            // Polling hingga selesai
            $maxWaitSeconds = config('services.google_reviews.max_wait_seconds', 300);
            $waitInterval = config('services.google_reviews.wait_interval', 10);
            $elapsed = 0;

            while ($elapsed < $maxWaitSeconds) {
                sleep(min($waitInterval, $maxWaitSeconds - $elapsed));
                $elapsed += $waitInterval;

                $status = $this->getRunStatus($runId);

                if (!$status['success']) {
                    $result['message'] = 'Gagal memeriksa status scraping.';
                    $result['status'] = 'ERROR';
                    return $result;
                }

                if (in_array($status['status'], ['SUCCEEDED', 'FINISHED'])) {
                    $datasetId = $status['dataset_id'];

                    if (empty($datasetId)) {
                        $result['message'] = 'Dataset ID tidak ditemukan.';
                        $result['status'] = 'ERROR';
                        return $result;
                    }

                    $items = $this->getDataset($datasetId);

                    if (empty($items)) {
                        $result['message'] = 'Tidak ada review baru ditemukan.';
                        $result['status'] = 'COMPLETED';
                        $result['stats'] = ['total' => 0, 'new' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0];
                        $result['success'] = true;
                        return $result;
                    }

                    // Filter hanya review yang lebih baru dari lastReviewDate
                    if ($lastReviewDate) {
                        $filteredItems = [];
                        foreach ($items as $item) {
                            $reviewDate = data_get($item, 'review_date', data_get($item, 'reviewDate', ''));
                            if (empty($reviewDate)) {
                                $filteredItems[] = $item;
                                continue;
                            }
                            try {
                                $parsedDate = $this->parseDate($reviewDate);
                                if ($parsedDate && $parsedDate > $lastReviewDate) {
                                    $filteredItems[] = $item;
                                }
                            } catch (\Throwable $e) {
                                $filteredItems[] = $item;
                            }
                        }
                        $items = $filteredItems;
                    }

                    if (empty($items)) {
                        $result['message'] = 'Tidak ada review baru ditemukan sejak scraping terakhir.';
                        $result['status'] = 'COMPLETED';
                        $result['stats'] = ['total' => 0, 'new' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0];
                        $result['success'] = true;
                        return $result;
                    }

                    $stats = $this->parseAndSaveReviews($items);

                    $result['success'] = true;
                    $result['status'] = 'COMPLETED';
                    $result['stats'] = $stats;
                    $result['message'] = "Update selesai. {$stats['new']} review baru ditambahkan.";
                    return $result;
                }

                if (in_array($status['status'], ['FAILED', 'ABORTED', 'TIMED-OUT'])) {
                    $result['message'] = "Scraping gagal dengan status: {$status['status']}";
                    $result['status'] = 'FAILED';
                    return $result;
                }
            }

            $result['message'] = "Waktu tunggu scraping habis ({$maxWaitSeconds} detik).";
            $result['status'] = 'TIMEOUT';
            return $result;
        } catch (\Throwable $e) {
            Log::error('[GoogleReviewService] Error in incremental scraping', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $result['message'] = $e->getMessage();
            $result['status'] = 'ERROR';
            return $result;
        }
    }

    /**
     * ================================================================
     *  PRIVATE HELPERS
     * ================================================================
     */

    /**
     * Validasi konfigurasi
     */
    private function validateConfig(): void
    {
        if (empty($this->apifyToken)) {
            throw new \RuntimeException('APIFY_TOKEN tidak dikonfigurasi. Setel di .env atau config/services.php.');
        }

        if (empty($this->searchUrl) && empty($this->placeIds)) {
            throw new \RuntimeException('Search URL atau Place ID harus dikonfigurasi.');
        }
    }

    /**
     * Build input untuk Apify actor
     */
    private function buildApifyInput(): array
    {
        $input = [
            'maxReviews' => $this->maxReviews,
            'maxImages' => 1,
            'language' => 'id',
            'includeReviews' => true,
            'scrapeReviewerInfo' => true,
            'includeImages' => false,
            'maxImagesPerReview' => 1,
        ];

        // Prioritaskan placeIds jika ada
        if (!empty($this->placeIds)) {
            $input['placeIds'] = $this->placeIds;
            return $input;
        }

        // Fallback ke searchUrls
        $input['searchUrls'] = [$this->searchUrl];

        return $input;
    }

    /**
     * Parse item dari Apify response
     */
    private function parseApifyItem(array $item): ?array
    {
        $reviews = data_get($item, 'reviews', []);

        // Jika item punya properti review langsung
        $authorName = data_get($item, 'author_name', data_get($item, 'name', ''));
        $reviewText = data_get($item, 'review_text', data_get($item, 'reviewText', ''));
        $rating = data_get($item, 'rating', data_get($item, 'stars', 0));
        $reviewDate = data_get($item, 'review_date', data_get($item, 'reviewDate', ''));
        $reviewId = data_get($item, 'review_id', data_get($item, 'reviewId', ''));
        $profilePhoto = data_get($item, 'profile_photo', data_get($item, 'profilePhotoUrl', ''));
        $reviewPhoto = data_get($item, 'review_photo', data_get($item, 'reviewPhotoUrl', ''));
        $authorUrl = data_get($item, 'author_url', data_get($item, 'authorUrl', ''));

        // Jika tidak ada review langsung, coba dari array reviews
        if (empty($authorName) && empty($reviewText) && !empty($reviews)) {
            return null; // Akan diproses di loop terpisah
        }

        // Skip jika tidak ada data review yang berarti
        if (empty($authorName) && empty($reviewText)) {
            return null;
        }

        // Parse tanggal
        $parsedDate = $this->parseDate($reviewDate);

        return [
            'place_name' => null, // akan diisi dari metadata item
            'place_id' => null,
            'author_name' => $authorName ?: 'Anonymous',
            'author_url' => $authorUrl ?: null,
            'rating' => is_numeric($rating) ? (float) $rating : 0,
            'review_text' => $reviewText ?: '',
            'review_date' => $parsedDate,
            'total_rating' => 0,
            'total_reviews' => 0,
            'profile_photo' => $profilePhoto ?: null,
            'review_photo' => $reviewPhoto ?: null,
            'review_id' => $reviewId ?: null,
        ];
    }

    /**
     * Parse item review dari nested array "reviews" di response Apify
     * Struktur: setiap item dalam array reviews memiliki field spesifik
     */
    private function parseApifyReviewItem(array $item): ?array
    {
        $keys = array_keys($item);

        // Coba semua kemungkinan field nama author
        $authorName = $item['authorName'] ?? $item['author_name'] ?? $item['name'] ?? $item['userName'] ?? $item['reviewerName'] ?? '';

        // Coba semua kemungkinan field teks review
        $reviewText = $item['reviewText'] ?? $item['review_text'] ?? $item['text'] ?? $item['comment'] ?? '';

        // Coba semua kemungkinan field rating
        $rating = $item['stars'] ?? $item['rating'] ?? $item['starRating'] ?? $item['score'] ?? 0;

        // Coba semua kemungkinan field tanggal
        $reviewDate = $item['publishedAtDate'] ?? $item['publishedAt'] ?? $item['review_date'] ?? $item['reviewDate'] ?? $item['timestamp'] ?? $item['time'] ?? '';

        // Coba semua kemungkinan field ID review
        $reviewId = $item['reviewId'] ?? $item['review_id'] ?? $item['id'] ?? $item['reviewPk'] ?? '';

        // Coba SEMUA kemungkinan field foto profil dari Google Maps / Apify
        $profilePhoto = $item['authorPhotoUrl'] ?? $item['profilePhotoUrl'] ?? $item['profile_photo_url'] ?? $item['profile_photo'] ?? $item['authorPhoto'] ?? $item['userPhoto'] ?? $item['photoUrl'] ?? $item['avatarUrl'] ?? $item['avatar'] ?? $item['photo'] ?? $item['picture'] ?? $item['authorImageUrl'] ?? $item['reviewerPhotoUrl'] ?? $item['userImage'] ?? $item['profilePhoto'] ?? '';

        // Coba semua kemungkinan field foto review
        $reviewPhoto = $item['reviewPhotoUrl'] ?? $item['reviewPhoto'] ?? $item['review_photo'] ?? $item['reviewImageUrl'] ?? $item['photo'] ?? '';

        // Coba semua kemungkinan field URL author
        $authorUrl = $item['authorUrl'] ?? $item['author_url'] ?? $item['authorLink'] ?? $item['profileUrl'] ?? $item['userUrl'] ?? '';

        if (empty($authorName) && empty($reviewText)) {
            Log::info('[GoogleReviewService] parseApifyReviewItem skipped - keys', ['keys' => $keys]);
            return null;
        }

        $parsedDate = $this->parseDate($reviewDate);

        Log::info('[GoogleReviewService] parseApifyReviewItem parsed', [
            'author_name' => $authorName,
            'has_photo' => !empty($profilePhoto),
            'photo_preview' => $profilePhoto ? substr($profilePhoto, 0, 80) : 'none',
            'rating' => $rating,
            'review_id' => $reviewId ? substr($reviewId, 0, 20) : 'none',
        ]);

        return [
            'place_name' => null,
            'place_id' => null,
            'author_name' => $authorName ?: 'Anonymous',
            'author_url' => $authorUrl ?: null,
            'rating' => is_numeric($rating) ? (float) $rating : 0,
            'review_text' => $reviewText ?: '',
            'review_date' => $parsedDate,
            'total_rating' => 0,
            'total_reviews' => 0,
            'profile_photo' => $profilePhoto ?: null,
            'review_photo' => $reviewPhoto ?: null,
            'review_id' => $reviewId ?: null,
        ];
    }

    /**
     * Parse berbagai format tanggal
     */
    private function parseDate($date): ?Carbon
    {
        if (empty($date)) {
            return null;
        }

        try {
            // Unix timestamp
            if (is_numeric($date) && strlen((string) $date) >= 10) {
                return Carbon::createFromTimestamp((int) $date);
            }

            // ISO date string
            return Carbon::parse($date);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
