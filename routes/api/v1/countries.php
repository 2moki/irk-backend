<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\CountryController;

Route::get('/', [CountryController::class, 'index'])->name('index');
Route::get('{country:code}', [CountryController::class, 'show'])->name('show');
