<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\VoivodeshipController;

Route::get('/', [VoivodeshipController::class, 'index'])->name('index');
Route::get('{voivodeship}', [VoivodeshipController::class, 'show'])->name('show');
