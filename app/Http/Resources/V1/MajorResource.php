<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Major $resource
 */
class MajorResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'semesters' => $this->resource->semesters,
            'study_level' => $this->resource->studyLevel?->name,
            'study_mode' => $this->resource->studyMode?->name,
            'degree_title' => $this->resource->degreeTitle?->name,
            'languages_limit' => $this->resource->languages_limit,
            'created_at' => DateResource::make($this->resource->created_at),
            'updated_at' => DateResource::make($this->resource->updated_at),
        ];
    }
}
