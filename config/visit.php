<?php

return [
    // Matikan di env jika tidak ingin tracking (VISIT_TRACKING_ENABLED=false)
    'enabled' => env('VISIT_TRACKING_ENABLED', true),

    // Cookie untuk membedakan sesi kunjungan
    'cookie_name' => env('VISIT_COOKIE_NAME', 'visit_token'),
    'cookie_lifetime_minutes' => env('VISIT_COOKIE_MINUTES', 60 * 24), // 1 hari

    // Salt untuk hashing IP (default pakai APP_KEY)
    'hash_salt' => env('VISIT_HASH_SALT', env('APP_KEY')),
];
