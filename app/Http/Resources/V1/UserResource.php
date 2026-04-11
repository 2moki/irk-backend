<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read User $resource
 */
class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'first_name' => $this->resource->first_name,
            'middle_name' => $this->resource->middle_name,
            'last_name' => $this->resource->last_name,
            'email' => $this->resource->email,
            'email_verified_at' => DateResource::make($this->resource->email_verified_at),
            'phone_prefix' => $this->resource->phone_prefix,
            'phone_number' => $this->resource->phone_number,
            'pesel' => $this->resource->pesel,
            'document_number' => $this->resource->document_number,
            'date_of_birth' => $this->resource->date_of_birth,
            'gender' => $this->resource->gender,
            'address' => AddressResource::make($this->resource->address),
            'mailing_address' => AddressResource::make($this->resource->mailingAddress),
            'created_at' => DateResource::make($this->resource->created_at),
            'updated_at' => DateResource::make($this->resource->updated_at),
            'deleted_at' => DateResource::make($this->resource->deleted_at),
        ];
    }
}
