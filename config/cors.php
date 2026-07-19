<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => array_filter(array_map('trim', explode(',', env('FRONTEND_URL', 'http://localhost:3000')))),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['ETag', 'X-Request-ID'],
    'max_age' => 3600,
    'supports_credentials' => true,
];
