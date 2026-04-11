<?php

declare(strict_types=1);

return [
    /**
     * API Rate Limiting
     */
    'throttle' => [
        'max_attempts' => (int) env('API_THROTTLE_MAX', 60),
        'decay_minutes' => (int) env('API_THROTTLE_DECAY', 1),
    ],

    /**
     * Pagination
     */
    'pagination' => [
        'per_page' => 10,
    ],
];
