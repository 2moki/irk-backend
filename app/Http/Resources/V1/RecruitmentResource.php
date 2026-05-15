<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Recruitment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Recruitment $resource
 */
class RecruitmentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'start_date' => DateResource::make($this->resource->start_date),
            'end_date' => DateResource::make($this->resource->end_date),
            'slots' => $this->resource->slots,
            'max_points' => $this->resource->max_points,
            'major_id' => $this->resource->major_id,
            'academic_year_id' => $this->resource->academic_year_id,
            'cost_id' => $this->resource->cost_id,
            'status' => $this->resource->status,
            'price' => $this->whenLoaded('cost', fn() => $this->resource->cost->price),
            'major' => MajorResource::make($this->whenLoaded('major')),
            'academic_year' => $this->whenLoaded('academicYear', fn() => [
                'id' => $this->resource->academicYear->id,
                'start_year' => $this->resource->academicYear->start_year,
                'billing_type' => $this->resource->academicYear->billing_type,
            ]),
            'created_at' => DateResource::make($this->resource->created_at),
            'updated_at' => DateResource::make($this->resource->updated_at),
        ];
    }
}
