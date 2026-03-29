<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('v1')->as('v1:')->group(function (): void {
    Route::get('/user', fn(Request $request) => $request->user())->name('user');
});

if (App::isLocal()) {
    Route::post('dev-token', AuthTokenController::class)->name('dev-token');
}
