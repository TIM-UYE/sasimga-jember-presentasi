<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\GoogleReview;
use App\Models\KategoriMenu;
use App\Models\Menu;
use App\Models\Video;

class HomeController extends Controller
{
    public function index()
    {
        $menus = Menu::where('is_available', true)
            ->with('kategori')
            ->orderBy('created_at', 'desc')
            ->get();

        $kategoris = KategoriMenu::where('is_active', true)
            ->withCount(['menus' => function($query) {
                $query->where('is_available', true);
            }])
            ->get();

        // Ambil 3 review terbaru dari Google (hasil scraping Apify)
        $testimonis = GoogleReview::orderBy('review_date', 'desc')
            ->take(3)
            ->get()
            ->map(function ($review) {
                return [
                    'author_name' => $review->author_name ?? 'Anonymous',
                    'text' => $review->review_text ?? '',
                    'rating' => (int) $review->rating,
                    'profile_photo_url' => $review->profile_photo,
                    'relative_time_description' => $review->review_date
                        ? $review->review_date->diffForHumans()
                        : 'Baru saja',
                    'source' => 'Google',
                    'sentiment' => $review->sentiment,
                ];
            });

        $galeris = Galeri::where('is_active', true)
            ->orderByDesc('created_at')
            ->get();

        $videos = Video::where('is_active', true)
            ->orderByDesc('created_at')
            ->get();

        return view('frontend.pages.home', compact('menus', 'kategoris', 'testimonis', 'galeris', 'videos'));
    }
}
