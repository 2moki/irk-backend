<?php

namespace App\Http\Resources\V1;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\UserDocument;

/**
 * @property-read UserDocument $resource
 */
class DocumentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
    */
    public function toArray(Request $request): array
    {
        $expire = now()->addMinutes(30);
        return [
            'id'            => $this->resource->id,
            'thumbnail_url' => URL::temporarySignedRoute(
                    'v1:documents:thumbnail',
                    $expire,
                    ['document' => $this->id]
                ),
            'full_url' => URL::temporarySignedRoute(
                'v1:documents:full',
                $expire,
                ['document' => $this->resource->id]
            ),
        ];
    }
}
