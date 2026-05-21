<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Google Reviews Scraper Schedule
|--------------------------------------------------------------------------
|
| Scrape Google Maps reviews automatically every hour.
| To run the scheduler, add this Cron entry to your server:
| * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
|
*/

Schedule::command('google-reviews:scrape --async')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground()
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('[Scheduler] Google Reviews scraping completed successfully.');
    })
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('[Scheduler] Google Reviews scraping failed.');
    })
    ->then(function () {
        // Fetch latest results after scraping completes
        \Illuminate\Support\Facades\Artisan::call('google-reviews:scrape', ['--async' => true]);
    })
    ->description('Scrape Google Maps reviews for latest ratings and comments');
