<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Application $resource
 */
class ApplicationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'money_balance' => $this->resource->money_balance,
            'required_balance' => $this->resource->required_balance,
            'documents_delivered' => $this->resource->documents_delivered,
            'exam_type' => $this->resource->exam_type,
            'created_at' => DateResource::make($this->resource->created_at),
            'updated_at' => DateResource::make($this->resource->updated_at),
        ];
    }
}
