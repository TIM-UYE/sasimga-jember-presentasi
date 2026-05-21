<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class GoogleExtractorService
{
    protected string $apiKey;
    protected string $host;
    protected string $businessId;

    public function __construct()
    {
        $this->apiKey = env('RAPIDAPI_KEY', '');
        $this->host = env('RAPIDAPI_HOST', 'google-maps-extractor2.p.rapidapi.com');
        $this->businessId = env('RAPIDAPI_BUSINESS_ID', '');
    }

    /**
     * Normalize review item
     */
    protected function normalizeReview(array $r): array
    {
        return [
            'author_name' => data_get($r, 'user_name', 'Anonymous'),
            'text' => data_get($r, 'text', ''),
            'rating' => (int) data_get($r, 'rating', 0),
            'profile_photo_url' => data_get($r, 'user_avatar')
                ?: asset('images/avatar-default.png'),

            'relative_time_description' => data_get($r, 'time', ''),
            'source' => 'Google',
        ];
    }

    /**
     * Get reviews with optional limit.
     *
     * If no limit is provided, returns all available reviews.
     */
    public function getReviews(?int $limit = null): Collection
    {
        $reviews = $this->getAllReviews();

        return $limit === null ? $reviews : $reviews->take($limit);
    }

    /**
     * Fetch ALL reviews with pagination support
     */
    public function getAllReviews(): Collection
{
    if (empty($this->apiKey) || empty($this->businessId)) {
        return collect();
    }

    $cacheKey = 'rapidapi_google_reviews_all_' . md5($this->businessId);
    $cached = Cache::get($cacheKey, []);

    try {
        $result = Cache::remember($cacheKey, now()->addHour(), function () {

            $url = "https://{$this->host}/business_reviews";

            $allReviews = collect();

            $nextToken = null;

            do {

                $query = [
                    'business_id' => $this->businessId,
                    'lang' => 'id',
                    'limit' => 100,
                ];

                if ($nextToken) {
                    $query['next_token'] = $nextToken;
                }

                $response = Http::timeout(30)
                    ->withHeaders([
                        'x-rapidapi-host' => $this->host,
                        'x-rapidapi-key' => $this->apiKey,
                    ])
                    ->get($url, $query);

                if (! $response->ok()) {
                    throw new \RuntimeException(sprintf(
                        'RapidAPI review fetch failed with status %s.',
                        $response->status()
                    ));
                }

                $json = $response->json();

                $items = collect(data_get($json, 'data', []));

                if ($items->isEmpty()) {
                    break;
                }

                $normalized = $items->map(function ($r) {
                    return $this->normalizeReview($r);
                });

                $allReviews = $allReviews->merge($normalized);

                $nextToken = data_get($json, 'next_token') ?: data_get($json, 'next_page_token');

            } while ($nextToken);

            return $allReviews
                ->unique(function ($item) {
                    return md5(
                        $item['author_name'] .
                        $item['text']
                    );
                })
                ->values()
                ->toArray();
        });
    } catch (\Throwable $exception) {
        if (! empty($cached)) {
            return collect($cached);
        }

        return collect();
    }

    return collect($result);
}
}
