<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Support\Collection;

class RecruitmentController extends Controller
{
    public function index(): Collection
    {
        return Recruitment::with(['major', 'cost'])->get();
    }
}
