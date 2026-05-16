<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\RecruitmentController;

Route::get('/', [RecruitmentController::class, 'index'])->name('index');
Route::get('{recruitment}', [RecruitmentController::class, 'show'])->name('show');
