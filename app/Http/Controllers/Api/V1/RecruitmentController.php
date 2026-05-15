<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Filters\RecruitmentStatusFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\RecruitmentResource;
use App\Models\Recruitment;
use App\Sorts\MajorNameSort;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class RecruitmentController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Recruitment::class);

        $recruitments = QueryBuilder::for(Recruitment::class)
            ->allowedIncludes('major', 'cost', 'academicYear')
            ->allowedFilters(
                AllowedFilter::partial('major_name', 'major.name'),
                AllowedFilter::custom('status', new RecruitmentStatusFilter()),
                'is_suspended',
                'major_id',
                'academic_year_id',
            )
            ->allowedSorts(
                'start_date',
                'end_date',
                'slots',
                'max_points',
                AllowedSort::custom('major_name', new MajorNameSort()),
            )
            ->defaultSort(AllowedSort::custom('major_name', new MajorNameSort()))
            ->paginate(config()->integer('api.pagination.per_page'));

        return RecruitmentResource::collection($recruitments);
    }

    public function show(Recruitment $recruitment): Response
    {
        $this->authorize('view', $recruitment);

        $recruitment = QueryBuilder::for(Recruitment::where('id', $recruitment->id))
            ->allowedIncludes('major', 'cost', 'academicYear')
            ->firstOrFail();

        return response()->json(RecruitmentResource::make($recruitment));
    }
}
