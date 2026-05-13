<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\RecruitmentApplicationResource;
use App\Models\Pivots\RecruitmentApplication;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class RecruitmentApplicationController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', RecruitmentApplication::class);

        $recruitmentApplications = QueryBuilder::for(RecruitmentApplication::class)
            ->allowedIncludes('recruitment', 'application')
            ->whereHas('application', function ($query): void {
                $query->where('user_id', auth()->id());
            })
            ->paginate(config()->integer('api.pagination.per_page'));

        return RecruitmentApplicationResource::collection($recruitmentApplications);
    }

    public function show(RecruitmentApplication $recruitmentApplication): Response
    {
        $this->authorize('view', $recruitmentApplication);

        $recruitmentApplication = QueryBuilder::for(
            RecruitmentApplication::where('id', $recruitmentApplication->id),
        )
            ->allowedIncludes('recruitment', 'application')
            ->firstOrFail();

        return response()->json(RecruitmentApplicationResource::make($recruitmentApplication));
    }
}
