<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Address $resource
 */
class AddressResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'country' => CountryResource::make($this->resource->country),
            'voivodeship' => VoivodeshipResource::make($this->resource->voivodeship),
            'state' => $this->resource->state,
            'post_code' => $this->resource->post_code,
            'city' => $this->resource->city,
            'street' => $this->resource->street,
            'house_number' => $this->resource->house_number,
            'apartment_number' => $this->resource->apartment_number,
            'post_office' => $this->resource->post_office,
            'created_at' => DateResource::make($this->resource->created_at),
            'updated_at' => DateResource::make($this->resource->updated_at),
        ];
    }
}
