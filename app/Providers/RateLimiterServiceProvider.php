<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class RateLimiterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for(
            name: 'api',
            callback: fn(Request $request): Limit => Limit::perMinutes(
                decayMinutes: config()->integer('api.throttle.decay_minutes'),
                maxAttempts: config()->integer('api.throttle.max_attempts'),
            )->by($request->user()?->id ?: $request->ip()),
        );
    }
}
