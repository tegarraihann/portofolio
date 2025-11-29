<?php

return [
    'enabled' => env('ARTICLE_VIEW_TRACKING_ENABLED', true),
    'cookie_name' => env('VIEW_COOKIE_NAME', env('VISIT_COOKIE_NAME', 'visit_token')),
    'cookie_minutes' => env('VIEW_COOKIE_MINUTES', 60 * 24), // 1 hari
    'hash_salt' => env('VIEW_HASH_SALT', env('APP_KEY')),
];
