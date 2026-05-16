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
            ->paginate(config()->integer('api.pagination.per_page'));

        return response()->json(MajorResource::collection($majors));
    }

    public function show(Major $major): Response
    {
        $this->authorize('view', $major);

        return response()->json(MajorResource::make($major));
    }
}
