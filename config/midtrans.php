<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi Midtrans untuk pembayaran via Snap API
    |
    */

    'environment' => env('MIDTRANS_ENVIRONMENT', 'sandbox'),
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),

    'expiry_duration' => env('MIDTRANS_EXPIRY_DURATION', 60),
    'expiry_unit' => env('MIDTRANS_EXPIRY_UNIT', 'minutes'),
];
