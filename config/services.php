<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for all of the third party
    | services that you may use in your application. These services
    | may be used throughout your Laravel application without a lot of
    | friction.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fonnte WhatsApp Service
    |--------------------------------------------------------------------------
    |
    | Configuration for Fonnte WhatsApp API service used for sending
    | order notifications to driver groups and customers.
    |
    */

    'fonnte' => [
        'token' => env('FONNTE_TOKEN'),
        'group_id' => env('FONNTE_GROUP_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Reviews Scraper (Apify)
    |--------------------------------------------------------------------------
    |
    | Configuration for scraping Google Maps reviews using Apify's
    | Google Maps Scraper actor: compass~crawler-google-places
    |
    */

    'google_reviews' => [
        'apify_token' => env('APIFY_TOKEN'),
        'actor_id' => env('APIFY_ACTOR_ID', 'compass~crawler-google-places'),
        'search_url' => env('GOOGLE_MAPS_SEARCH_URL', ''),
        'place_ids' => env('GOOGLE_PLACE_ID') ? [env('GOOGLE_PLACE_ID')] : [],
        'max_reviews' => env('GOOGLE_REVIEWS_MAX', 50),
        'timeout' => env('GOOGLE_REVIEWS_TIMEOUT', 120),
        'max_wait_seconds' => env('GOOGLE_REVIEWS_MAX_WAIT', 300),
        'wait_interval' => env('GOOGLE_REVIEWS_WAIT_INTERVAL', 10),
        'place_name' => env('GOOGLE_PLACE_NAME', ''),
    ],

];
