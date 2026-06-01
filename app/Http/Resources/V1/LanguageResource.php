<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Language $resource
 * @property-read \Illuminate\Database\Eloquent\Relations\Pivot&object{priority: int} $pivot
 */
class LanguageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'priority' => $this->whenPivotLoaded('application_language', fn() => $this->pivot->priority),
        ];
    }
}
