<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthTokenController;
use App\Http\Middleware\DecryptEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\RoutePath;

Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('v1')->as('v1:')->group(function (): void {
    Route::get('/user', fn(Request $request) => $request->user())->name('user');
});

Route::post(RoutePath::for('password.update', 'auth/reset-password'), [NewPasswordController::class, 'store'])
    ->middleware(['guest:' . config('fortify.guard'), DecryptEmail::class])
    ->name('password.update');

if (App::isLocal()) {
    Route::post('dev-token', AuthTokenController::class)->name('dev-token');
}
