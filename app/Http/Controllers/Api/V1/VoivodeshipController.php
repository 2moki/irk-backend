<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\VoivodeshipResource;
use App\Models\Voivodeship;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class VoivodeshipController extends Controller
{
    public function index(): Response
    {
        $voivodeships = QueryBuilder::for(Voivodeship::class)
            ->allowedFilters('name_en', 'name_pl')
            ->allowedSorts('name_en', 'name_pl')
            ->get();

        return response()->json(VoivodeshipResource::collection($voivodeships));
    }

    public function show(Voivodeship $voivodeship): Response
    {
        return response()->json(VoivodeshipResource::make($voivodeship));
    }
}
