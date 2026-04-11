<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Country $resource
 */
class CountryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'code' => $this->resource->code,
            'name_en' => $this->resource->name_en,
            'name_pl' => $this->resource->name_pl,
        ];
    }
}
