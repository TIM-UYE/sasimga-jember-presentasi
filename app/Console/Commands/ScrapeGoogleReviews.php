<?php

namespace App\Console\Commands;

use App\Services\GoogleReviewService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScrapeGoogleReviews extends Command
{
    protected $signature = 'google-reviews:scrape
                            {--async : Jalankan scraping secara async (trigger only, tanpa polling)}
                            {--run-id= : Ambil hasil dari Run ID yang sudah ada}
                            {--force : Force scrape even if recently scraped}';

    protected $description = 'Scrape Google Maps reviews menggunakan Apify Google Maps Scraper dan simpan ke database';

    protected GoogleReviewService $googleReviewService;

    public function __construct(GoogleReviewService $googleReviewService)
    {
        parent::__construct();
        $this->googleReviewService = $googleReviewService;
    }

    public function handle(): int
    {
        $this->info('=== Google Reviews Scraper ===');
        $this->line('');

        // Cek konfigurasi
        $apifyToken = config('services.google_reviews.apify_token', env('APIFY_TOKEN', ''));
        if (empty($apifyToken)) {
            $this->error('❌ APIFY_TOKEN tidak dikonfigurasi!');
            $this->line('');
            $this->warn('Tambahkan ke file .env:');
            $this->info('APIFY_TOKEN=your_apify_token_here');
            $this->line('');
            $this->warn('Atau atur di config/services.php:');
            $this->info("'google_reviews' => [");
            $this->info("    'apify_token' => env('APIFY_TOKEN'),");
            $this->info("    'search_url' => env('GOOGLE_MAPS_SEARCH_URL'),");
            $this->info("    'place_ids' => [env('GOOGLE_PLACE_ID')],");
            $this->info(']');

            Log::error('[ScrapeGoogleReviews] APIFY_TOKEN tidak dikonfigurasi.');

            return Command::FAILURE;
        }

        $searchUrl = config('services.google_reviews.search_url', '');
        $placeIds = config('services.google_reviews.place_ids', []);

        if (empty($searchUrl) && empty($placeIds)) {
            $this->error('❌ Search URL atau Place ID harus dikonfigurasi!');
            $this->line('');
            $this->warn('Tambahkan ke file .env:');
            $this->info('GOOGLE_MAPS_SEARCH_URL=https://www.google.com/maps/place/...');
            $this->info('GOOGLE_PLACE_ID=ChIJ...');
            $this->line('');
            $this->warn('Atau atur di config/services.php:');
            $this->info("'google_reviews' => [");
            $this->info("    'search_url' => env('GOOGLE_MAPS_SEARCH_URL'),");
            $this->info("    'place_ids' => [env('GOOGLE_PLACE_ID')],");
            $this->info(']');

            Log::error('[ScrapeGoogleReviews] Search URL / Place ID tidak dikonfigurasi.');

            return Command::FAILURE;
        }

        // Tampilkan konfigurasi
        $this->table(['Parameter', 'Nilai'], [
            ['Apify Token', substr($apifyToken, 0, 8) . '...' . substr($apifyToken, -4)],
            ['Actor ID', config('services.google_reviews.actor_id', 'compass~crawler-google-places')],
            ['Search URL', $searchUrl ?: '(using place_ids)'],
            ['Place IDs', !empty($placeIds) ? implode(', ', $placeIds) : '(not set)'],
            ['Max Reviews', config('services.google_reviews.max_reviews', 50)],
        ]);

        $this->newLine();

        // Mode async: trigger only
        if ($this->option('async')) {
            $this->info('🚀 Mode Async: Memulai scraping tanpa polling...');
            $result = $this->googleReviewService->triggerScraping();

            if ($result['success']) {
                $this->info('✅ Scraping berhasil dimulai!');
                $this->line('');
                $this->table(['Run ID', 'Actor', 'Durasi'], [
                    [$result['run_id'], $result['actor_id'], $result['duration_seconds'] . 's'],
                ]);
                $this->line('');
                $this->warn('Untuk mengambil hasil nanti, jalankan:');
                $this->info("php artisan google-reviews:scrape --run-id={$result['run_id']}");

                Log::info('[ScrapeGoogleReviews] Async scraping triggered', [
                    'run_id' => $result['run_id'],
                ]);

                return Command::SUCCESS;
            }

            $this->error("❌ Gagal: {$result['message']}");

            Log::error('[ScrapeGoogleReviews] Async scraping failed', [
                'error' => $result['message'],
            ]);

            return Command::FAILURE;
        }

        // Mode: ambil dari run ID yang sudah ada
        if ($runId = $this->option('run-id')) {
            $this->info("🔍 Mengambil hasil dari Run ID: {$runId}...");
            $result = $this->googleReviewService->scrapeThenFetch($runId);

            if ($result['success']) {
                $stats = $result['stats'];
                $this->info('✅ Data berhasil diproses!');
                $this->line('');
                $this->table(['Total', 'Baru', 'Diupdate', 'Skipped', 'Error'], [
                    [$stats['total'], $stats['new'], $stats['updated'], $stats['skipped'], $stats['errors']],
                ]);

                Log::info('[ScrapeGoogleReviews] Data fetched from run ID', [
                    'run_id' => $runId,
                    'stats' => $stats,
                ]);

                return Command::SUCCESS;
            }

            $this->error("❌ Gagal: {$result['message']}");

            Log::error('[ScrapeGoogleReviews] Failed to fetch from run ID', [
                'run_id' => $runId,
                'error' => $result['message'],
            ]);

            return Command::FAILURE;
        }

        // Mode: full scraping (trigger + polling + save)
        $this->info('🔄 Menjalankan full scraping (trigger + polling + save)...');
        $this->warn('Proses ini bisa memakan waktu beberapa menit...');
        $this->newLine();

        $bar = $this->output->createProgressBar(100);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->setMessage('Memulai scraping...');
        $bar->start();

        try {
            $result = $this->googleReviewService->runFullScraping();

            $bar->finish();
            $this->newLine(2);

            if ($result['success']) {
                $stats = $result['stats'];
                $this->info('✅ Scraping selesai!');
                $this->line('');
                $this->line("📋 {$result['message']}");
                $this->line('');

                if ($stats) {
                    $this->table(['Total', 'Baru', 'Diupdate', 'Skipped', 'Error'], [
                        [$stats['total'], $stats['new'], $stats['updated'], $stats['skipped'], $stats['errors']],
                    ]);
                }

                // Tampilkan statistik terkini
                $this->newLine();
                $this->info('📊 Statistik Review Terkini:');
                $dashboardStats = $this->googleReviewService->getDashboardStats();
                $this->table(['Metrik', 'Nilai'], [
                    ['Total Review', $dashboardStats['total_reviews']],
                    ['Rata-rata Rating', $dashboardStats['average_rating']],
                    ['Place Name', $dashboardStats['place_name']],
                    ['Total Rating', $dashboardStats['total_rating']],
                    ['Positif', $dashboardStats['positif'] . ' (' . $dashboardStats['positif_persen'] . '%)'],
                    ['Negatif', $dashboardStats['negatif'] . ' (' . $dashboardStats['negatif_persen'] . '%)'],
                    ['Netral', $dashboardStats['netral'] . ' (' . $dashboardStats['netral_persen'] . '%)'],
                    ['Last Scraped', $dashboardStats['last_scraped'] ? $dashboardStats['last_scraped']->format('Y-m-d H:i:s') : 'Never'],
                ]);

                Log::info('[ScrapeGoogleReviews] Full scraping completed', [
                    'status' => $result['status'],
                    'message' => $result['message'],
                    'stats' => $stats,
                ]);

                return Command::SUCCESS;
            }

            $this->error("❌ Scraping gagal: {$result['message']}");

            Log::error('[ScrapeGoogleReviews] Full scraping failed', [
                'status' => $result['status'],
                'message' => $result['message'],
            ]);

            return Command::FAILURE;
        } catch (\Throwable $e) {
            $bar->finish();
            $this->newLine(2);

            $this->error("❌ Error: {$e->getMessage()}");

            Log::error('[ScrapeGoogleReviews] Error during scraping', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
