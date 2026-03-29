<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api'])->prefix('v1')->as('v1:')->group(function (): void {});

if (App::isLocal()) {
    Route::post('dev-token', AuthTokenController::class)->name('dev-token');
}

Route::get('/user', fn(Request $request) => $request->user())->middleware('auth:sanctum');
