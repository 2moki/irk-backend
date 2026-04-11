<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CountryResource;
use App\Models\Country;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class CountryController extends Controller
{
    public function index(): Response
    {
        $countries = QueryBuilder::for(Country::class)
            ->allowedFilters('code')
            ->allowedSorts('code', 'name_en', 'name_pl')
            ->paginate(config()->integer('api.pagination.per_page'));

        return response()->json(CountryResource::collection($countries));
    }

    public function show(Country $country): Response
    {
        return response()->json(CountryResource::make($country));
    }
}
