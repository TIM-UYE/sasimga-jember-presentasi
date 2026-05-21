<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleReview extends Model
{
    use HasFactory;

    protected $table = 'google_reviews';

    protected $fillable = [
        'place_name',
        'place_id',
        'author_name',
        'author_url',
        'rating',
        'review_text',
        'review_date',
        'total_rating',
        'total_reviews',
        'profile_photo',
        'review_photo',
        'review_id',
        'sentiment',
        'sentiment_score',
        'scraped_at',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'total_rating' => 'decimal:1',
        'total_reviews' => 'integer',
        'sentiment_score' => 'decimal:4',
        'review_date' => 'datetime',
        'scraped_at' => 'datetime',
    ];

    /**
     * Scope: hanya review positif (rating >= 4)
     */
    public function scopePositif($query)
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Scope: hanya review negatif (rating <= 2)
     */
    public function scopeNegatif($query)
    {
        return $query->where('rating', '<=', 2);
    }

    /**
     * Scope: review netral (rating 3)
     */
    public function scopeNetral($query)
    {
        return $query->where('rating', 3);
    }

    /**
     * Scope: review berdasarkan sentiment
     */
    public function scopeSentiment($query, string $sentiment)
    {
        return $query->where('sentiment', $sentiment);
    }

    /**
     * Scope: review terbaru
     */
    public function scopeTerbaru($query)
    {
        return $query->orderBy('review_date', 'desc');
    }

    /**
     * Scope: review dengan rating tertinggi
     */
    public function scopeRatingTertinggi($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    /**
     * Ambil data statistik rating untuk dashboard
     */
    public static function getRatingStats(): array
    {
        $total = self::count();

        if ($total === 0) {
            return [
                'total_reviews' => 0,
                'average_rating' => 0,
                'rating_5' => 0,
                'rating_4' => 0,
                'rating_3' => 0,
                'rating_2' => 0,
                'rating_1' => 0,
                'positif' => 0,
                'negatif' => 0,
                'netral' => 0,
                'positif_persen' => 0,
                'negatif_persen' => 0,
                'netral_persen' => 0,
                'place_name' => null,
                'total_rating' => 0,
                'last_scraped' => null,
            ];
        }

        $avg = self::avg('rating');
        $rating5 = self::where('rating', 5)->count();
        $rating4 = self::where('rating', 4)->count();
        $rating3 = self::where('rating', 3)->count();
        $rating2 = self::where('rating', 2)->count();
        $rating1 = self::where('rating', 1)->count();
        $positif = $rating5 + $rating4;
        $negatif = $rating2 + $rating1;

        $latest = self::latest('scraped_at')->first();
        $place = self::whereNotNull('place_name')->first();

        return [
            'total_reviews' => $total,
            'average_rating' => round($avg, 1),
            'rating_5' => $rating5,
            'rating_4' => $rating4,
            'rating_3' => $rating3,
            'rating_2' => $rating2,
            'rating_1' => $rating1,
            'positif' => $positif,
            'negatif' => $negatif,
            'netral' => $rating3,
            'positif_persen' => $total > 0 ? round(($positif / $total) * 100, 1) : 0,
            'negatif_persen' => $total > 0 ? round(($negatif / $total) * 100, 1) : 0,
            'netral_persen' => $total > 0 ? round(($rating3 / $total) * 100, 1) : 0,
            'place_name' => $place?->place_name ?? config('services.google_reviews.place_name', 'Unknown'),
            'total_rating' => $place?->total_rating ?? 0,
            'last_scraped' => $latest?->scraped_at,
        ];
    }

    /**
     * Cek duplikasi berdasarkan kombinasi author_name + review_text + review_date
     */
    public static function isDuplicate(?string $reviewId, string $authorName, string $reviewText, $reviewDate): bool
    {
        if ($reviewId) {
            return self::where('review_id', $reviewId)->exists();
        }

        $reviewDateStr = $reviewDate instanceof \DateTime
            ? $reviewDate->format('Y-m-d H:i:s')
            : $reviewDate;

        return self::where('author_name', $authorName)
            ->where('review_text', $reviewText)
            ->where('review_date', $reviewDateStr)
            ->exists();
    }
}
