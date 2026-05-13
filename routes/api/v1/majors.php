<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\MajorController;

Route::get('/', [MajorController::class, 'index'])->name('index');
Route::get('{major}', [MajorController::class, 'show'])->name('show');
