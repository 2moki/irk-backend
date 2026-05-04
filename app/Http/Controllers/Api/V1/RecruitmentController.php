<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Http\JsonResponse;

class RecruitmentController extends Controller
{
    public function index()
{
    return Recruitment::with(['major', 'cost'])->get();
}
}