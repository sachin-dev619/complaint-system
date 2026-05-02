<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

     'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'broadcasting/auth'   // ✅ IMPORTANT ADD THIS
    ],

    'allowed_methods' => ['*'],

   'allowed_origins' => [
        'http://127.0.0.1:4200',
        'http://localhost:4200',
        'http://127.0.0.1:4201',   // ✅ ADD THIS
        'http://localhost:4201',   // ✅ ADD THIS
        'https://complaint-frontend-qs8l40vp8-sachins-projects-da9a2415.vercel.app',
        'https://complaint-frontend-ctpoasj6z-sachins-projects-da9a2415.vercel.app'
    ],

    'supports_credentials' => true,

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // 'supports_credentials' => false, // ✅ MUST BE TRUE for auth/broadcasting
];
