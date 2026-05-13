<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Pivots\RecruitmentApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read RecruitmentApplication $resource
 */
class RecruitmentApplicationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'application_id' => $this->resource->application_id,
            'recruitment_id' => $this->resource->recruitment_id,
            'got_points' => $this->resource->got_points,
            'priority' => $this->resource->priority,
            'is_paid' => $this->resource->is_paid,
            'payment_date' => $this->resource->payment_date ? DateResource::make($this->resource->payment_date) : null,
            'application_status' => $this->resource->application_status,
            'created_at' => DateResource::make($this->resource->created_at),
            'updated_at' => DateResource::make($this->resource->updated_at),
            'recruitment' => RecruitmentResource::make($this->whenLoaded('recruitment')),
            'application' => ApplicationResource::make($this->whenLoaded('application')),
        ];
    }
}
