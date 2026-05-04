<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthTokenController;
use App\Http\Middleware\DecryptEmail;
use App\Http\Middleware\Localization;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\RoutePath;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ApplicationController;
use \App\Http\Controllers\Api\V1\RecruitmentController;

Route::middleware(['throttle:api', Localization::class])->prefix('v1')->as('v1:')->group(function (): void {
    Route::prefix('countries')
        ->as('countries:')
        ->group(base_path('routes/api/v1/countries.php'));

    Route::prefix('voivodeships')
        ->as('voivodeships:')
        ->group(base_path('routes/api/v1/voivodeships.php'));

    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::get('user', fn(Request $request) => UserResource::make($request->user()))->name('user');
        Route::put('/user', [UserController::class, 'update']);
        Route::get('/application', [ApplicationController::class, 'show']);
        Route::put('/application', [ApplicationController::class, 'update']);
        Route::get('recruitments', [RecruitmentController::class, 'index']);
    });
});

Route::post(RoutePath::for('password.update', 'auth/reset-password'), [NewPasswordController::class, 'store'])
    ->middleware(['guest:' . config('fortify.guard'), DecryptEmail::class, Localization::class])
    ->name('password.update');

if (App::isLocal()) {
    Route::post('dev-token', AuthTokenController::class)->name('dev-token');
}