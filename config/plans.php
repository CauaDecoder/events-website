<?php

return [
    'free' => [
        'name' => 'Gratuito',
        'monthly_price' => 0,
        'features' => [],
    ],
    'premium' => [
        'name' => 'Premium',
        'monthly_price' => (float) env('PREMIUM_MONTHLY_PRICE', 49.90),
        'features' => ['builder.countdown', 'builder.gallery', 'builder.map', 'builder.video', 'custom.domain', 'remove.branding'],
    ],
];
