<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MajorResource;
use App\Models\Major;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class MajorController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Major::class);

        $majors = QueryBuilder::for(Major::class)
            ->allowedIncludes('languages')
            ->paginate(config()->integer('api.pagination.per_page'));

        return response()->json(MajorResource::collection($majors));
    }

    public function show(Major $major): Response
    {
        $this->authorize('view', $major);

        $major = QueryBuilder::for(Major::where('id', $major->id))
            ->allowedIncludes('languages')
            ->firstOrFail();

        return response()->json(MajorResource::make($major));
    }
}
