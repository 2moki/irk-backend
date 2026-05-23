<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\LanguageResource;
use App\Models\Language;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class LanguageController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Language::class);

        $languages = QueryBuilder::for(Language::class)
            ->allowedFilters('name')
            ->allowedSorts('name')
            ->paginate(
                $request->has('per_page')
                    ? $request->integer('per_page')
                    : config()->integer('api.pagination.per_page'),
            );

        return response()->json(LanguageResource::collection($languages));
    }

    public function show(Language $language): Response
    {
        $this->authorize('view', $language);

        return response()->json(LanguageResource::make($language));
    }
}
