<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\LanguageController;

Route::get('/', [LanguageController::class, 'index'])->name('index');
Route::get('{language}', [LanguageController::class, 'show'])->name('show');
