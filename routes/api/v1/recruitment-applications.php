<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\RecruitmentApplicationController;

Route::get('/', [RecruitmentApplicationController::class, 'index'])->name('index');
Route::get('{recruitmentApplication}', [RecruitmentApplicationController::class, 'show'])->name('show');
